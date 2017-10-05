<?php

namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Annotation\AutoProcess;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Log;

/**
 * 进程注解封装器
 *
 * @uses      AutoProcessWrapper
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoProcessWrapper extends AbstractWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations
        = [
            AutoProcess::class,
            Log::class
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
    protected $methodAnnotations = [];

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations)
    {
        return isset($annotations[AutoProcess::class]);
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
        return false;
    }
}