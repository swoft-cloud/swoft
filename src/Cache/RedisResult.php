<?php

namespace Swoft\Cache;

use Swoft\Web\AbstractResult;

/**
 * redis返回数据
 *
 * @uses      RedisResult
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisResult extends AbstractResult
{
    public function getResult()
    {
        return $this->recv(true);
    }
}