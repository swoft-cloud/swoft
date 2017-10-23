<?php

namespace Swoft\Service;

/**
 *
 *
 * @uses      IServiceConnect
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IServiceConnect
{
    public function reConnect();

    /**
     * @param string $data
     *
     * @return bool
     */
    public function send(string $data): bool;

    /**
     * @return string
     */
    public function recv(): string;
}