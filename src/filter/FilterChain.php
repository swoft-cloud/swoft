<?php

namespace swoft\filter;

use swoft\web\Request;
use swoft\web\Response;

/**
 * 过滤链
 *
 * @uses      FilterChain
 * @version   2017年05月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class FilterChain implements IFilter
{
    /**
     * @var array 过滤器
     */
    private $filters = [];

    /**
     * @var IUriPattern 过滤器uri-pattern规则
     */
    private $filterUriPattern;

    /**
     * 执行过滤器
     *
     * @param Request     $request      请求Request
     * @param Response    $response     响应Response
     * @param FilterChain $filterChain  过滤连
     * @param int         $currentIndex 当前执行过滤器的index,默认数组一样0开始
     *
     * @return bool 返回是否处理成功，成功执行逻辑，失败，filter里面实现逻辑数据显示
     */
    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0)
    {
        if (empty($this->filters) || count($this->filters) < $currentIndex + 1) {
            return true;
        }

        $uri = $request->getRequestUri();
        $filterAry = $this->getCurrentFilter($uri, $currentIndex);
        if (empty($filterAry)) {
            return true;
        }

        /* @var IFilter $currentFilter */
        list($currentFilter, $currentIndex) = $filterAry;

        $nextIndex = $currentIndex + 1;
        return $currentFilter->doFilter($request, $response, $this, $nextIndex);
    }

    /**
     * 获取当前符合条件匹配的filter
     *
     * @param string $uri          请求uri地址
     * @param int    $currentIndex 过滤器当前index
     *
     * @return array 返回一个数组，包含filter和index
     */
    private function getCurrentFilter(string $uri, int $currentIndex)
    {
        if (!isset($this->filters[$currentIndex])) {
            return array();
        }

        /* @var Filter $filter */
        $filter = $this->filters[$currentIndex];
        $uriPattern = $filter->getUriPattern();

        $match = $this->filterUriPattern->isMatch($uri, $uriPattern);
        if ($match) {
            return [$filter, $currentIndex];
        }

        return $this->getCurrentFilter($uri, $currentIndex + 1);
    }

    /**
     * filter过滤失败逻辑
     *
     * @param Request  $request
     * @param Response $response
     */
    public function denyFilter(Request $request, Response $response)
    {

    }
}