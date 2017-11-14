<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Ary;
use Swoft\Bean\Annotation\EnumFlt;
use Swoft\Bean\Annotation\EnumInteger;
use Swoft\Bean\Annotation\EnumNumber;
use Swoft\Bean\Annotation\EnumStr;
use Swoft\Bean\Annotation\Flt;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\RequestMethod;
use Swoft\Bean\Annotation\Str;
use Swoft\Web\Controller;
use Swoft\Web\Request;
use Swoft\Web\Response;

/**
 * restful和参数验证测试demo
 *
 * @uses      RestController
 * @version   2017年11月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RestController extends Controller
{
    /**
     *
     */
    public function actionList()
    {

    }

    /**
     * @RequestMapping(route="/{$name}", method={RequestMethod::POST})
     *
     * @Str(name="string", min=1, min=6, default="str")
     * @Flt(name="float", min=1.1, max=1.6, default="1.3")
     * @Integer(from="get/post/body", name="integer", min=-1, max=6, default="3")
     * @Number(name="number", min=3, max=9, default="5")
     * @EnumStr(name="enumString", values={"a", "b"}, default="b")
     * @EnumFlt(name="enumFloat", values={1.1, 1.2}, default="1.1")
     * @EnumInteger(name="enumInteger", values={-1,3,-9}, default="9")
     * @EnumNumber(name="enumNumber", values={1,2,4,6}, default="8")
     * @Ary(name="array", delimiter="," min=1, max=10, default="a,b")
     */
    public function actionCreate(Request $request, Response $response, string $name, \ArrayObject $params)
    {

    }
}