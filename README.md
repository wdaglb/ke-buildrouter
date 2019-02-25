## KE-BuileRouter TP注解路由

> 兼容ThinkPHP 5.0与5.1,不兼容3.x

**安装**

```
composer require ke/buildrouter
```

**使用**

只需要使用composer安装扩展就自动加载,无需任何其它代码来启动<br/>

当TP设置为调试模式时(也就是app_debug为true)时每次请求都会生成route/build_route.php<br/>
注意：如果是5.0则会生成application/build_route.php


<br/>

部署时你应该使用下面命令生成路由表
```
php think ke-buildrouter
```


**注解格式**

与TP官方的差不多，目前只支持
```
@route('rule')
// 如需指定请求类型，传入第二个参数即可，支持路由模式下所有的类型
@route('rule', 'method')
```
在控制器类的方法注释中定义（通常称之为**注解路由**），例如：

```

<?php
namespace app\index\controller;

class Index
{
    /**
     * @param  string  $name 数据名称
     * @return mixed
     * @route('hello/:name')
     */
    public function hello($name)
    {
    	return 'hello,'.$name;
    }
}
```
请务必注意注释的规范，可以利用IDE生成规范的注释。

> 该方式定义的路由在调试模式下面实时生效，部署模式则需要使用 php think ke-buildrouter 指令生成路由规则文件。

注意必须严格使用@route(（区分大小写，route和(之间不能有空格），**建议路由定义写在注释最后一段，否则后面需要一个空行。**

默认注册的路由规则是支持所有的请求，如果需要指定请求类型，可以在第二个参数中指定请求类型：
```
<?php
namespace app\index\controller;

class Index
{
    /**
     * @param  string  $name 数据名称
     * @return mixed
     * @route('hello/:name','get')
     */
    public function hello($name)
    {
    	return 'hello,'.$name;
    }
}
```

然后就使用下面的URL地址访问：
```
http://tp5.com/hello/thinkphp
```

页面输出
```
hello,thinkphp
```

**关于URL生成**

> 建议使用url函数生成
```
// application/index/controller/Index.php
url('index/index/index')

// application/index/controller/user/Message.php
url('index/user.message/index')

// application/index/controller/UserMessage.php
url('index/user_message/index')
```
> 注意：需要全部小写,否则生成的路由不会匹配准确

这里借用了[官方文档](https://www.kancloud.cn/manual/thinkphp5_1/469333)


后续可能支持option参数的设置?
