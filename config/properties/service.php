<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'user' => [
        'name'        => 'redis',
        'uri'         => [
            '127.0.0.1:8099',
            '127.0.0.1:8099',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'maxWaitTime' => 3,
        'maxIdleTime' => 60,
        'timeout'     => 8,
        'useProvider' => false,
        'balancer' => 'random',
        'provider' => 'consul',
    ]
];