<?php

namespace swoft\filter;

use swoft\base\ApplicationContext;
use swoft\web\Request;
use swoft\web\Response;

/**
 *
 *
 * @uses      FilterChain
 * @version   2017年05月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class FilterChain implements Filter
{
    /**
     * @var array
     */
    private $filters;

    public function init()
    {
        $newFilters = [];
        foreach ($this->filters as $filterName => $filter){
            $filterClass = $filter['class'];
            $filter['class'] = ApplicationContext::getBean($filterClass);
            $newFilters[] = $filter;
        }
        $this->filters = $newFilters;
    }


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

        /* @var Filter $currentFilter*/
        list($currentFilter, $currentIndex) = $filterAry;

        $nextIndex = $currentIndex + 1;
        return $currentFilter->doFilter($request, $response, $this, $nextIndex);
    }

    private function getCurrentFilter(string $uri, $currentIndex)
    {
        if(!isset($this->filters[$currentIndex])){
            return array();
        }

        $filter = $this->filters[$currentIndex];
        $uriPattern = $filter['uriPattern'];

        /* @var  $filterUriPattern FilterUriPattern*/
        $filterUriPattern = ApplicationContext::getBean(FilterUriPattern::class);
        if($filterUriPattern->match($uri, $uriPattern)){
            $filterObject = $filter['class'];
            return array($filterObject, $currentIndex);
        }
        return $this->getCurrentFilter($uri, $currentIndex +1);
    }

    public function denyFilter(Request $request, Response $response): Response
    {
        return $response;
    }
}