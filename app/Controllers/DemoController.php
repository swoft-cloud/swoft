<?php

namespace App\Controllers;

use App\Models\Entity\User;
use App\Models\Logic\IndexLogic;
use Swoft\App;
use Swoft\Db\QueryBuilder;
use Swoft\Db\EntityManager;
use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\RequestMapping;
use Swoft\Di\Annotation\RequestMethod;
use Swoft\Web\Controller;

/**
 * 控制器demo
 *
 * @AutoController(prefix="/demo2")
 *
 * @uses      DemoController
 * @version   2017年08月22日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DemoController extends Controller
{
    /**
     * 注入逻辑层
     *
     * @Inject()
     * @var IndexLogic
     */
    private $logic;

    /**
     * 定义一个route,支持get和post方式，处理uri=/demo2/index
     *
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function actionIndex()
    {
        // 获取所有GET参数
        $get = $this->get();
        // 获取name参数默认值defaultName
        $name = $this->get('name', 'defaultName');
        // 获取所有POST参数
        $post = $this->post();
        // 获取name参数默认值defaultName
        $name = $this->post('name', 'defaultName');
        // 获取所有参，包括GET或POST
        $request = $this->request();
        // 获取name参数默认值defaultName
        $name = $this->request('name', 'defaultName');
        //json方式显示数据


        $this->outputJson("suc");
    }

    /**
     * 定义一个route,支持get,以"/"开头的定义，直接是根路径，处理uri=/index2
     *
     * @RequestMapping(route="/index2", method=RequestMethod::GET)
     */
    public function actionIndex2()
    {
        $this->outputJson("demo266666");
    }

    /**
     * 没有使用注解，自动解析注入，默认支持get和post
     */
    public function actionIndex3()
    {
        $this->outputJson("suc3222");
    }

    public function actionQueryBuilder()
    {
        $em = EntityManager::create();
        $query = $em->createQuery()
                ->select("*")
                ->from("user")
                ->where('sex', ":sex")
                ->setParameter('sex', 1)
                ->orderBy("id", QueryBuilder::ORDER_BY_DESC);
        $users = $query->getResult();

        $sql = $query->getSql();

        $em->close();

        $this->outputJson([$sql, $users]);

    }

    public function actionAdd()
    {
        $user = new User();
        $user->setName("boy");
        $user->setAge(mt_rand(1, 100));
        $user->setDesc("this is add user");
        $user->setSex(1);
//        $result = $user->save();
        $result = $user->deferSave();

//        $result = User::query()
//            ->select('id')
//            ->select('name')->where('sex', '1')
//            ->orderBy("id", QueryBuilder::ORDER_BY_ASC)
//            ->limit(6)
//            ->getResult();

//        $result = User::query()
//            ->select('id')
//            ->select('name')->where('sex', '1')
//            ->orderBy("id", QueryBuilder::ORDER_BY_ASC)
//            ->limit(6)
//            ->getDefer();
//
//        $result = $result->getResult();

//        $em = EntityManager::create();
//        $result = $em->save($user);
//        $em->close();

        $this->outputJson($result->getResult());
    }
    public function actionQueryArray(){
        $em = EntityManager::create();
        $query = $em->createQuery('select * from user where name=:name and sex=:sex');
        $query->setParameter("name", "stelin");
        $query->setParameter("sex", 1);
        $users = $query->getResult();
        $sql = $query->getSql();
        $em->close();
        $this->outputJson([$sql, $users]);
    }

    public function actionQueryEntity(){
        $em = EntityManager::create();
        $query = $em->createQuery('select * from user where name=:name and sex=:sex');
        $query->setParameter("name", "stelin");
        $query->setParameter("sex", 1);
        $result = $query->getResult(User::class);
        $sql = $query->getSql();

        $em->close();

        $users = [];
        /* @var User $userEntity*/
        foreach ($result as $userEntity){
            $user['id'] = $userEntity->getId();
            $user['name'] = $userEntity->getName();
            $user['age'] = $userEntity->getAge();
            $user['sex'] = $userEntity->getSex();
            $user['desc'] = $userEntity->getDesc();
            $users[] = $user;
        }
        $this->outputJson([$sql, $users]);
    }

    /**
     * 国际化测试
     */
    public function actionI18n()
    {
        $data[] = App::t("title", [], 'zh');
        $data[] = App::t("title", [], 'en');
        $data[] = App::t("msg.body", ["stelin", 999], 'en');
        $data[] = App::t("msg.body", ["stelin", 666], 'en');
        $this->outputJson($data);
    }
}