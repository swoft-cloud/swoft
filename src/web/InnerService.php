<?php

namespace swoft\web;

/**
 *
 *
 * @uses      InnerService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InnerService
{
    public function run(string $method, $params)
    {
        $this->beforeService();
        try {
            $data = $this->$method(...$params);
            $data = $this->formatData($data);
        } catch (\Exception $e) {
            $status = $e->getCode();
            $msg = $e->getMessage();
            $data = $this->formatData("", $status, $msg);
        }

        $this->beforeService();

        return $data;
    }

    public function formatData($data, $status = 200, $message = "")
    {
        return [
            'data' => $data,
            'status' => $status,
            'msg' => $message,
            'time' => microtime(true)
        ];
    }

    public function beforeService()
    {

    }

    public function afterService()
    {

    }
}