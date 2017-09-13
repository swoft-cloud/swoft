<?php

namespace Swoft\Di\Wrapper;

/**
 * 封装器接口
 *
 * @uses      IWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IWrapper
{
    /**
     * 封装注解
     *
     * @param string $className
     * @param array  $annotations
     *
     * @return array|null
     */
    public function doWrapper(string $className, array $annotations);

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations);

    /**
     * 是否解析属性注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations);

    /**
     * 是否解析方法注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations);
}