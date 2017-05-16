<?php

namespace swoft\filter;

use swoft\web\Request;
use swoft\web\Response;

/**
 *
 *
 * @uses      Filter
 * @version   2017年05月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface Filter
{
    public function preFilter();
    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0);
    public function denyFilter(Request $request, Response $response);
    public function postFilter();
}