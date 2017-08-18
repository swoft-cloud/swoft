<?php

namespace swoft\di\resolver;

/**
 *
 *
 * @uses      IResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IResource
{
    public function getDefinitions();
}