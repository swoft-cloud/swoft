<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

function user_func(): string
{
    return 'hello';
}

/**
 * 生成guid
 *
 * @param int $workerId 节点ID [0,32)
 * @param int $dataCenterId 数据中心ID [0,32)
 * @return string
 */
function snowflakeGuid(int $workerId = 0, int $dataCenterId = 0): string
{
    return bean('snowflake')->id($workerId, $dataCenterId);
}
