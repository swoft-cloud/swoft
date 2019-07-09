<p align="center">
    <a href="https://github.com/swoft-cloud/swoft" target="_blank">
        <img src="http://qiniu.daydaygo.top/swoft-logo.png?imageView2/2/w/300" alt="swoft"/>
    </a>
</p>

[![Latest Stable Version](http://img.shields.io/packagist/v/swoft/swoft.svg)](https://packagist.org/packages/swoft/swoft)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft)
[![Docker Build Status](https://img.shields.io/docker/build/swoft/alphp.svg)](https://hub.docker.com/r/swoft/alphp/)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.3.3-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://www.swoft.org/docs)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

![](public/image/start-http-server.jpg)

Swoft is a PHP microservices coroutine framework based on the Swoole extension. Like Go, Swoft has a built-in coroutine web server and a common coroutine client and is resident in memory, independent of traditional PHP-FPM. There are similar Go language operations, similar to the Spring Cloud framework flexible annotations, powerful global dependency injection container, comprehensive service governance, flexible and powerful AOP, standard PSR specification implementation and so on.

Through three years of accumulation and direction exploration, Swoft has made Swoft the Spring Cloud in the PHP world, which is the best choice for PHP's high-performance framework and microservices management.

> **[中文说明](README.zh-CN.md)**

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

- [中文文档](https://www.swoft.org/docs/2.x/zh-CN/README.html)
- [English](https://en.swoft.org/docs)

## Discuss

- [swoft-cloud/community](https://gitter.im/swoft-cloud/community)
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

- Http server

```bash
[root@swoft swoft]# php bin/swoft http:start
```

- WebSocket server

```bash
[root@swoft swoft]# php bin/swoft ws:start
```

- RPC server

```bash
[root@swoft swoft]# php bin/swoft rpc:start
```

## License

Swoft is an open-source software licensed under the [LICENSE](LICENSE)
