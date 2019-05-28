<?php
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
     *
     * @throws ApiException
     */
    public function api(){
        throw new ApiException("api of ExceptionController");
    }
}
