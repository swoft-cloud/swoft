<?php

namespace Swoft\Service;

use Swoft\Base\RequestContext;
use Swoft\Di\Annotation\Bean;

/**
 * json格式解压包
 *
 * @Bean("packer")
 * @uses      JsonPacker
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class JsonPacker implements IPack
{

    public function pack($data)
    {
        return json_encode($data);
    }

    public function unpack($data)
    {
        return json_decode($data, true);
    }

    public function formatData($func, $params)
    {
        $contextData = RequestContext::getContextData();
        $logid = $contextData['logid']?? "";
        $spanid = $contextData['spanid']?? 0;

         $data = [
            'func' => $func,
            'params' => $params,
            'logid' => $logid,
            'spanid' => $spanid
         ];

         return $data;
    }

    public function checkData($data)
    {
        if(!isset($data['status']) || !isset($data['Data']) || !isset($data['msg'])){

        }

        $status = $data['status'];
        if($status != 200){

        }

        return $data['Data'];

    }
}