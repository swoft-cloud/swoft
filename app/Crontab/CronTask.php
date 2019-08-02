<?php declare(strict_types=1);

namespace App\Crontab;

use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;

/**
 * Class CronTask
 *
 * @since 2.0
 *
 * @Scheduled()
 */
class CronTask
{
    /**
     * @Cron("* * * * * *")
     */
    public function secondTask()
    {
        printf("second task run: %s ", date('Y-m-d H:i:s', time()));
    }

    /**
     * @Cron("0 * * * * *")
     */
    public function minuteTask()
    {
        printf("minute task run: %s ", date('Y-m-d H:i:s', time()));
    }

}