<?php

namespace Swoft\Di\Wrapper;

/**
 *
 *
 * @uses      IWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IWrapper
{
    public function doWrapper(string $className, array $annotations);
}