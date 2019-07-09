<?php
// +----------------------------------------------------------------------
// | ke-buildrouter
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


use think\console\Input;
use think\console\Output;

class Command extends \think\console\Command
{
    protected function configure()
    {
        $this->setName('ke-buildrouter');
    }


    protected function execute(Input $input, Output $output)
    {
        $root = Polyfill::getRootPath();
        $builder = new BuildRouter($root, 'application', $root . 'route/build_route.php');
        $builder->make();

        $output->writeln('route build success');
    }

}