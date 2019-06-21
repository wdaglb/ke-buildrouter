<?php
// +----------------------------------------------------------------------
// | ke-buildrouter
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

if (defined('THINK_VERSION')) {
    define('KE_TP_VERSION', '5.0');
} else {
    define('KE_TP_VERSION', '5.1');
}
defined('KE_ROUTE_AUTO') || define('KE_ROUTE_AUTO', 1);
use ke\Polyfill;

if (KE_ROUTE_AUTO && Polyfill::isDebug()) {
    Polyfill::hooks_exec(function () {
        $builder = new \ke\BuildRouter(Polyfill::getRootPath(), 'application', Polyfill::getRouteFile());
        $builder->make();

        if (KE_TP_VERSION === '5.0') {
            require (APP_PATH . 'build_route' . EXT);
        }
    });
}

Polyfill::console_add([
    '\\ke\\Command'
]);
