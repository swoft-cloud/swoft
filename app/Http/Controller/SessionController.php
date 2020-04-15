<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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

        return $response->withData([
            'times'  => $times,
            'sessId' => $sess->getSessionId()
        ]);
    }

    /**
     * @RequestMapping("all")
     *
     * @return array
     */
    public function all(): array
    {
        $sess = HttpSession::current();

        return $sess->toArray();
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
        $sess->set('testKey1', ['k' => 'v', 'v1', 3]);

        return $response->withData(['testKey', 'testKey1']);
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
        $ok   = $sess->delete('testKey');

        return ['delete' => $ok];
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

    /**
     * @RequestMapping()
     *
     * @return string
     */
    public function not(): string
    {
        return 'not-use';
    }

    // ------------ flash session usage

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function flash(): array
    {
        $sess = HttpSession::current();
        $sess->setFlash('flash1', 'test-value');

        return ['set.testKey' => 'test-value'];
    }
}
