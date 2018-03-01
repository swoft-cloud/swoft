<?php

namespace App\Controllers;

use Swoft\HttpClient\Client;
use Swoft\Http\Server\Bean\Annotation\Controller;

/**
 * @Controller(prefix="/httpClient")
 */
class HttpClientController
{
    /**
     * @return array
     * @throws \Swoft\HttpClient\Exception\RuntimeException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function request(): array
    {
        $client = new Client();
        $result = $client->get('https://www.swoft.org')->getResult();
        $result2 = $client->get('https://www.swoft.org')->getResponse()->getBody()->getContents();
        return compact('result', 'result2');
    }
}