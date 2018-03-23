<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'server'  => [
        'pfile'      => env('PFILE', '/tmp/swoft.pid'),
        'pname'      => env('PNAME', 'php-swoft'),
        'tcpable'    => env('TCPABLE', true),
        'cronable'   => env('CRONABLE', false),
        'autoReload' => env('AUTO_RELOAD', true),
    ],
    'tcp'     => [
        'host'               => env('TCP_HOST', '0.0.0.0'),
        'port'               => env('TCP_PORT', 8099),
        'mode'              => env('TCP_MODE', SWOOLE_PROCESS),
        'type'               => env('TCP_TYPE', SWOOLE_SOCK_TCP),
        'package_max_length' => env('TCP_PACKAGE_MAX_LENGTH', 2048),
        'open_eof_check'     => env('TCP_OPEN_EOF_CHECK', false),
    ],
    'http'    => [
        'host'  => env('HTTP_HOST', '0.0.0.0'),
        'port'  => env('HTTP_PORT', 80),
        'mode' => env('HTTP_MODE', SWOOLE_PROCESS),
        'type'  => env('HTTP_TYPE', SWOOLE_SOCK_TCP),
    ],
    'ws'  => [
        // enable handle http request ?
        'enable_http' => env('WS_ENABLE_HTTP', true),
        // other settings will extend the 'http' config
        // you can define separately to overwrite existing settings
    ],
    'crontab' => [
        'task_count' => env('CRONTAB_TASK_COUNT', 1024),
        'task_queue' => env('CRONTAB_TASK_QUEUE', 2048),
    ],
    'setting' => [
        'worker_num'            => env('WORKER_NUM', 1),
        'max_request'           => env('MAX_REQUEST', 10000),
        'daemonize'             => env('DAEMONIZE', 0),
        'dispatch_mode'         => env('DISPATCH_MODE', 2),
        'log_file'              => env('LOG_FILE', '@runtime/logs/swoole.log'),
        'task_worker_num'       => env('TASK_WORKER_NUM', 1),
        'package_max_length'    => env('PACKAGE_MAX_LENGTH', 2048),
        'upload_tmp_dir'        => env('UPLOAD_TMP_DIR', '@runtime/uploadfiles'),
        'document_root'         => env('DOCUMENT_ROOT', BASE_PATH . '/public'),
        'enable_static_handler' => env('ENABLE_STATIC_HANDLER', true),
        'open_http2_protocol'   => env('OPEN_HTTP2_PROTOCOL', false),
        'ssl_cert_file'         => env('SSL_CERT_FILE', ''),
        'ssl_key_file'          => env('SSL_KEY_FILE', ''),
        'task_ipc_mode'         => env('TASK_IPC_MODE', 2),
        'message_queue_key'     => env('MESSAGE_QUEUE_KEY', 0x70001001),
        'task_tmpdir'           => env('TASK_TMPDIR', '/tmp'),
    ],
];
