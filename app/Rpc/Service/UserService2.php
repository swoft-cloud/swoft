<?php declare(strict_types=1);


namespace App\Rpc\Service;


use App\Rpc\Lib\UserInterface;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Class UserService2
 *
 * @since 2.0
 *
 * @Service(version="1.1")
 */
class UserService2 implements UserInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getUser(int $id): array
    {
        return [
            'v'    => '1.1',
            'id'   => $id,
            'name' => 'name'
        ];
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function isExist(int $id): bool
    {
        return $id == 1;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function getByName(string $name): int
    {
        return 18306 + 10000;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function getUsers(array $ids): array
    {
        $users = [];
        foreach ($ids as $id) {
            $users['id']   = $id;
            $users['v']    = '1.1';
            $users['name'] = 'name' . $id;
        }

        return $users;
    }
}