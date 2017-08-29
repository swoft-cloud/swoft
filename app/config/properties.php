<?php
return [
    "version" => '1.0',
    'autoInitBean' => true,
    'beanScan' => [
        'app\controllers',
        'app\models',
        'app\beans',
    ],
    'i18n' =>[
        'sourceLanguage' => '@app/messages/',
    ],
    'env' => 'base',
    'user.stelin.steln' => 'fafafa',
    'service' =>[
        'user' => [
            'timeout' => 3000
        ]
    ]
];