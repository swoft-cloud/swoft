<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\RequestMapping;

/**
 * 路由注解封装器
 *
 * @uses      AutoControllerWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoControllerWrapper extends AbstractWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations
        = [
            AutoController::class
        ];

    /**
     * 属性注解
     *
     * @var array
     */
    protected $propertyAnnotations
        = [
            Inject::class
        ];

    /**
     * 方法注解
     *
     * @var array
     */
    protected $methodAnnotations
        = [
            RequestMapping::class
        ];

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations)
    {
        return isset($annotations[AutoController::class]);
    }

    /**
     * 是否解析属性注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations)
    {
        return isset($annotations[Inject::class]);
    }

    /**
     * 是否解析方法注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations)
    {
        return isset($annotations[RequestMapping::class]);
    }
}