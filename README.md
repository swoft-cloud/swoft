<p align="center">
    <a href="https://github.com/stelin/swoft" target="_blank">
        <img src="http://www.stelin.me/assets/img/swoft.png" alt="swoft" />
    </a>
</p>


# 简介
swoft是基于swoole协程2.x的高性能PHP微服务框架，内置http服务器。框架全协程实现，性能优于传统的php-fpm模式。

- 基于swoole易扩展
- 内置http协程服务器
- MVC分层设计
- 高性能路由
- 全局容器注入
- 高性能RPC
- 服务治理熔断、降级、负载、注册与发现
- RPC服务
- 连接池Mysql、Redis、RPC
- 强大的日志系统

**Future**

- 连接池等待队列
- 国际化i18
- restful风格
- mysql数据库ORM
- inotify自动reload
- crontab定时任务
- 服务监控
- 日志统计分析
- 统一配置中心

# 更新记录

* 2017-08-15 重构console命令行

# 快速入门
## 文档
[**中文文档**](https://swoft.gitbooks.io/swoft/)

QQ交流群:548173319

## 环境要求
1. hiredis
2. composer
2. PHP7.X
3. Swoole2.x且开启协程和异步redis

## 安装

* clone项目
* composer install安装依赖
* 配置base.php
* 设置启动参数swoft.ini

## 启动

启动服务支持HTTP和TCP同时启动，swoft.ini中配置。

**常用命令**

```php
//启动服务,是否是守护进程，根据swoft.ini配置
php swoft.php start

//守护进程启动，覆盖swoft.ini守护进程配置
php swoft.php start -d

// 重启
php swoft.php restart

// 重新加载
php swoft.php reload

// 关闭服务
php swoft.php stop

```

**Swoft.ini参数**

```shell
[swoft]
;;;;;;;;;;;;;;;;;;;
; About swoft.ini   ;
;;;;;;;;;;;;;;;;;;;

[server]
pfile = '/tmp/swoft.pid';
pname = "php-swf";

[tcp]
enable = 1;
host = "0.0.0.0"
port = 8099
type = SWOOLE_SOCK_TCP

[http]
host = "0.0.0.0"
port = 80
model = SWOOLE_PROCESS
type = SWOOLE_SOCK_TCP

[setting]
worker_num = 4
max_request = 10000
daemonize = 0;
dispatch_mode = 2
log_file = SWOOLE_LOG_PATH
```

## 路由器

路由解析有两种方式，注册路由和自动解析，所有路由都在routes.php中配置。建议所有路由都提前注册，或者通过注解注册，不建议使用自动路由解析。路由配置参数(base.php):

```php
return [
    // ...
    'router'      => [
        'class'  => \swoft\web\Router::class,
        'ignoreLastSep'  => false, // 是否忽略最后一个斜杠，设置false后，/user/index和/user/index/是两个不同的路由
        'tmpCacheNumber' => 1000,// 缓存路由数，最近一1000条
        'matchAll'       => '', // 匹配所有，所有请求都会匹配到这个uri或闭包
    ],
    // ...
];
```
### 路由实例

```php
//匹配 GET 请求. 处理器是个闭包 Closure
$router->get('/', function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("hello");
    $resposne->send();
});

// 匹配参数 'test/john'
$router->get('/test/{name}', function ($arg) {
    echo $arg; // 'john'
}, [
    'tokens' => [
        'name' => '\w+', // 添加参数匹配限制。若不添加对应的限制，将会自动设置为匹配除了'/'外的任何字符
    ]
]);

// 可选参数支持。匹配 'hello' 'hello/john'
$router->get('/hello[/{name}]', function ($name = 'No') {
    echo $name; // 'john'
}, [
    'tokens' => [
        'name' => '\w+', // 添加参数匹配限制
    ]
]);

// 匹配 POST 请求
$router->post('/user/login', function () {
    $request = App::getRequest();
    var_dump($request->getGetParameters(), $request->getPostParameters());
});

// 匹配 GET 或者 POST
$router->map(['get', 'post'], '/user/login', function () {
    $request = App::getRequest();
    var_dump($request->getGetParameters(), $request->getPostParameters());
});

// 允许任何请求方法
$router->any('/home', function () {
    $resposne = RequestContext::getResponse();
    $resposne->setResponseContent("hello, you request page is /home");
    $resposne->send();
});
$router->any('/404', function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("Sorry,This page not found.");
    $resposne->send();
});

// 路由组
$router->group('/user', function ($router) {
    $router->get('/', function () {
        $resposne = App::getResponse();
        $resposne->setResponseContent("hello. you access: /user/");
        $resposne->send();
    });
    $router->get('/index', function () {
        $resposne = App::getResponse();
        $resposne->setResponseContent("hello. you access: /user/index");
        $resposne->send();
    });
});

// 通过@符号连接控制器类和方法名可以指定执行方法
$router->get('/', app\controllers\Home::class);
$router->get('/index', 'app\controllers\Home@index');
$router->get('/about', 'app\controllers\Home@about');

// 访问 '/home/test' 将会执行 'app\controllers\Home::test()'
$router->any('/home/{any}', app\controllers\Home::class);

// 可匹配 '/home', '/home/test' 等
$router->any('/home[/{name}]', app\controllers\Home::class);

// 配置 matchAll 可用于拦截所有请求，目前有如下两种方式。
//路由path
'matchAll' => '/about',

//回调
'matchAll' => function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("System Maintaining ... ...");
    $resposne->send();
},
```


## 控制器
一个继承\swoft\web\Controller的类既是控制器，控制器有两种注解自动注册和手动注册两种方式。建议使用注解自动注册，方便简洁，维护简单。多次注册相同的路由前者会被后者覆盖。

### 注解自动注册
注解自动注册常用到三个注解@AutoController、@Inject、@RequestMapping.

## 连接池
连接池使用简单，只需在base.php里面配置对应服务连接池即可。

> @AutoController
已经使用@AutoController，不能再使用@Bean注解。
@AutoController注解不需要指定bean名称，统一类为bean名称
@AutoController()默认自动解析controller前缀，并且使用驼峰格式。
@AutoController(prefix="/demo2")或@AutoController("/demo2")功能一样，两种使用方式。
@Inject
使用和之前的一样
@RequestMapping
@RequestMapping(route="/index2")或@RequestMapping("/index2")功能一样两种方式使用，这种默认是支持get和post方式@RequestMapping(route="/index2", method=RequestMethod::GET)注册支持的方法
不使用@RequestMapping或RequestMapping()功能一样，都是默认解析action方法，以驼峰格式，注册路由。


```php
return [

    // ...

    // RCP打包、解包
    "packer"          => [
        'class' => JsonPacker::class
    ],
    // 服务发现bean, 目前系统支持consul,只行实现
    'consulProvider'       => [
        'class' => \swoft\service\ConsulProvider::class
    ],

    // user服务连接池
    "userPool"            => [
        "class"           => \swoft\pool\ServicePool::class,
        "uri"             => '127.0.0.1:8099,127.0.0.1:8099', // useProvider为false时，从这里识别配置
        "maxIdel"         => 6,// 最大空闲连接数
        "maxActive"       => 10,// 最大活跃连接数
        "maxWait"         => 20,// 最大的等待连接数
        "timeout"         => '${config.service.user.timeout}',// 引用properties.php配置值
        "balancer"        => '${randomBalancer}',// 连接创建负载
        "serviceName"     => 'user',// 服务名称，对应连接池的名称格式必须为xxxPool/xxxBreaker
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}' // useProvider为true使用，用于发现服务
    ],
    // user服务熔断器
    "userBreaker" => [
        'class'           => \swoft\circuit\CircuitBreaker::class,
        'delaySwithTimer' => 8000
    ],

    // ...

];
```

## 缓存
缓存目前只支持redis,redis使用有两种方式直接调用和延迟收包调用。

```php
// 直接调用
RedisClient::set('name', 'redis client stelin', 180);
$name = RedisClient::get('name');
RedisClient::get($name);

// 延迟收包调用
$ret = RedisClient::deferCall('get', ['name']);
$ret2 = RedisClient::deferCall('get', ['name']);

$result = $ret->getResult();
$result2 = $ret2->getResult();

$data = [
'redis' => $name,
'defer' => $result,
'defer2' => $result2,
];
```

## RPC调用
RPC及内部服务通过监听TCP端口实现，通过swoft.ini日志配置TCP监听端口信息。RPC调用内部实现连接池、熔断器、服务注册与发现等。

```php
// 直接调用
$result = Service::call("user", 'User::getUserInfo', [2,6,8]);

//并发调用
$res = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
$res2 = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
$users = $res->getResult();
$users2 = $res2->getResult();


$deferRet = $users;
$deferRet2 = $users2;
```

## HttpClient
系统提供HttpClient来实现HTTP调用，目前有两种方式，直接调用和延迟收包调用，延迟收包，一般用于并发调用。

```php
// 直接调用
$requestData = [
  'name' => 'boy',
  'desc' => 'php'
];

$result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
$result = $result;

// 延迟调用方式实现两个请求并发调用
$ret = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
$ret2 = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
$defRet1 = $ret->getResult();
$defRet2 = $ret->getResult();
```

## 日志

日志记录一般用户问题的问的分析，系统的定位。目前日志规划有debug trace error info warning notice等级别。每种不同的级别用户记录不同重要程度的信息。系统会为每一个请求生成一条notice,并且一个请求产生的所有日志都有一个相同的logid,notice里面记录该请求的详细信息，比如uri 总共耗时 缓存或db操作时间等等信息。

```php
// 标记开始
App::profileStart("tag");

// 直接输出异常
App::error(new \Exception("error exception"));
App::error("this errro log");
App::info("this errro log");

// 数组
App::error(['name' => 'boy']);
App::debug("this errro log");

// 标记结束
App::profileEnd("tag");

// 统计缓存命中率
App::counting("cache", 1, 10);
```






