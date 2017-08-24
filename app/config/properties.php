<?php
return [
    "version" => '1.0',
    'autoInitBean' => true,
    'beanScan' => [
        'app\controllers',
        'app\models',
        'app\beans',
    ],
    'env' => 'base',
    'user.stelin.steln' => 'afa',
    'service' =>[
        'user' => [
            'timeout' => 300
        ]
    ]
];