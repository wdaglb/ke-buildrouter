<?php
// +----------------------------------------------------------------------
// | ke-buildrouter
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

if (defined('THINK_VERSION')) {
    define('THINK_VERSION', '5.0');
} else {
    define('THINK_VERSION', '5.1');
}

var_dump(THINK_VERSION);exit;

if (\think\facade\App::isDebug()) {
    \think\facade\Hook::exec(function () {
        $builder = new \ke\BuildRouter(\think\facade\App::getRootPath(), 'application', \think\facade\App::getRootPath() . 'route/build_route.php');
        $builder->make();
    });
}

\think\Console::addDefaultCommands([
    '\\ke\\Command'
]);
