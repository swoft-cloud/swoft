<?php declare(strict_types=1);


namespace App\Rpc\Service;


use App\Rpc\Lib\UserInterface;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Class UserService
 *
 * @since 2.0
 *
 * @Service()
 */
class UserService implements UserInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getUser(int $id): array
    {
        return [
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
        return 18306;
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
            $user['id']   = $id;
            $user['name'] = 'name' . $id;
            $users[]      = $user;
        }

        return $users;
    }
}