<?php

declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Console\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Input\Input;
use Swoft\Log\Helper\CLog;

/**
 * Class GuidCommand
 *
 * @Command()
 */
class GuidCommand
{
    /**
     * @CommandMapping()
     * @param Input $input
     */
    public function test(Input $input): void
    {
        $start = sprintf("%.0f", microtime(true) * 1000);
        $n = 100000; // 生成速度：MacBook pro i5 8G,CPU 1.4 GHz 平均60-70个id/毫秒
        $arr = [];
        for ($i = 0; $i < $n; $i++) {
            /*$workerId = random_int(0, 31);
            $dataCenterId = random_int(0, 31);
            $id = snowflakeGuid($workerId, $dataCenterId);*/

            $id = snowflakeGuid();
            if ($i % 5000 == 0) {
                Clog::info($id);
            }
            /*if (in_array($id, $arr)) {
                CLog::error('ID重复');
            }
            $arr[] = $id;*/
        }
        $end = sprintf("%.0f", microtime(true) * 1000);
        $speed = $n / ($end - $start);
        CLog::info("finished {$speed}/ms");
    }
}
