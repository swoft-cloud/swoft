<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Scope;
use Swoft\Di\Resource\AnnotationResource;

/**
 * 抽象解析器
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
     * 注解解析资源
     *
     * @var AnnotationResource
     */
    protected $annotationResource;

    /**
     * 默认属性解析值
     *
     * @var array
     */
    protected $defaultProperty;

    /**
     * 默认类解析值
     *
     * @var array
     */
    protected $defaultClass;

    /**
     * AbstractParser constructor.
     *
     * @param AnnotationResource $annotationResource
     */
    public function __construct(AnnotationResource $annotationResource)
    {
        $this->annotationResource = $annotationResource;
        $this->defaultClass = ['', Scope::SINGLETON];
        $this->defaultProperty = [null, false];
    }
}