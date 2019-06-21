<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\User;
use Exception;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Throwable;

/**
 * Class DbModelController
 *
 * @since 2.0
 *
 * @Controller(prefix="dbModel")
 */
class DbModelController
{
    /**
     * @RequestMapping(route="find")
     *
     * @param Response $response
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function find(Response $response): Response
    {
        $id   = $this->getId();
        $user = User::find($id);

        return $response->withData($user);
    }

    /**
     * @RequestMapping(route="save")
     *
     * @return array
     *
     * @throws Exception
     */
    public function save(): array
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        $user->save();

        return $user->toArray();
    }

    /**
     * @RequestMapping(route="update")
     *
     * @return array
     *
     * @throws Throwable
     */
    public function update(): array
    {
        $id = $this->getId();

        User::updateOrInsert(['id'=>$id], ['name'=> 'swoft']);

        $user = User::find($id);

        return $user->toArray();
    }

    /**
     * @RequestMapping(route="delete")
     *
     * @return array
     *
     * @throws Throwable
     */
    public function delete(): array
    {
        $id = $this->getId();
        $result = User::find($id)->delete();

        return [$result];
    }

    /**
     * @return int
     * @throws Throwable
     */
    public function getId(): int
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        $user->save();

        return $user->getId();
    }
}