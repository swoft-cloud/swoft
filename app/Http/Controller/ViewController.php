<?php declare(strict_types=1);


namespace App\Http\Controller;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class ViewController
 *
 * @since 2.0
 *
 * @Controller(prefix="view")
 */
class ViewController
{
    /**
     * @RequestMapping("index")
     *
     * @param Response $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function index(Response $response)
    {
        $response = $response->withContent('<html><h1>Swoft framework</h1></html>');
        $response = $response->withContentType(ContentType::HTML);
        return $response;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function ary(): array
    {
        return ['ary'];
    }

    /**
     * @RequestMapping()
     *
     * @return string
     */
    public function str(): string
    {
        return 'string';
    }
}