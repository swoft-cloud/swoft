<?php
return [
    "version" => '1.0',
    'settingPath' => SETTING_PATH,
    'autoInitBean' => true,
    'beanScan' => [
        'app\controllers',
        'app\models',
        'app\beans',
    ],
    'env' => 'base',
    'user.stelin.steln' => 'fafafa',
    'service' =>[
        'user' => [
            'timeout' => 3000
        ]
    ]
];