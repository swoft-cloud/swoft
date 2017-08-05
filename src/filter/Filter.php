<?php

namespace swoft\filter;

/**
 *
 *
 * @uses      Filter
 * @version   2017年08月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Filter implements IFilter
{
    protected $uriPattern;

    /**
     * @return mixed
     */
    public function getUriPattern()
    {
        return $this->uriPattern;
    }
}