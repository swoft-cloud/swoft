<?php

namespace Swoft\Filter;

/**
 * 过滤器
 *
 * @uses      Filter
 * @version   2017年08月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Filter implements IFilter
{
    /**
     * @var string 过滤器匹配规则，多个规则逗号隔开。比如"/a/b/c,/c/d"
     */
    protected $uriPattern;

    /**
     * 获取过滤器规则
     *
     * @return string
     */
    public function getUriPattern()
    {
        return $this->uriPattern;
    }
}