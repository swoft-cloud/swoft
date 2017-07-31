<?php

namespace swoft\service;

use swoft\http\HttpClient;

/**
 *
 *
 * @uses      ConsulProvider
 * @version   2017年07月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsulProvider implements ServiceProvider
{
    private $address = '127.0.0.1:80';

    public function getServiceList(string $serviceName)
    {
        $url = "http://" . $this->address . "/v1/health/service/{$serviceName}?passing";
        $resutl = HttpClient::call($url, HttpClient::GET);
        $services = json_decode($resutl, true);

        $nodes = [];
        foreach ($services as $service) {
            if (!isset($service['Service'])) {
                continue;
            }
            $serviceInfo = $service['Service'];
            if (!isset($serviceInfo['Address']) || !isset($serviceInfo['Port'])) {
                continue;
            }
            $address = $serviceInfo['Address'];
            $port = $serviceInfo['Port'];

            $uri = implode(":", [$address, $port]);
            $nodes[] = $uri;
        }

        var_dump($nodes);
        return $nodes;
    }

    public function registerService(string $serviceName, $host, $port, $tags = [], $interval = 10, $timeout = 1)
    {
        $url = "http://" . $this->address . "/v1/agent/service/register";
        $hostName = gethostname();
        $service = [
            'ID'                => $serviceName . "-" . $hostName,
            "Name"              => "user",
            'Tags'              => $tags,
            'Address'           => $host,
            'Port'              => $port,
            'EnableTagOverride' => false,
            'Check'             => [
                'DeregisterCriticalServiceAfter' => '90m',
                'TCP'                            => $host . ":" . $port,
                "Interval"                       => $interval . "s"
            ]
        ];
        $this->putService($service, $url);
    }

    private function putService($service, $url)
    {
        $contentJson = json_encode($service);
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置HTTP头信息
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contentJson);//设置提交的字符串
        curl_exec($ch);//执行预定义的CURL
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
        } else {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
    }
}