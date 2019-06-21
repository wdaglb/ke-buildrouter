<?php
// +----------------------------------------------------------------------
// | 注解路由
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;

use think\App;
use think\Container;

class BuildRouter
{
    private $routes = [];

    private $file;

    /**
     * @var App
     */
    private $app;

    private $appNamespace;


    public function __construct($path, $app, $saveFile = 'build_route.php')
    {
        $this->appNamespace = $app;
        $this->app = Container::get('app');

        $files = $this->get_list($path . $app);

        $this->routes = [];
        foreach ($files as $file) {
            $fs = fopen($file, 'rb');
            $content = fread($fs, filesize($file));
            if (preg_match_all('/\/\*.+?@route\((.+?)\).+?function\s*(\w+)\s*\(/s', $content, $matchs)) {
                $file = str_replace(DIRECTORY_SEPARATOR, '/', $file);
                foreach ($matchs[1] as $index=>$match) {
                    $this->routes[] = [
                        'pattern'=>array_map('trim', explode(',', str_replace('\'', '', $match))),
                        'file'=>$file,
                        'action'=>$matchs[2][$index],
                        'annotation'=>$matchs[0]
                    ];
                }
            }
            fclose($fs);
            unset($fs);
            unset($content);
        }
        unset($files);

        $this->file = $saveFile;
    }


    /**
     * 获取php文件列表
     * @param $dir
     * @return array
     */
    private function get_list($dir)
    {
        static $ret;
        $files = glob($dir . '*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file) && substr($file, -4) === '.php') {
                    $ret[] = $file;
                } else {
                    $this->get_list($file . '/');
                }
            }
        }
        return $ret;
    }


    /**
     * 解析出控制器名
     * @param string $str
     * @return string
     */
    private function parseController($str)
    {
        $str = preg_replace_callback('/(Controller)*\.php$/', function ($match) {
            return '';
        }, $str);
        return $str;
    }


    /**
     * 驼峰转下划风格
     * @param string $str
     * @return string
     */
    private function toUnder($str)
    {
        return strtolower(preg_replace_callback('/([a-z])([A-Z])/', function ($match) {
            return $match[1] . '_' . $match[2];
        }, $str));
    }


    /**
     * 开始解析
     */
    public function make()
    {
        $content = "<?php \r\n";
        $content .= "/* build_route提示：本文件为自动生成，请不要编辑 */\r\n\r\n";
        if (KE_TP_VERSION === '5.0') {
            $content .= "use \\think\\Route;\r\n";
        } else {
            $content .= "use \\think\\facade\\Route;\r\n";
        }

        $prefix = $this->app->config('ke_route_prefix');
        $vars = $this->app->config('ke_route_vars');
        $is_vars = count($vars);
        $multi_module = $this->app->config('app_multi_module');

        foreach ($this->routes as $route) {
            $tmps = explode('/', $route['file']);
            $tmps = array_slice($tmps, array_search($this->appNamespace, $tmps) + 1);

            if ($multi_module) {
                $module = $tmps[0];
                $tmps = array_slice($tmps, 2);
                if (count($tmps) === 1) {
                    $module .= '/' . $this->parseController($tmps[0]) . '/' . $route['action'];
                } else {
                    $end = array_pop($tmps);
                    $module .= '/' . implode('.', $tmps) . '.' . $this->parseController($end) . '/' . $route['action'];
                }
            } else {
                $tmps = array_slice($tmps, 1);
                $module = $this->parseController($tmps[0]) . '/' . $route['action'];
            }

            if (isset($route['pattern'][1])) {
                $method = strtolower($route['pattern'][1]);
            } else {
                $method = 'rule';
            }
            $rule = $route['pattern'][0];
            // 变量替换
            if ($is_vars) {
                $rule = preg_replace_callback('/\$(\w+)/', function ($match) use($vars) {
                    return isset($vars[$match[1]]) ? $vars[$match[1]] : 'null';
                }, $rule);
            }

            $pre = $prefix;
            if (substr($rule, 0, 1) === '/') {
                $pre .= substr($rule, 1);
            } else {
                $pre .= $rule;
            }
            $content .= "Route::{$method}('{$pre}', '{$module}');\r\n";
        }

        file_put_contents($this->file, $content);
    }
}

