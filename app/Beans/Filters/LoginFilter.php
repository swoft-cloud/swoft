<?php

namespace App\Beans\Filters;

use Swoft\Filter\Filter;
use Swoft\Filter\FilterChain;
use Swoft\Web\Request;
use Swoft\Web\Response;

/**
 * 登陆验证
 *
 * @uses      CommonParamsFilter
 * @version   2017年05月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class LoginFilter extends Filter
{
    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0)
    {
        $uid = $request->getParameter('uid');
        if($uid != 6){
            $this->denyFilter($request, $response);
            return false;
        }
        return $filterChain->doFilter($request, $response, $filterChain, $currentIndex);
    }

    public function denyFilter(Request $request, Response $response)
    {
        $response->setResponseContent(json_encode(array('status' => 403, 'msg' => 'need login!')));
        $response->setFormat(Response::FORMAT_JSON);
        $response->send();
    }
}