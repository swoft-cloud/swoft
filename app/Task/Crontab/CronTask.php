<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Task\Crontab;

use Exception;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Log\Helper\CLog;

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
     *
     * @throws Exception
     */
    public function secondTask(): void
    {
        // $user = new User();
        // $user->setAge(mt_rand(1, 100));
        // $user->setUserDesc('desc');
        //
        // $user->save();
        //
        // $id   = $user->getId();
        // $user = User::find($id)->toArray();

        CLog::info('second task run: %s ', date('Y-m-d H:i:s'));
        // CLog::info(JsonHelper::encode($user));
    }

    /**
     * @Cron("0 * * * * *")
     */
    public function minuteTask(): void
    {
        CLog::info('minute task run: %s ', date('Y-m-d H:i:s'));
    }
}
