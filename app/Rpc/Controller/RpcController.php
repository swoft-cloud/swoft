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
     * @Reference(pool="user.pool", version="1.1")
     *
     * @var UserInterface
     */
    private $userService2;

    /**
     * @RequestMapping("user")
     *
     * @return array
     */
    public function user(): array
    {
        $users = $this->userService->getUsers([12, 16]);
        $user  = $this->userService->getUser(12);
        $user2 = $this->userService->getByName('name');
        $bool  = $this->userService->isExist(12);

        $data = [
            $users,
            $user,
            $user2,
            $bool,
        ];
        return $data;
    }

    /**
     * @RequestMapping("user2")
     *
     * @return array
     */
    public function user2(): array
    {
        $users = $this->userService2->getUsers([12, 16]);
        $user  = $this->userService2->getUser(12);
        $user2 = $this->userService2->getByName('name');
        $bool  = $this->userService2->isExist(12);

        $data = [
            $users,
            $user,
            $user2,
            $bool,
        ];
        return $data;
    }
}