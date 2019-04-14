<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Context\Context;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{
    /**
     * @RequestMapping("/")
     * @throws \Throwable
     */
    public function index(): Response
    {
        return Context::mustGet()->getResponse()->withContent('Hello');
    }

    /**
     * @RequestMapping("/ex")
     * @throws \Throwable
     */
    public function ex(): void
    {
        throw new \RuntimeException('exception throw on ' . __METHOD__);
    }
}
