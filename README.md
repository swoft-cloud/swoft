<p align="center">
    <a href="https://github.com/swoft-cloud/swoft" target="_blank">
        <img src="http://www.stelin.me/assets/img/swoft.png" alt="swoft" />
    </a>
</p>

[![Latest Version](https://img.shields.io/badge/unstable-v0.2.6-yellow.svg?maxAge=2592000)](https://packagist.org/packages/swoft/swoft)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft)
[![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/swoft/swoft)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=2.0.12-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/swoft/swoft)
[![Hiredis Version](https://img.shields.io/badge/hiredis-%3E=0.1-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/swoft/swoft)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://doc.swoft.org)
[![Swoft License](https://img.shields.io/badge/license-apache%202.0-lightgrey.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

# 简介
首个基于 Swoole 原生协程的新时代 PHP 高性能协程全栈框架，内置协程网络服务器及常用的协程客户端，常驻内存，不依赖传统的 PHP-FPM，全异步非阻塞 IO 实现，以类似于同步客户端的写法实现异步客户端的使用，没有复杂的异步回调，没有繁琐的 yield, 有类似 Go 语言的协程、灵活的注解、强大的全局依赖注入容器、完善的服务治理、灵活强大的 AOP、标准的 PSR 规范实现等等，可以用于构建高性能的Web系统、API、中间件、基础服务等等。

- 基于 Swoole 扩展
- 内置协程网络服务器
- MVC 分层设计
- 高性能路由
- 强大的 AOP (面向切面编程)
- 灵活的注解功能
- 全局的依赖注入容器
- 基于 PSR-7 的 HTTP 消息实现
- 基于 PSR-14 的事件管理器
- 基于 PSR-15 的中间件
- 基于 PSR-16 的缓存设计
- 可扩展的高性能 RPC
- RESTful 支持
- 国际化(i18n)支持
- 快速灵活的参数验证器
- 完善的服务治理，熔断、降级、负载、注册与发现
- 通用连接池 Mysql、Redis、RPC
- 数据库 ORM
- 协程、异步任务投递
- 自定义用户进程
- 协程和同步阻塞客户端无缝自动切换
- 别名机制
- 跨平台热更新自动 Reload
- 强大的日志系统

# 文档
[**中文文档**](https://doc.swoft.org)

QQ 交流群: 548173319

# 环境要求

1. PHP 7.0 +
2. [Swoole 2.0.12](https://github.com/swoole/swoole-src/releases) +, 需开启协程和异步Redis
3. [Hiredis](https://github.com/redis/hiredis/releases)
4. [Composer](https://getcomposer.org/)

# 安装

## 手动安装

* Clone 项目
* 安装依赖 `composer install`

## Composer 安装

* `composer create-project swoft/swoft swoft dev-master`

## Docker 安装

* `docker run -p 80:80 swoft/swoft`

# 配置

若在执行 `composer install` 的时候由程序自动复制环境变量配置文件失败，则可手动复制项目根目录的 `.env.example` 并命名为 `.env`，注意在执行 `composer update` 时并不会触发相关的复制操作

```
# Server
PFILE=/tmp/swoft.pid
PNAME=php-swoft
TCPABLE=true
CRONABLE=false
AUTO_RELOAD=true

# HTTP
HTTP_HOST=0.0.0.0
HTTP_PORT=80

# TCP
TCP_HOST=0.0.0.0
TCP_PORT=8099
TCP_PACKAGE_MAX_LENGTH=2048
TCP_OPEN_EOF_CHECK=false

# Crontab
CRONTAB_TASK_COUNT=1024
CRONTAB_TASK_QUEUE=2048

# Settings
WORKER_NUM=1
MAX_REQUEST=10000
DAEMONIZE=0
DISPATCH_MODE=2
LOG_FILE=@runtime/swoole.log
TASK_WORKER_NUM=1
```

## 启动

**帮助命令**

```
[root@swoft bin]# php swoft -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php swoft -h

Commands:
  entity  the group command list of database entity
  rpc     the group command list of rpc server
  server  the group command list of http-server

Options:
  -v,--version  show version
  -h,--help     show help
```

**HTTP启动**

> 是否同时启动RPC服务器取决于.env文件配置

```php
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft start -d

// 重启
php bin/swoft restart

// 重新加载
php bin/swoft reload

// 关闭服务
php bin/swoft stop

```


**RPC启动**

> 启动独立的RPC服务器

```php
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft rpc:start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft rpc:start -d

// 重启
php bin/swoft rpc:restart

// 重新加载
php bin/swoft rpc:reload

// 关闭服务
php bin/swoft rpc:stop

```

# 更新日志

[更新日志](changelog.md)

# 协议
Swoft 的开源协议为 Apache-2.0，详情参见[LICENSE](LICENSE)。







