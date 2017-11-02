<?php
return [
    'commonParamsFilter' => [
        'class' => \App\beans\Filters\CommonParamsFilter::class,
        'uriPattern' => '/*',
    ],
    'loginFilter' => [
        'class' => \App\beans\Filters\LoginFilter::class,
        'uriPattern' => '/index/login,/index/login2',
    ],
    'Filter' => [
        'filters' => [
            '${commonParamsFilter}',
            '${loginFilter}',
        ],
    ],
];