<?php declare(strict_types=1);


namespace App\Rpc\Lib;


use Swoft\Rpc\Client\Concern\ServiceTrait;

interface UserInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getUser(int $id): array;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function isExist(int $id): bool;

    /**
     * @param string $name
     *
     * @return int
     */
    public function getByName(string $name): int;

    /**
     * @param array $ids
     *
     * @return array
     */
    public function getUsers(array $ids): array;
}