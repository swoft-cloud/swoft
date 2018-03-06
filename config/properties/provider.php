<?php
return [
    'consul' => [
        'address' => '',
        'port'    => 8500,
        'register' => [
            'id'                => '',
            'name'              => '',
            'tags'              => [],
            'enableTagOverride' => false,
            'service'           => [
                'address' => 'localhost',
                'port'   => '8099',
            ],
            'check'             => [
                'id'       => '',
                'name'     => '',
                'tcp'      => 'localhost:8099',
                'interval' => 10,
                'timeout'  => 1,
            ],
        ],
        'discovery' => [
            'name' => 'user',
            'dc' => 'dc',
            'near' => '',
            'tag' =>'',
            'passing' => true
        ]
    ],
];