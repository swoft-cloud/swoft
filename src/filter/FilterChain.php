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
     * @var array 所有过滤规则表
     */
    private $filterUriPatterns = [];

    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0)
    {
        if(empty($this->filters) || count($this->filters) < $currentIndex + 1){
            return true;
        }

        $uri = $request->getRequestUri();
        $filterAry = $this->getCurrentFilter($uri, $currentIndex);
        if(empty($filterAry)){
            return true;
        }

        /* @var IFilter $currentFilter*/
        list($currentFilter, $currentIndex) = $filterAry;

        $nextIndex = $currentIndex + 1;
        return $currentFilter->doFilter($request, $response, $this, $nextIndex);
    }

    private function getCurrentFilter(string $uri, $currentIndex)
    {
        if(!isset($this->filters[$currentIndex])){
            return array();
        }

        /* @var Filter $filter*/
        $filter = $this->filters[$currentIndex];
        $uriPattern = $filter->getUriPattern();

        $match = false;

        /* @var IUriPattern $filterUriPattern*/
        foreach ($this->filterUriPatterns as $filterUriPattern){
            if($filterUriPattern->isMatch($uri, $uriPattern)){
                $match = true;
                continue;
            }
        }

        if($match){
            return [$filter, $currentIndex];
        }

        return $this->getCurrentFilter($uri, $currentIndex +1);
    }

    public function denyFilter(Request $request, Response $response): Response
    {
        return $response;
    }
}