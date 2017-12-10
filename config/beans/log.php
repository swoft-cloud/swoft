<?php
return [
    "noticeHandler"      => [
        "class"     => \Swoft\Log\FileHandler::class,
        "logFile"   => "@runtime/logs/notice.log",
        'formatter' => '${lineFormatter}',
        "levels"    => [
            \Swoft\Log\Logger::NOTICE,
            \Swoft\Log\Logger::INFO,
            \Swoft\Log\Logger::DEBUG,
            \Swoft\Log\Logger::TRACE,
        ]
    ],
    "applicationHandler" => [
        "class"     => \Swoft\Log\FileHandler::class,
        "logFile"   => "@runtime/logs/error.log",
        'formatter' => '${lineFormatter}',
        "levels"    => [
            \Swoft\Log\Logger::ERROR,
            \Swoft\Log\Logger::WARNING
        ]
    ],
    "logger"             => [
        "class"         => \Swoft\Log\Logger::class,
        "name"          => APP_NAME,
        "flushInterval" => 100,
        "flushRequest"  => true,
        "handlers"      => [
            '${noticeHandler}',
            '${applicationHandler}'
        ]
    ],
];
