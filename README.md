<p align="center">
    <a href="https://github.com/stelin/swoft" target="_blank">
        <img src="http://www.stelin.me/assets/img/swoft.png" alt="swoft" />
    </a>
</p>


[![php version](https://img.shields.io/badge/php-7.0-blue.svg)](http://php.net/)
------------

依赖项(Dependencies)
------------

|依赖|  特性|
|:---:|:---:|
|[twig](https://github.com/twigphp/Twig)|渲染模板|
| [php-di](https://github.com/PHP-DI/PHP-DI)  |  依赖注入|
|[doctrine-annotations](https://github.com/doctrine/annotations)|注解解析|



简介(Introduction)
------------
swoft是基于swoole协程的高性能PHP微服务框架，内置http服务器。框架全协程实现，性能完胜传统的php-fpm模式。

- 基于swoole易扩展
- 内置http协程服务器
- MVC分层设计
- PHP-DI依赖注入
- 强大twig渲染模板
- redis协程连接池(后续)
- mysql协程连接池(后续)
- 高性能RPC实现(后续)
- consul服务发现注册(后续)
- 服务熔断、降级、限流(后续)
- 服务监控(后续)



未完成
------------

- 连接池等待队列
- 熔断器
- restapi封装
- redis封装
- 路由重构及缓存
- mysql封装(ORM)
- 服务注册和发现(mysql)
- crontab定时任务
- inotify文件监控，自动reload
- rpc协议实现
- 服务监控
- 日志统计分析UI
- 功能细化封装




安装(Installation)
------------


文档(Documentation)
-------------
