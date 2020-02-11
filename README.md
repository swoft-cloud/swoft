![swoft-logo](https://raw.githubusercontent.com/swoft-cloud/swoft/master/public/image/swoft-logo-mdl.png)

[![Latest Stable Version](http://img.shields.io/packagist/v/swoft/swoft.svg)](https://packagist.org/packages/swoft/swoft)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft)
[![Docker Build Status](https://img.shields.io/docker/build/swoft/alphp.svg)](https://hub.docker.com/r/swoft/swoft/)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.4.1-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://www.swoft.org/docs)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)
[![Gitter](https://img.shields.io/gitter/room/swoft-cloud/swoft.svg)](https://gitter.im/swoft-cloud/community)

![start-http-server](https://raw.githubusercontent.com/swoft-cloud/swoft/master/public/image/start-http-server.jpg)

PHP microservice coroutine framework

> **[中文说明](README.zh-CN.md)**

## Introduction

Swoft is a PHP microservices coroutine framework based on the Swoole extension. Like Go, Swoft has a built-in coroutine web server and a common coroutine client and is resident in memory, independent of traditional PHP-FPM. There are similar Go language operations, similar to the Spring Cloud framework flexible annotations, powerful global dependency injection container, comprehensive service governance, flexible and powerful AOP, standard PSR specification implementation and so on.

Through three years of accumulation and direction exploration, Swoft has made Swoft the Spring Cloud in the PHP world, which is the best choice for PHP's high-performance framework and microservices management.

## Feature

- Built-in high performance network server(Http/Websocket/RPC/TCP)
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
- Efficient seconds corntab
- Process pool
- Flexible exception handling
- Powerful log system
- Service registration & discovery
- Service breaker
- Service restrictions
- Service fallback
- Configuration Center
- Apollo
- Consul

## Document

- [中文文档](https://www.swoft.org/docs)
- [English](http://swoft.io/docs)

## Discuss

- Forum https://github.com/swoft-cloud/forum/issues
- Gitter.im https://gitter.im/swoft-cloud/community
- Reddit https://www.reddit.com/r/swoft/
- QQ Group1: 548173319      
- QQ Group2: 778656850

## Requirement

- [PHP 7.1+](https://github.com/php/php-src/releases)
- [Swoole 4.3.4+](https://github.com/swoole/swoole-src/releases)
- [Composer](https://getcomposer.org/)

## Install

### Composer

```bash
composer create-project swoft/swoft swoft
```

## Start

- Http Server

```bash
[root@swoft swoft]# php bin/swoft http:start
```

- WebSocket Server

```bash
[root@swoft swoft]# php bin/swoft ws:start
```

- RPC Server

```bash
[root@swoft swoft]# php bin/swoft rpc:start
```

- TCP Server

```bash
[root@swoft swoft]# php bin/swoft tcp:start
```

- Process Pool

```bash
[root@swoft swoft]# php bin/swoft process:start
```

## Core Components

Component Name   | Packagist Version
--------------------|---------------------
swoft-annotation          |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/annotation.svg)](https://packagist.org/packages/swoft/annotation)
swoft-config              |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/config.svg)](https://packagist.org/packages/swoft/config)
swoft-db                  |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/db.svg)](https://packagist.org/packages/swoft/db)
swoft-framework           |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/framework.svg)](https://packagist.org/packages/swoft/framework)
swoft-i18n                |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/i18n.svg)](https://packagist.org/packages/swoft/i18n)
swoft-proxy               |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/proxy.svg)](https://packagist.org/packages/swoft/proxy)
swoft-rpc-client          |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/rpc-client.svg)](https://packagist.org/packages/swoft/rpc-client)
swoft-stdlib              |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/stdlib.svg)](https://packagist.org/packages/swoft/stdlib)
swoft-tcp-server          |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/tcp-server.svg)](https://packagist.org/packages/swoft/tcp-server)
swoft-aop                 |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/aop.svg)](https://packagist.org/packages/swoft/aop)
swoft-connection-pool     |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/connection-pool.svg)](https://packagist.org/packages/swoft/connection-pool)
swoft-error               |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/error.svg)](https://packagist.org/packages/swoft/error)
swoft-http-message        |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/http-message.svg)](https://packagist.org/packages/swoft/http-message)
swoft-log                 |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/log.svg)](https://packagist.org/packages/swoft/log)
swoft-redis               |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/redis.svg)](https://packagist.org/packages/swoft/redis)
swoft-rpc-server          |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/rpc-server.svg)](https://packagist.org/packages/swoft/rpc-server)
swoft-task                |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/task.svg)](https://packagist.org/packages/swoft/task)
swoft-validator           |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/validator.svg)](https://packagist.org/packages/swoft/validator)
swoft-bean                |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/bean.svg)](https://packagist.org/packages/swoft/bean)
swoft-console             |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/console.svg)](https://packagist.org/packages/swoft/console)
swoft-event               |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/event.svg)](https://packagist.org/packages/swoft/event)
swoft-http-server         |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/http-server.svg)](https://packagist.org/packages/swoft/http-server)
swoft-process             |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/process.svg)](https://packagist.org/packages/swoft/process)
swoft-rpc                 |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/rpc.svg)](https://packagist.org/packages/swoft/rpc)
swoft-server              |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/server.svg)](https://packagist.org/packages/swoft/server)
swoft-tcp                 |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/tcp.svg)](https://packagist.org/packages/swoft/tcp)
swoft-websocket-server    |   [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/websocket-server.svg)](https://packagist.org/packages/swoft/websocket-server)

## Extension Components

Component Name   | Packagist Version
-----------------|---------------------
swoft-apollo  | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/apollo.svg)](https://packagist.org/packages/swoft/apollo)
swoft-breaker | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/breaker.svg)](https://packagist.org/packages/swoft/breaker)
swoft-crontab | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/crontab.svg)](https://packagist.org/packages/swoft/crontab)
swoft-consul  | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/consul.svg)](https://packagist.org/packages/swoft/consul)
swoft-limiter | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/limiter.svg)](https://packagist.org/packages/swoft/limiter)
swoft-view    | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/view.svg)](https://packagist.org/packages/swoft/view)
swoft-whoops  | [![Latest Stable Version](http://img.shields.io/packagist/v/swoft/whoops.svg)](https://packagist.org/packages/swoft/whoops)

## License

Swoft is an open-source software licensed under the [LICENSE](LICENSE)
