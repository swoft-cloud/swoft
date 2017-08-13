<?php

namespace swoft\filter;

/**
 *
 *
 * @uses      UriPattern
 * @version   2017年08月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UriPattern implements IUriPattern
{
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
        foreach ($uriPatterns as $pattern){
            $reg = str_replace($searchs, $replaces, $pattern);
            $result = preg_match('/^' . $reg . '$/', $uri);
            if($result){
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