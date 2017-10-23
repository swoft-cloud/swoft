<?php

namespace Swoft\Service;

use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\Bean;

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

    /**
     * 数据打包
     *
     * @param mixed $data
     *
     * @return string
     */
    public function pack($data)
    {
        return json_encode($data);
    }

    /**
     * 数据解包
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function unpack($data)
    {
        return json_decode($data, true);
    }

    /**
     * 数据格式化
     *
     * @param string $func   函数
     * @param array  $params 参数
     *
     * @return array
     */
    public function formatData(string $func, array $params)
    {
        $contextData = RequestContext::getContextData();
        $logid = $contextData['logid']?? "";
        $spanid = $contextData['spanid']?? 0;

        // 传递数据信息
        $data = [
            'func'   => $func,
            'params' => $params,
            'logid'  => $logid,
            'spanid' => $spanid
        ];

        return $data;
    }

    /**
     * 参数验证
     *
     * @param array $data 参数
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function checkData(array $data)
    {
        // 格式不正确
        if (!isset($data['status']) || !isset($data['data']) || !isset($data['msg'])) {
            throw new \InvalidArgumentException("rpc返回参数格式不正确，data=".json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        // 返回状态验证
        $status = $data['status'];
        if ($status != 200) {
            throw new \InvalidArgumentException("rpc返回数据状态不正确，data=".json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        return $data['data'];
    }
}
