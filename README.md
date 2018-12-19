## KE-BuileRouter TP注解路由

**要求**

ThinkPHP >= 5.0

**安装**

`
composer require ke/buildrouter
`

**使用**

只需要使用composer安装扩展就自动加载,无需任何其它代码来启动<br/>

当TP设置为调试模式时(也就是app_debug为true)时每次请求都会生成route/build_route.php<br/>
注意：如果是5.0则会生成application/build_route.php


<br/>

部署时你应该使用
`php think ke-buildrouter`
生成路由表


**注解格式**

与TP官方的差不多，目前只支持
`
@route('rule', 'method')
`
必须输入method，否则不识别

**简单的例子**

`

 /**
  * @route('test', 'get')
  */
  public function index()
  {
     return 'hello';
  }
 
`

后续可能支持option参数的设置?
