<?php
/**
 * +----------------------------------------------------------------------
 * | 异常demo
 * +----------------------------------------------------------------------
 * | Copyright (c) 2019 http://www.sunnyos.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Date：2019-05-22 16:01:33
 * | Author: Sunny (admin@mail.sunnyos.com) QQ：327388905
 * +----------------------------------------------------------------------
 */

namespace App\Http\Controller;

use App\Exception\ApiException;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * @Controller(prefix="ex")
 */
class ExceptionController
{
    /**
     * @RequestMapping(route="api")
     */
    public function api(){
        throw new ApiException("api of ExceptionController");
    }
}
