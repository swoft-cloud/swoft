<?php

namespace App\Controllers;

use Swoft\Http\Client;
use Swoft\Http\Server\Bean\Annotation\Controller;

/**
 * @Controller(prefix="/http")
 */
class HttpController
{
    /**
     * @return array
     */
    public function get()
    {
        $client = new Client();
        $response = $client->get('http://www.swoft.org')->getResponse()->getBody()->getContents();
        $response2 = $client->get('http://127.0.0.1/redis/testCache')->getResponse()->getBody()->getContents();
        $response3 = $client->get('http://127.0.0.1/redis/testCache')->getResult()->getBody()->getContents();
        return [$response, $response2, $response3];
    }
}