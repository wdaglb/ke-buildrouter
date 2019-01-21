<?php
// +----------------------------------------------------------------------
// | ke-buildrouter.
// +----------------------------------------------------------------------
// | FileName: bootstrap.php
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

define('ROOT_PATH', __DIR__ . '/');
define('KE_TP_VERSION', '5.1');

require ROOT_PATH . '../src/BuildRouter.php';
$builder = new \ke\BuildRouter(ROOT_PATH, 'application', ROOT_PATH . 'build_route.php');
$builder->make();
