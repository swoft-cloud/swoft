<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Process\MonitorProcess;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use function bean;
use function vdump;

/**
 * Class ProcController
 *
 * @since 2.0
 *
 * @Controller()
 */
class ProcController
{
    /**
     * @RequestMapping("test")
     *
     * @return string
     */
    public function test(): string
    {
        $mp = bean(MonitorProcess::class);
        vdump($mp->getSwooleProcess()->exportSocket());

        return 'hello';
    }
}
