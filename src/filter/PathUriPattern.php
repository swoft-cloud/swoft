<?php

namespace swoft\filter;

/**
 * 路径匹配
 *
 * @uses      PathUriPattern
 * @version   2017年08月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class PathUriPattern implements IUriPattern
{

    /**
     * @var IUriPattern
     */
    private $excludeUriPattern = null;

    /**
     * 路径匹配
     * 例如: /a/b/*,/a/*,/*(所有路径)
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    public function isMatch(string $uri, string $uriPattern): bool
    {
        $reg = '/^(.*)\*$/';
        $result = preg_match($reg, $uriPattern, $match);
        if ($result !== 1) {
            return false;
        }

        $pathReg = $match[1];
        $pathReg = str_replace('/', '\/', $pathReg);
        $matchReg = '/^' . $pathReg . '/';

        $matchResult = preg_match($matchReg, $uri);
        if ($matchResult !== 1) {
            return false;
        }

        if($this->excludeUriPattern != null && $this->isMatch($uri, $uriPattern)){
            return false;
        }

        return true;
    }
}