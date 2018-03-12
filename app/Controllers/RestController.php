<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Server\Request;

/**
 * RESTful和参数验证测试demo
 *
 * @Controller(prefix="/user")
 */
class RestController
{
    /**
     * 查询列表接口
     * 地址:/user/
     *
     * @RequestMapping(route="/user", method={RequestMethod::GET})
     */
    public function list()
    {
        return ['list'];
    }


    /**
     * 创建一个用户
     * 地址:/user
     *
     * @RequestMapping(route="/user", method={RequestMethod::POST,RequestMethod::PUT})
     *
     * @param Request $request
     *
     * @return array
     */
    public function create(Request $request)
    {
        $name = $request->input('name');

        $bodyParams = $request->getBodyParams();
        $bodyParams = empty($bodyParams) ? ['create', $name] : $bodyParams;

        return $bodyParams;
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
    public function getUser(int $uid)
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
    public function getBookFromUser(int $userId, string $bookId)
    {
        return ['bookFromUser', $userId, $bookId];
    }

    /**
     * 删除一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::DELETE})
     *
     * @param int $uid
     *
     * @return array
     */
    public function deleteUser(int $uid)
    {
        return ['delete', $uid];
    }

    /**
     * 更新一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::PUT, RequestMethod::PATCH})
     *
     * @param int $uid
     * @param Request $request
     * @return array
     */
    public function updateUser(Request $request, int $uid)
    {
        $body = $request->getBodyParams();
        $body['update'] = 'update';
        $body['uid'] = $uid;

        return $body;
    }
}