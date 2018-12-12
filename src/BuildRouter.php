<?php
// +----------------------------------------------------------------------
// | 注解路由
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;

class BuildRoute
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


    public function make()
    {
        $content = "<?php \r\n";
        $content .= "/* build_route提示：本文件为自动生成，请不要编辑 */\r\n\r\n";

        foreach ($this->routes as $route) {
            $route['file'] = str_replace(DIRECTORY_SEPARATOR, '/', $route['file']);
            $tmps = explode('/', $route['file']);
            $tmps = array_slice($tmps, array_search($this->app, $tmps) + 1);

            $module = $tmps[0];
            $tmps = array_slice($tmps, 2);
            if (count($tmps) === 1) {
                $module .= '/' . strtolower(str_replace('Controller.php', '', $tmps[0])) . '/' . $route['action'];
            } else {
                $end = array_pop($tmps);
                $module .= '/' . implode('.', $tmps) . '.' . strtolower(str_replace('Controller.php', '', $end)) . '/' . $route['action'];
            }
            // $controller =


            $content .= "Route::{$route['pattern'][1]}('{$route['pattern'][0]}', '{$module}');\r\n\r\n";
        }

        file_put_contents($this->file, $content);
    }
}

$root_path = __DIR__ . '/../../../';
$builder = new BuildRoute($root_path, 'application', $root_path . 'route/build_route.php');
$builder->make();
