<?php

namespace Swoft\Cache;

use Swoft\Web\AbstractResult;

/**
 * redis延迟收包，返回数据结构
 *
 * @uses      CacheResult
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CacheResult extends AbstractResult
{
    /**
     * 返回收包结果
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->recv(true);
    }
}
