<p align="center">
    <a href="https://github.com/swoft-cloud/swoft" target="_blank">
        <img src="http://qiniu.daydaygo.top/swoft-logo.png?imageView2/2/w/300" alt="swoft" />
    </a>
</p>

[![Latest Version](https://img.shields.io/badge/beta-v1.0.0-green.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/releases)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft)
[![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=2.1.3-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)
[![Hiredis Version](https://img.shields.io/badge/hiredis-%3E=0.1-brightgreen.svg?maxAge=2592000)](https://github.com/redis/hiredis)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://doc.swoft.org)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

**[中文说明](README_CN.md)**

## Introduction

The first high-performance PHP coroutine full-stack componentization framework based on Swoole native coroutine, built-in coroutine web server and commonly-used coroutine client, resident memory, which has no dependency on PHP-FPM, asynchronous non-blocking IO implementation, similar to synchronous client style of writing to achieve the use of asynchronous clients, without complex asynchronous callback, no tedious yield, similar  Go language coroutines, flexible annotations framework, a powerful global dependency injection container base on annotations, and great service governance , flexible and powerful AOP, PSR specification implementation, etc., could be used to build high-performance Web systems, APIs, middleware, basic services, microservice and so on.

- Base on Swoole extension
- Built-in HTTP, TCP, WebSocket Coroutine Server
- Powerful AOP (Aspect Oriented Programming)
- Flexible and comprehensive annotations framework
- Global dependency injection container
- PSR-7 based HTTP message implementation
- PSR-14 based event manager
- PSR-15 based middleware
- PSR-16 based cache design
- Scalable high performance RPC
- Holistic service governance, fallback, load balance, service registration and discovery
- Database ORM
- Universal connection pools
- Mysql, Redis, RPC, HTTP Coroutine Clients
- Coroutine driver client and blocking driver client seamlessly switch automatically
- Coroutine and asynchronous task delivery
- Custom user processes
- RESTful supported
- Internationalization (i18n) supported
- High performance router
- Fast and flexible parameter validator
- Alias mechanism
- Powerful log component
- Cross-platform application auto-reload mechanism


## Document

[**Chinese Document**](https://doc.swoft.org)  
[**English Document**](https://doc.swoft.org) Not yet, please help us to complete it.

QQ Group1: 548173319      
QQ Group2: 778656850

## Environmental Requirements

1. PHP 7.0 +
2. [Swoole 2.1.3](https://github.com/swoole/swoole-src/releases) + ( >= 4.1 is better), *coroutine* and *async redis client* options are required
3. [Hiredis](https://github.com/redis/hiredis/releases)
4. [Composer](https://getcomposer.org/)

## Install

### Manual Installation

* Clone project
* Install requires `composer install`

### Install by Composer

* `composer create-project swoft/swoft swoft`

### Install by Docker

* `docker run -p 80:80 swoft/swoft`

### Install by Docker-Compose

* `cd swoft`
* `docker-compose up`

## Configuration

If automatically copied `.env` file operation fails when `composer install` was executed, the `.env.example` that in root directory can be manually copied and named `.env`. Note that `composer update` will not trigger related copy operations.

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

# WebSocket
WS_ENABLE_HTTP=true

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

## Management

### Help command

```text
[root@swoft]# php bin/swoft -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php bin/swoft {command} [arguments ...] [options ...]

Commands:
  entity  The group command list of database entity
  gen     Generate some common application template classes
  rpc     The group command list of rpc server
  server  The group command list of http-server
  ws      There some commands for manage the webSocket server

Options:
  -v, --version  show version
  -h, --help     show help
```

### Start HTTP Server

```bash
// Start HTTP Server
php bin/swoft start

// Start Daemonize HTTP Server
php bin/swoft start -d

// Restart HTTP server
php bin/swoft restart

// Reload HTTP server
php bin/swoft reload

// Stop HTTP server
php bin/swoft stop
```

### Start WebSocket Server

Start WebSocket Server, optional whether to support HTTP processing.

```bash
// Star WebSocket Server
php bin/swoft ws:start

// Start Daemonize WebSocket Server
php bin/swoft ws:start -d

// Restart WebSocket server
php bin/swoft ws:restart

// Reload WebSocket server
php bin/swoft ws:reload

// Stop WebSocket server
php bin/swoft ws:stop
```

### Start RPC Server

Start an independent RPC Server.

```bash
// Start RPC Server
php bin/swoft rpc:start

// Start Daemonize RPC Server
php bin/swoft rpc:start -d

// Restart RPC Server
php bin/swoft rpc:restart

// Reload RPC Server
php bin/swoft rpc:reload

// Stop RPC Server
php bin/swoft rpc:stop
```

## Changelog

[Changelog](changelog.md)

## License

Swoft is an open-source software licensed under the [LICENSE](LICENSE)
