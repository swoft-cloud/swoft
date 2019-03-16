<?php

namespace App\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class TestController
 *
 * @Controller("/test")
 * @since 2.0
 */
class TestController
{
    /**
     * Test
     *
     * @RequestMapping(route="index")
     *
     * @return string
     */
    public function test(): string
    {
        return 'swoft framework hello';
    }

    /**
     * @param Response $response
     * @param Request  $request
     *
     * @RequestMapping(route="p")
     *
     * @return string
     */
    public function params(Response $response, Request $request): string
    {
        return get_class($response) . '-' . get_class($request);
    }

    /**
     * @param int      $uid
     * @param Response $response
     * @param string   $name
     * @param int      $age
     * @param int      $count
     *
     * @RequestMapping(route="u/{uid}")
     *
     * @return string
     */
    public function user(int $uid, Response $response, string $name, int $age, int $count = 1): string
    {
        return 'uid=' . $uid . ' name=' . $name . ' age=' . $age . ' count=' . $count . ' response=' . get_class($response);
    }
}