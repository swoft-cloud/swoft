<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Scope;
use Swoft\Di\Resource\AnnotationResource;

/**
 *
 *
 * @uses      AbstractParser
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractParser implements IParser
{
    /**
     * @var AnnotationResource
     */
    protected $annotationResource;

    protected $defaultProperty;

    protected $defaultClass;

    public function __construct(AnnotationResource $annotationResource)
    {
        $this->annotationResource = $annotationResource;
        $this->defaultClass = ['', Scope::SINGLETON];
        $this->defaultProperty = [null, false];
    }
}