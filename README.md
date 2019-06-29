<p align="center">
    <a href="https://github.com/swoft-cloud/swoft" target="_blank">
        <img src="http://qiniu.daydaygo.top/swoft-logo.png?imageView2/2/w/300" alt="swoft"/>
    </a>
</p>

[![Latest Version](https://img.shields.io/badge/version-v2.0.2-green.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/releases)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft)
[![Docker Build Status](https://img.shields.io/docker/build/swoft/alphp.svg)](https://hub.docker.com/r/swoft/alphp/)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.3.3-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://www.swoft.org/docs)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

⚡️ Modern High performance AOP and Coroutine PHP Framework, base on Swoole

## Feature

- Built-in high performance network server(Http/Websocket/RPC)
- Flexible componentization
- Flexible annotation function
- Diversified command terminal(Console)
- Powerful Aspect Oriented Programming（AOP）
- Perfect Container management、Dependency Injection (DI)
- Flexible event mechanism
- Implementation of HTTP message based on PSR-7
- Event Manager Based on PSR-14
- Middleware based on PSR-15
- Internationalization(i18n) support
- Simple and efficient parameter validator
- High performance connection pool(Mysql/Redis/RPC)，Automatic reconnection 
- Database is highly compatible Laravel
- Cache Redis highly compatible Laravel
- Efficient task processing
- Flexible exception handling
- Powerful log system

## Document

- [中文](https://www.swoft.org/docs/2.x/zh-CN/README.html)
- [English](https://en.swoft.org/docs)

QQ Group1: 548173319      
QQ Group2: 778656850

## Requirement

- [PHP 7.1 +](https://github.com/php/php-src/releases)
- [Swoole 4.3.4 + ](https://github.com/swoole/swoole-src/releases)
- [Composer](https://getcomposer.org/)

## Install

### Composer

* `composer create-project swoft/swoft swoft`

## Start

```text
[root@swoft swoft]# php bin/swoft http:start
2019/06/02-11:18:06 [INFO] Swoole\Runtime::enableCoroutine
2019/06/02-11:18:06 [INFO] Swoft\SwoftApplication:__construct(14) Set alias @base=/data/www/swoft
2019/06/02-11:18:06 [INFO] Swoft\SwoftApplication:__construct(14) Set alias @app=@base/app
2019/06/02-11:18:06 [INFO] Swoft\SwoftApplication:__construct(14) Set alias @config=@base/config
2019/06/02-11:18:06 [INFO] Swoft\SwoftApplication:__construct(14) Set alias @runtime=@base/runtime
2019/06/02-11:18:06 [INFO] Project path is /data/www/swoft
2019/06/02-11:18:06 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) Env file(/data/www/swoft/.env) is loaded
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) Annotations is scanned(autoloader 23, annotation 226, parser 57)
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) config path=/data/www/swoft/config
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) config env=
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) Bean is initialized(singleton 144, prototype 41, definition 30)
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) Event manager initialized(30 listener, 3 subscriber)
2019/06/02-11:18:11 [INFO] Swoft\Event\Manager\EventManager:triggerListeners(324) WebSocket server route registered(module 2, message command 3)
2019/06/02-11:18:11 [INFO] Swoft\Event\Manager\EventManager:triggerListeners(324) Error manager init completed(2 type, 3 handler, 3 exception)
2019/06/02-11:18:11 [INFO] Swoft\Processor\ApplicationProcessor:handle(221) Console command route registered (group 14, command 5)
                            Information Panel
  ***********************************************************************
  * HTTP     | Listen: 0.0.0.0:18306, type: TCP, mode: Process, worker: 1
  * rpc      | Listen: 0.0.0.0:18307, type: TCP
  ***********************************************************************

HTTP server start success !
2019/06/02-11:18:11 [INFO] Swoft\Event\Manager\EventManager:triggerListeners(324) Registered swoole events:
 start, shutdown, managerStart, managerStop, workerStart, workerStop, workerError, request, task, finish
Server start success (Master PID: 249, Manager PID: 250)
```

## License

Swoft is an open-source software licensed under the [LICENSE](LICENSE)
