<?php

namespace Swoft\Filter;

use Swoft\Web\Request;
use Swoft\Web\Response;

/**
 * 过滤器接口定义
 *
 * @uses      IFilter
 * @version   2017年05月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IFilter
{
    /**
     * 过滤实际逻辑处理
     *
     * @param Request     $request
     * @param Response    $response
     * @param FilterChain $filterChain
     * @param int         $currentIndex
     *
     * @return bool
     */
    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0);

    /**
     * 未能通过过滤，逻辑处理
     *
     * @param Request  $request
     * @param Response $response
     */
    public function denyFilter(Request $request, Response $response);
}
