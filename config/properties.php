<?php
return [
    "version" => '1.0',
    'autoInitBean' => true,
    'beanScan' => [
        'App\Controllers',
        'App\Models',
        'App\Beans',
    ],
    'I18n' =>[
        'sourceLanguage' => '@root/messages/',
    ],
    'env' => 'Base',
    'user.stelin.steln' => 'fafafa',
    'Service' =>[
        'user' => [
            'timeout' => 3000
        ]
    ]
];