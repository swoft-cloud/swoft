<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\Count;
use App\Model\Entity\User;
use Exception;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Throwable;

/**
 * Class SelectDbController
 *
 * @since 2.0
 *
 * @Controller(prefix="selectDb")
 */
class SelectDbController
{
    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function modelNotExistDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            User::db("test_error");
            User::db("test_error")->find($id);
        });

        User::db("test_error");
        User::db("test_error")->find($id);

        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function modelDb(): array
    {
        $id   = $this->getId();
        $user = User::find($id)->toArray();

        $this->insertId2();
        $result = User::db('test2')->count('id');

        sgo(function () {
            $id = $this->getId();
            User::find($id)->toArray();

            $this->insertId2();
            User::db('test2')->count('id');
        });

        return [$user, $result];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function queryNotExistDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        DB::table('user')->db('test_error');
        DB::table('user')->db('test_error')->where('id', '=', $id)->get();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            DB::table('user')->db('test_error');
            DB::table('user')->db('test_error')->where('id', '=', $id)->get();
        });

        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function queryDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        $this->insertId2();

        $count = DB::table('user')->db('test2')->count();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            $this->insertId2();

            DB::table('user')->db('test2')->count();
        });

        return [$user, $count];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function dbNotExistDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            DB::db('test_error');
        });

        DB::db('test_error');

        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function dbDb(): array
    {
        $id   = $this->getId();
        $user = User::find($id)->toArray();

        $result = DB::db('test2')->selectOne('select * from user limit 1');

        sgo(function () {
            $id = $this->getId();
            User::find($id)->toArray();

            DB::db('test2')->selectOne('select * from user limit 1');
        });

        return [$user, $result];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Exception
     */
    public function select(): array
    {
        $count = new Count();
        $count->setUserId(mt_rand(1, 100));
        $count->setAttributes('attr');
        $count->setCreateTime(time());

        $result = $count->save();

        return [$result, $count->getId()];
    }

    public function insertId2(): bool
    {
        $result = User::db('test2')->insert([
            [
                'name'      => uniqid(),
                'password'  => md5(uniqid()),
                'age'       => mt_rand(1, 100),
                'user_desc' => 'u desc',
                'foo'       => 'bar'
            ]
        ]);

        return $result;
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