<?php

namespace app\beans\filters;

use swoft\filter\Filter;
use swoft\filter\FilterChain;
use swoft\web\Request;
use swoft\web\Response;

/**
 *
 *
 * @uses      commonParamsFilter
 * @version   2017年05月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CommonParamsFilter implements Filter
{

    public function preFilter()
    {
        return true;
    }

    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0)
    {

    }

    public function postFilter()
    {

    }

    public function denyFilter(Request $request, Response $response)
    {
    }
}