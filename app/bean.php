<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\Db\Database;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Redis\RedisDb;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Server\SwooleEvent;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\WebSocket\Server\WebSocketServer;

return [
    'noticeHandler'      => [
        'logFile' => '@runtime/logs/notice-%d{Y-m-d-H}.log',
    ],
    'applicationHandler' => [
        'logFile' => '@runtime/logs/error-%d{Y-m-d}.log',
    ],
    'logger'             => [
        'flushRequest' => false,
        'enable'       => false,
        'json'         => false,
    ],
    'httpServer'         => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            // 'rpc' => bean('rpcServer'),
            // 'tcp' => bean('tcpServer'),
        ],
        'process'  => [
            // 'monitor' => bean(\App\Process\MonitorProcess::class)
            // 'crontab' => bean(CrontabProcess::class)
        ],
        'on'       => [
            // SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true,
            'worker_num'            => 6,
            // static handle
            // 'enable_static_handler'    => true,
            // 'document_root'            => dirname(__DIR__) . '/public',
        ]
    ],
    'httpDispatcher'     => [
        // Add global http middleware
        'middlewares'      => [
            \App\Http\Middleware\FavIconMiddleware::class,
            \Swoft\Http\Session\SessionMiddleware::class,
            // \Swoft\Whoops\WhoopsMiddleware::class,
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
        ],
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class
        ]
    ],
    'db'                 => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test;host=127.0.0.1',
        'username' => 'root',
        'password' => 'swoft123456',
        'charset'  => 'utf8mb4',
    ],
    'db2'                => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test2;host=127.0.0.1',
        'username' => 'root',
        'password' => 'swoft123456',
        'charset'  => 'utf8mb4',
        //        'dbSelector' => bean(DbSelector::class)
    ],
    'db2.pool'           => [
        'class'    => Pool::class,
        'database' => bean('db2'),
    ],
    'db3'                => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test2;host=127.0.0.1',
        'username' => 'root',
        'password' => 'swoft123456',
        'charset'  => 'utf8mb4',
    ],
    'db3.pool'           => [
        'class'    => Pool::class,
        'database' => bean('db3')
    ],
    'migrationManager'   => [
        'migrationPath' => '@database/Migration',
    ],
    'redis'              => [
        'class'    => RedisDb::class,
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
        'option'   => [
            'prefix' => 'swoft:'
        ]
    ],
    'user'               => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'          => [
        'class'  => ServicePool::class,
        'client' => bean('user'),
    ],
    'rpcServer'          => [
        'class' => ServiceServer::class,
        'listener' => [
            'http' => bean('httpServer'),
        ]
    ],
    'wsServer'           => [
        'class'    => WebSocketServer::class,
        'port'     => 18308,
        'listener' => [
            'rpc' => bean('rpcServer'),
            // 'tcp' => bean('tcpServer'),
        ],
        'on'       => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
            // Enable task must add task and finish event
            SwooleEvent::TASK    => bean(TaskListener::class),
            SwooleEvent::FINISH  => bean(FinishListener::class)
        ],
        'debug'    => 1,
        // 'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting'  => [
            'task_worker_num'       => 6,
            'task_enable_coroutine' => true,
            'worker_num'            => 6,
            'log_file'              => alias('@runtime/swoole.log'),
            // 'open_websocket_close_frame' => true,
        ],
    ],
    // 'wsConnectionManager' => [
    //     'storage' => bean('wsConnectionStorage')
    // ],
    // 'wsConnectionStorage' => [
    //     'class' => \Swoft\Session\SwooleStorage::class,
    // ],
    /** @see \Swoft\WebSocket\Server\WsMessageDispatcher */
    'wsMsgDispatcher'    => [
        'middlewares' => [
            \App\WebSocket\Middleware\GlobalWsMiddleware::class
        ],
    ],
    /** @see \Swoft\Tcp\Server\TcpServer */
    'tcpServer'          => [
        'port'  => 18309,
        'debug' => 1,
    ],
    /** @see \Swoft\Tcp\Protocol */
    'tcpServerProtocol'  => [
        // 'type' => \Swoft\Tcp\Packer\JsonPacker::TYPE,
        'type' => \Swoft\Tcp\Packer\SimpleTokenPacker::TYPE,
        // 'openLengthCheck' => true,
    ],
    /** @see \Swoft\Tcp\Server\TcpDispatcher */
    'tcpDispatcher'      => [
        'middlewares' => [
            \App\Tcp\Middleware\GlobalTcpMiddleware::class
        ],
    ],
    'cliRouter'          => [// 'disabledGroups' => ['demo', 'test'],
    ],
];
