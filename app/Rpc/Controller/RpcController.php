<?php declare(strict_types=1);


namespace App\Rpc\Controller;


use App\Rpc\Lib\UserInterface;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class RpcController
 *
 * @since 2.0
 *
 * @Controller(prefix="rpc")
 */
class RpcController
{
    /**
     * @Reference(pool="user.pool")
     *
     * @var UserInterface
     */
    private $userService;

    /**
     * @RequestMapping("user")
     *
     * @return array
     */
    public function user(): array
    {
        return $this->userService->getUsers([1, 2]);
    }
}