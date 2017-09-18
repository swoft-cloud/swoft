<?php

namespace Swoft\Filter;

use Swoft\Di\Annotation\Bean;

/**
 * 过滤器规则匹配
 *
 * @Bean("uriPattern")
 * @uses      UriPattern
 * @version   2017年08月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UriPattern implements IUriPattern
{
    /**
     * 规则匹配
     *
     * @param string $uri        请求uri
     * @param string $uriPattern 具体过滤规则，多个逗号隔开。比如"/a/b/c,/c/e"
     *
     * @return bool 返回uri是否匹配当前规则
     */
    public function isMatch(string $uri, string $uriPattern): bool
    {
        $searchs = [
            '.',
            '*',
            '/'
        ];
        $replaces = [
            '\.',
            '.*',
            '\/'
        ];

        $isMatch = false;
        $uriPatterns = explode(",", $uriPattern);
        foreach ($uriPatterns as $pattern) {
            $reg = str_replace($searchs, $replaces, $pattern);
            $result = preg_match('/^' . $reg . '$/', $uri);
            if ($result) {
                $isMatch = true;
                break;
            }
        }

        if ($isMatch) {
            return true;
        }
        return false;
    }
}
