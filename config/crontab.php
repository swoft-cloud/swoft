<?php

use \Swoft\Crontab\Crontab;

/**
  * @rule 规则
  * 秒级crontab规则（兼容linux-crontab语法）
  *  0     1    2    3    4    5
  *  *     *    *    *    *    *
  *  -     -    -    -    -    -
  *  |     |    |    |    |    |
  *  |     |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
  *  |     |    |    |    +----- month (1 - 12)
  *  |     |    |    +------- day of month (1 - 31)
  *  |     |    +--------- hour (0 - 23)
  *  |     +----------- min (0 - 59)
  *  +------------- sec (0-59) 可忽略，若忽略则最小粒度为分级
*/
return [
        "class"         => Crontab::class,
        "task" =>[
            [
                'rule' => '* * * * * *',
                'execute' => '/usr/bin/echo "每1秒执行"'
            ],
            [
                'rule' => '*/3 * * * * *',
                'execute' => '/usr/bin/echo "每3秒执行（第0秒临界值）"'
            ],
            [
                'rule' => '3-5 * * * * *',
                'execute' => '/usr/bin/echo "第3秒-第5秒执行"'
            ],
            [
                'rule' => '35 * * * * *',
                'execute' => '/usr/bin/echo "第35秒执行"'
            ]
        ]
    ];
