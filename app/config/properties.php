<?php
return [
    "version" => '1.0',
    'env' => 'base',
    'beanScan' => [
        'app\controllers',
        'app\models',
    ],
    'user.stelin.steln' => 'afa',
    'service' =>[
        'user' => [
            'timeout' => 300
        ]
    ]
];