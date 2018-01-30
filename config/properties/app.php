<?php
return [
    "version"           => '1.0',
    'autoInitBean'      => true,
    'bootScan' => [
        'App\Commands'
    ],
    'beanScan'          => [
        'App\Controllers',
        'App\Models',
        'App\Middlewares',
        'App\Tasks',
        'App\Services',
        'App\Process',
        'App\Breaker',
        'App\Pool',
        'App\Exception',
    ],
    'I18n'              => [
        'sourceLanguage' => '@root/resources/messages/',
    ],
    'env'               => 'Base',
    'user.stelin.steln' => 'fafafa',
    'Service'           => [
        'user' => [
            'timeout' => 3000
        ]
    ],
    'database' => require dirname(__FILE__).DS."db.php",
    'cache'    => require dirname(__FILE__).DS."cache.php",
    'service'  => require dirname(__FILE__).DS."service.php",
    'breaker'  => require dirname(__FILE__).DS."breaker.php",
];