## KE-BuileRouter TP注解路由

**要求**

ThinkPHP >= 5.1

**安装**

`
composer require ke/buildrouter
`

**使用**

当TP设置为调试模式时(也就是app_debug为true)时每次请求都会生成route/build_route.php

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

后续可能支持option参数的设置?
