<?php
// +----------------------------------------------------------------------
// | tp5
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;




class Polyfill
{

    public static function isDebug()
    {
        if (KE_TP_VERSION === '5.0') {
            return \think\App::$debug;
        } else {
            return \think\facade\App::isDebug();
        }
    }


    public static function getRouteFile()
    {
        if (KE_TP_VERSION === '5.0') {
            return APP_PATH . 'build_route.php';
        } else {
            return static::getRootPath() . 'route/build_route.php';
        }
    }


    public static function getRootPath()
    {
        if (KE_TP_VERSION === '5.0') {
            return ROOT_PATH;
        } else {
            return \think\facade\App::getRootPath();
        }
    }


    public static function hooks_exec($class)
    {
        if (KE_TP_VERSION === '5.0') {
            return \think\Hook::exec($class);
        } else {
            return \think\facade\Hook::exec($class);
        }
    }


    public static function console_add($option)
    {
        if (KE_TP_VERSION === '5.0') {
            return \think\Console::addDefaultCommands($option);
        } else {
            return \think\facade\Console::addDefaultCommands($option);
        }
    }

}
