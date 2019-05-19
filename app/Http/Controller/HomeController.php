<?php declare(strict_types=1);

namespace App\Http\Controller;

use const E_USER_ERROR;
use ReflectionException;
use RuntimeException;
use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\View\Renderer;
use Throwable;
use function trigger_error;

/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{
    /**
     * @RequestMapping("/")
     * @throws Throwable
     */
    public function index(): Response
    {
        /** @var Renderer $renderer */
        $renderer = Swoft::getBean('view');
        $content  = $renderer->render('home/index');

        return Context::mustGet()
            ->getResponse()
            ->withContentType(ContentType::HTML)
            ->withContent($content);
    }

    /**
     * @RequestMapping("/hello[/{name}]")
     * @param string $name
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function hello(string $name): Response
    {
        return Context::mustGet()
            ->getResponse()
            ->withContent('Hello' . ($name === '' ? '' : ", {$name}"));
    }

    /**
     * @RequestMapping("/ex")
     * @throws Throwable
     */
    public function ex(): void
    {
        throw new RuntimeException('exception throw on ' . __METHOD__);
    }

    /**
     * @RequestMapping("/er")
     * @throws Throwable
     */
    public function er(): void
    {
        trigger_error('user error', E_USER_ERROR);
    }
}
