<?php
// +----------------------------------------------------------------------
// | ke-buildrouter
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


use think\console\Input;
use think\console\Output;
use think\facade\App;

class Command extends \think\console\Command
{
    protected function configure()
    {
        $this->setName('ke-build');
    }


    protected function execute(Input $input, Output $output)
    {
        $builder = new BuildRoute(App::getRootPath(), 'application', App::getRootPath() . 'route/build_route.php');
        $builder->make();

        $output->writeln('route build success');
    }

}