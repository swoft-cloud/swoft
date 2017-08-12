<?php

namespace swoft\service;

use swoft\App;
use swoft\http\HttpClient;

/**
 * consul服务
 *
 * @uses      ConsulProvider
 * @version   2017年07月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsulProvider implements ServiceProvider
{
    /**
     * @var string consul服务地址
     */
    private $address = '127.0.0.1:80';

    /**
     * 获取服务可用的服务列表
     *
     * @param string $serviceName 服务名称
     *
     * @return array
     * <pre>
     * [
     *   '127.0.0.1:89',
     *   '127.0.0.1:88',
     *   ...
     * ]
     * <pre>
     */
    public function getServiceList(string $serviceName)
    {
        // consul获取健康的节点集合
        $url = "http://" . $this->address . "/v1/health/service/{$serviceName}?passing";
        $result = HttpClient::call($url, HttpClient::GET);
        $services = json_decode($result, true);

        // 数据格式化
        $nodes = [];
        foreach ($services as $service) {
            if (!isset($service['Service'])) {
                App::warning("consul[Service] 服务健康节点集合，数据格式不不正确，data=".$result);
                continue;
            }
            $serviceInfo = $service['Service'];
            if (!isset($serviceInfo['Address']) || !isset($serviceInfo['Port'])) {
                App::warning("consul[Address] Or consul[Port] 服务健康节点集合，数据格式不不正确，data=".$result);
                continue;
            }
            $address = $serviceInfo['Address'];
            $port = $serviceInfo['Port'];

            $uri = implode(":", [$address, $port]);
            $nodes[] = $uri;
        }

        return $nodes;
    }

    /**
     * 注册一个服务到consul
     *
     * @param string $serviceName   服务名称
     * @param string $host          HOST
     * @param  int   $port          PORT
     * @param array  $tags          tags
     * @param int    $interval      心跳时间，单位秒
     * @param int    $timeout       超时时间，单位秒
     */
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

    /**
     * CURL注册服务
     *
     * @param array  $service   服务信息集合
     * @param string $url       consulURI
     */
    private function putService(array $service, string $url)
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