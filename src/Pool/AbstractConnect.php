<?php

namespace Swoft\Pool;

/**
 *
 *
 * @uses      AbstractConnect
 * @version   2017年09月28日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractConnect implements IConnect
{
    /**
     * @var ConnectPool
     */
    protected $connectPool;

    public function __construct(ConnectPool $connectPool)
    {
        $this->connectPool = $connectPool;
        $this->createConnect();
    }
}