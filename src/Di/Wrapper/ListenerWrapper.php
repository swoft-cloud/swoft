<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\Listener;

/**
 *
 *
 * @uses      ListenerWrapper
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ListenerWrapper extends AbstractWrapper
{
    protected $classAnnotations
        = [
            Listener::class
        ];

    protected $propertyAnnotations
        = [
            Inject::class
        ];

    public function isParseClassAnnotations($annotations)
    {
        return isset($annotations[Listener::class]);
    }

    public function isParsePropertyAnnotations($annotations)
    {
        return isset($annotations[Inject::class]);
    }

    public function isParseMethodAnnotations($annotations)
    {
        return false;
    }
}