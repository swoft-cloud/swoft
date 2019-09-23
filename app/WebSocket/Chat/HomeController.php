<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\WebSocket\Chat;

use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class HomeController
 *
 * @WsController()
 */
class HomeController
{
    /**
     * Message command is: 'home.index'
     *
     * @return void
     * @MessageMapping()
     */
    public function index(): void
    {
        Session::mustGet()->push('hi, this is home.index');
    }

    /**
     * Message command is: 'home.echo'
     *
     * @param string $data
     * @MessageMapping()
     */
    public function echo(string $data): void
    {
        Session::mustGet()->push('(home.echo)Recv: ' . $data);
    }

    /**
     * Message command is: 'home.ar'
     *
     * @param string $data
     * @MessageMapping("ar")
     *
     * @return string
     */
    public function autoReply(string $data): string
    {
        return '(home.ar)Recv: ' . $data;
    }
}
