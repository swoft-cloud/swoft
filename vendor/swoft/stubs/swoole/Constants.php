<?php

define('SWOOLE_VERSION', '1.8.1'); //当前Swoole的版本号

define('HTTP_GLOBAL_ALL', 1);
define('HTTP_GLOBAL_GET', 2);
define('HTTP_GLOBAL_POST', 4);
define('HTTP_GLOBAL_COOKIE', 8);

/**
 * new swoole_server 构造函数参数
 */
define('SWOOLE_BASE', 1); //使用Base模式，业务代码在Reactor中直接执行
define('SWOOLE_THREAD', 2); //使用线程模式，业务代码在Worker线程中执行
define('SWOOLE_PROCESS', 3); //使用进程模式，业务代码在Worker进程中执行
define('SWOOLE_PACKET', 0x10);

/**
 * new swoole_client 构造函数参数
 */
define('SWOOLE_SOCK_TCP', 1); //创建tcp socket
define('SWOOLE_SOCK_TCP6', 3); //创建tcp ipv6 socket
define('SWOOLE_SOCK_UDP', 2); //创建udp socket
define('SWOOLE_SOCK_UDP6', 4); //创建udp ipv6 socket
define('SWOOLE_SOCK_UNIX_DGRAM', 5); //创建udp socket
define('SWOOLE_SOCK_UNIX_STREAM', 6); //创建udp ipv6 socket

define('SWOOLE_SSL', 5);

define('SWOOLE_TCP', 1); //创建tcp socket
define('SWOOLE_TCP6', 2); //创建tcp ipv6 socket
define('SWOOLE_UDP', 3); //创建udp socket
define('SWOOLE_UDP6', 4); //创建udp ipv6 socket
define('SWOOLE_UNIX_DGRAM', 5);
define('SWOOLE_UNIX_STREAM', 6);

define('SWOOLE_SOCK_SYNC', 0); //同步客户端
define('SWOOLE_SOCK_ASYNC', 1); //异步客户端

define('SWOOLE_SYNC', 0); //同步客户端
define('SWOOLE_ASYNC', 1); //异步客户端

define('SWOOLE_KEEP', 512); //客户端保持长连接

/**
 * new swoole_lock构造函数参数
 */
define('SWOOLE_FILELOCK', 2); //创建文件锁
define('SWOOLE_MUTEX', 3); //创建互斥锁
define('SWOOLE_RWLOCK', 1); //创建读写锁
define('SWOOLE_SPINLOCK', 5); //创建自旋锁
define('SWOOLE_SEM', 4); //创建信号量

define('SWOOLE_EVENT_WRITE', 1);
define('SWOOLE_EVENT_READ', 2);

define('SWOOLE_SSLv3_METHOD', 1);
define('SWOOLE_SSLv3_SERVER_METHOD', 1);
define('SWOOLE_SSLv3_CLIENT_METHOD', 1);
define('SWOOLE_SSLv23_METHOD', 1);
define('SWOOLE_SSLv23_SERVER_METHOD', 1);
define('SWOOLE_SSLv23_CLIENT_METHOD', 1);
define('SWOOLE_TLSv1_METHOD', 1);
define('SWOOLE_TLSv1_SERVER_METHOD', 1);
define('SWOOLE_TLSv1_CLIENT_METHOD', 1);
define('SWOOLE_TLSv1_1_METHOD', 1);
define('SWOOLE_TLSv1_1_SERVER_METHOD', 1);
define('SWOOLE_TLSv1_1_CLIENT_METHOD', 1);
define('SWOOLE_TLSv1_2_METHOD', 1);
define('SWOOLE_TLSv1_2_SERVER_METHOD', 1);
define('SWOOLE_TLSv1_2_CLIENT_METHOD', 1);
define('SWOOLE_DTLSv1_METHOD', 1);
define('SWOOLE_DTLSv1_SERVER_METHOD', 1);
define('SWOOLE_DTLSv1_CLIENT_METHOD', 1);

define('WEBSOCKET_OPCODE_TEXT', 0x1);
define('WEBSOCKET_OPCODE_BINARY', 0x2);

define('WEBSOCKET_STATUS_CONNECTION', 1);
define('WEBSOCKET_STATUS_HANDSHAKE', 2);
define('WEBSOCKET_STATUS_FRAME', 3);
define('WEBSOCKET_STATUS_ACTIVE', 3);
