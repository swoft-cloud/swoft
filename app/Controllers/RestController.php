<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Ary;
use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\EnumFloat;
use Swoft\Bean\Annotation\EnumInteger;
use Swoft\Bean\Annotation\EnumNumber;
use Swoft\Bean\Annotation\EnumString;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\RequestMethod;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Collector;
use Swoft\Web\Request;

/**
 * restful和参数验证测试demo
 *
 * @Controller(prefix="/users")
 *
 * @uses      RestController
 * @version   2017年11月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RestController
{
    /**
     * 查询列表接口
     * 地址:/users/
     *
     * @RequestMapping(route="/users", method={RequestMethod::GET})
     */
    public function actionList()
    {
        return ['list'];
    }


    /**
     * 创建一个用户
     * 地址:/users
     *
     * @RequestMapping(route="/users", method={RequestMethod::POST,RequestMethod::PUT})
     *
     * @Strings(name="string", min=1, min=6, default="str")
     * @Floats(name="float", min=1.1, max=1.6, default="1.3")
     * @Integer(from="get/post/body", name="integer", min=-1, max=6, default="3")
     * @Number(name="number", min=3, max=9, default="5")
     * @EnumString(name="enumString", values={"a", "b"}, default="b")
     * @EnumFloat(name="enumFloat", values={1.1, 1.2}, default="1.1")
     * @EnumInteger(name="enumInteger", values={-1,3,-9}, default="9")
     * @EnumNumber(name="enumNumber", values={1,2,4,6}, default="8")
     *
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionCreate(Request $request)
    {
        $name = $request->input('name');
        return ['create', $name];
    }

    /**
     * 查询一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::GET})
     *
     * @param int $uid
     *
     * @return array
     */
    public function actionGetUser(int $uid)
    {
        return ['getUser', $uid];
    }

    /**
     * 查询用户的书籍信息
     * 地址:/user/6/book/8
     *
     * @RequestMapping(route="{userId}/book/{bookId}", method={RequestMethod::GET})
     *
     * @param int    $userId
     * @param string $bookId
     *
     * @return array
     */
    public function actionGetBookFromUser(int $userId, string $bookId)
    {
        return ['bookFromUser', $userId, $bookId];
    }

    /**
     * 删除一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="uid", method={RequestMethod::DELETE})
     *
     * @param int $uid
     *
     * @return array
     */
    public function actionDeleteUser(int $uid)
    {
        return ['delete', $uid];
    }

    /**
     * 更新一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::UPDATE})
     *
     * @param int $uid
     *
     * @return array
     */
    public function actionUpdateUser(int $uid)
    {
        return ['update', $uid];
    }
}