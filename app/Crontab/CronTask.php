<?php declare(strict_types=1);

namespace App\Crontab;

use App\Model\Entity\User;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\JsonHelper;

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
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        $user->save();

        $id   = $user->getId();
        $user = User::find($id)->toArray();

        CLog::info("second task run: %s ", date('Y-m-d H:i:s', time()));
        CLog::info(JsonHelper::encode($user));
    }

    /**
     * @Cron("0 * * * * *")
     */
    public function minuteTask()
    {
        CLog::info("minute task run: %s ", date('Y-m-d H:i:s', time()));
    }

}