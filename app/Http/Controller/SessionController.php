<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Session\HttpSession;

/**
 * Class SessionController
 *
 * @Controller()
 */
class SessionController
{
    /**
     * @RequestMapping("/session")
     * @param Response $response
     *
     * @return Response
     */
    public function session(Response $response): Response
    {
        $sess  = HttpSession::current();
        $times = $sess->get('times', 0);
        $times++;

        $sess->set('times', $times);

        return $response->withData(['times' => $times]);
    }

    /**
     * @RequestMapping()
     * @param Response $response
     *
     * @return Response
     */
    public function set(Response $response): Response
    {
        $sess = HttpSession::current();
        $sess->set('testKey', 'test-value');

        return $response->withData(['set.testKey' => 'test-value']);
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function get(): array
    {
        $sess = HttpSession::current();

        return ['get.testKey' => $sess->get('testKey')];
    }

    /**
     * @RequestMapping("del")
     *
     * @return array
     */
    public function del(): array
    {
        $sess = HttpSession::current();
        $sess->set('testKey', 'test-value');

        return ['set.testKey' => 'test-value'];
    }

    /**
     * @RequestMapping()
     * @param Response $response
     *
     * @return Response
     */
    public function close(Response $response): Response
    {
        $sess = HttpSession::current();

        return $response->withData(['destroy' => $sess->destroy()]);
    }
}
