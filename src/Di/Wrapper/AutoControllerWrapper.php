<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\RequestMapping;

/**
 *
 *
 * @uses      AutoControllerWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoControllerWrapper extends AbstractWrapper
{
    protected $classAnnotations
        = [
            AutoController::class
        ];

    protected $propertyAnnotations
        = [
            Inject::class
        ];

    protected $methodAnnotations
        = [
            RequestMapping::class
        ];

    public function isParseClassAnnotations($annotations)
    {
        return isset($annotations[AutoController::class]);
    }

    public function isParsePropertyAnnotations($annotations)
    {
        return isset($annotations[Inject::class]);
    }

    public function isParseMethodAnnotations($annotations)
    {
        return isset($annotations[RequestMapping::class]);
    }
}