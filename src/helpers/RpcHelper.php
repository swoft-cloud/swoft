<?php
namespace swoft\helpers;

class RpcHelper
{
    public static function rpcPack($uri, $params)
    {
        $data = [
            'uri' => $uri,
            'params' => $params
        ];

//        $data = \swoole_serialize::pack($data);
        $data = json_encode($data);
        return $data;
    }

    public static function rpcUnpack($data)
    {
        $data = \swoole_serialize::unpack($data);
        return $data;
    }
}