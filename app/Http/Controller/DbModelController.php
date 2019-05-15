<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\User;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

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
     * @return array
     *
     * @throws \Exception
     */
    public function find(): array
    {
        $user = User::find(22);

        return $user->toArray();
    }
}