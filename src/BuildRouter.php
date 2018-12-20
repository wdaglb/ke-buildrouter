<?php
// +----------------------------------------------------------------------
// | 注解路由
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;

class BuildRouter
{
    private $routes = [];

    private $file;

    private $app;


    public function __construct($path, $app, $saveFile = 'build_route.php')
    {
        $this->app = $app;

        $files = $this->get_list($path . $app);

        $this->routes = [];
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match_all('/@route\((.+?)\).+?function\s*(\w+)/s', $content, $matchs)) {
                foreach ($matchs[1] as $index=>$match) {
                    $this->routes[] = [
                        'pattern'=>array_map('trim', explode(',', str_replace('\'', '', $match))),
                        'file'=>$file,
                        'action'=>$matchs[2][$index]
                    ];
                }
            }
        }

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
        foreach ($files as $file) {
            if (is_file($file) && preg_match('/\.php$/', $file)) {
                $ret[] = $file;
            } else {
                $this->get_list($file . '/');
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
        // strtolower(str_replace('Controller.php', '', $tmps[0]))
        $str = preg_replace_callback('/(Controller)*\.php$/', function ($match) {
            return '';
        }, $str);
        return strtolower($str);
    }


    public function make()
    {
        $content = "<?php \r\n";
        $content .= "/* build_route提示：本文件为自动生成，请不要编辑 */\r\n\r\n";
        if (KE_TP_VERSION === '5.0') {
            $content .= "use \\think\\Route;\r\n";
        } else {
            $content .= "use \\think\\facade\\Route;\r\n";
        }

        foreach ($this->routes as $route) {
            $route['file'] = str_replace(DIRECTORY_SEPARATOR, '/', $route['file']);
            $tmps = explode('/', $route['file']);
            $tmps = array_slice($tmps, array_search($this->app, $tmps) + 1);

            $module = $tmps[0];
            $tmps = array_slice($tmps, 2);
            if (count($tmps) === 1) {
                $module .= '/' . $this->parseController($tmps[0]) . '/' . $route['action'];
            } else {
                $end = array_pop($tmps);
                $module .= '/' . implode('.', $tmps) . '.' . $this->parseController($end) . '/' . $route['action'];
            }
            // $controller =


            $content .= "Route::{$route['pattern'][1]}('{$route['pattern'][0]}', '{$module}');\r\n";
        }

        file_put_contents($this->file, $content);
    }
}

