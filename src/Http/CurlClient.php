<?php

namespace Swoft\Http;

use Swoft\App;

/**
 *
 *
 * @uses      CurlClient
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CurlClient  extends AbstractHttpClient
{
    public static function call(string $url, string $method = self::GET, $data, int $timeout = 3, array $headers = [])
    {
        $profileKey = 'http.'.$url;
        $paramsBuild = self::getContentData($data);

        if ($method == self::GET && !empty($data)) {
            $url .= "&" . $paramsBuild;
        }

        App::profileStart($profileKey);

        //初始化CURL句柄
        $curl = curl_init();

        //设置请求的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // 不要http header 加快效率
        curl_setopt($curl, CURLOPT_HEADER, false);

        //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //设置连接等待时间和header
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

        switch ($method) {
            case self::GET :
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case self::POST:
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_NOBODY, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsBuild);
                break;
            case self::PUT :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsBuild);
                break;
            case self::DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsBuild);
                break;
        }

        $result = curl_exec($curl);
        $error = curl_errno($curl);
        if(!empty($error)){
            App::error("httpClient curl出错 url = $url error=".$error." params=".json_encode($data));
        }
        //        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        App::profileEnd($profileKey);
        return $result;
    }
}