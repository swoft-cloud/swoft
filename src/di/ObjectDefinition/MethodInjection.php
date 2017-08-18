<?php

namespace swoft\di\ObjectDefinition;

/**
 *
 *
 * @uses      MethodInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MethodInjection
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var ArgsInjection[]
     */
    private $parameters = [];
}