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

⚡️ 现代的高性能的 AOP & Coroutine PHP框架

> **[EN README](README.md)**

## 功能特色

 - 内置高性能网络服务器（Http/Websocket/RPC）
 - 灵活的组件功能
 - 强大的注解功能
 - 多样化的命令终端（控制台）
 - 强大的面向切面编程（AOP）
 - 容器管理，依赖注入（DI）
 - 灵活的事件机制
 - 基于PSR-7的HTTP消息的实现
 - 基于PSR-14的事件管理
 - 基于PSR-15的中间件
 - 国际化（i18n）支持
 - 简单有效的参数验证器
 - 高性能连接池（Mysql/Redis/RPC），自动重新连接
 - 数据库高度兼容Laravel的使用方式
 - Redis高度兼容Laravel的使用方式
 - 高效的任务处理
 - 灵活的异常处理
 - 强大的日志系统

## 在线文档

- [中文文档](https://www.swoft.org/docs/2.x/zh-CN/README.html)
- [English](https://www.swoft.org/docs/2.x/en)

## 学习交流

- QQ Group1: 548173319      
- QQ Group2: 778656850
- [swoft-cloud/community](https://gitter.im/swoft-cloud/community)

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
