<?php

namespace Swoft\Http;

/**
 *
 *
 * @uses      IHttpClient
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IHttpClient
{
    /**
     * get方法
     */
    const GET = "GET";

    /**
     * post方法
     */
    const POST = "POST";

    /**
     * put方法
     */
    const PUT = "PUT";

    /**
     * delete方法
     */
    const DELETE = "DELETE";

    /**
     * patch方法
     */
    const PATCH = "PATCH";
}