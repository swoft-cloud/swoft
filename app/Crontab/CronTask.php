<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Crontab;

use App\Model\Entity\User;
use Exception;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
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
     *
     * @throws Exception
     */
    public function secondTask()
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        $user->save();

        Log::profileStart('name');
        $id   = $user->getId();
        $user = User::find($id)->toArray();

        Log::profileEnd('name');

        Log::info('info message', ['a' => 'b']);

        CLog::info('second task run: %s ', date('Y-m-d H:i:s', time()));
        CLog::info(JsonHelper::encode($user));
    }

    /**
     * @Cron("0 * * * * *")
     */
    public function minuteTask()
    {
        CLog::info('minute task run: %s ', date('Y-m-d H:i:s', time()));
    }
}
