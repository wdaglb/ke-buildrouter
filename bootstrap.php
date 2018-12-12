<?php
// +----------------------------------------------------------------------
// | ke-buildrouter
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

// 判断TP是否启动
if (class_exists('\\think\\facade\\App')) {
    if (\think\facade\App::isDebug()) {
        \think\facade\Hook::exec(function () {
            $builder = new \ke\BuildRoute(\think\facade\App::getRootPath(), 'application', \think\facade\App::getRootPath() . 'route/build_route.php');
            $builder->make();
        });
    }
    \think\Console::addDefaultCommands([
        '\\ke\\Command'
    ]);
}
