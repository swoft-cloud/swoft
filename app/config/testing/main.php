<?php
$config = \swoft\helpers\ArrayHelper::merge(
    require_once __DIR__ . '/../base.php',
    [
        'params' =>[
            'version' => '1.1.0_testing'
        ]
    ]
);

return $config;