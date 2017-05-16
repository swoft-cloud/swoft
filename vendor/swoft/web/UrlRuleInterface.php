<?php

namespace swoft\web;

/**
 *
 *
 * @uses      UrlRuleInterface
 * @version   2017年04月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface UrlRuleInterface
{
    public function parseRequest(UrlManager $manager, Request $request);
}