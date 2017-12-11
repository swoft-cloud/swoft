<?php
return [
    "version"           => '1.0',
    'autoInitBean'      => true,
    'beanScan'          => [
        'App\Controllers',
        'App\Models',
        'App\Middlewares',
        'App\Tasks',
        'App\Services',
        'App\Process',
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
    ]
];