<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\EnumFloat;
use Swoft\Bean\Annotation\EnumInteger;
use Swoft\Bean\Annotation\EnumNumber;
use Swoft\Bean\Annotation\EnumString;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\Strings;

/**
 * validator
 *
 * @Controller("vld")
 * @uses      ValidatorController
 * @version   2017年12月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorController
{
    /**
     * @RequestMapping("index")
     *
     * @Strings(name="string", min=1, min=6, default="str")
     * @Floats(name="float", min=1.1, max=1.6, default="1.3")
     * @Integer(from="get/post/body", name="integer", min=-1, max=6, default="3")
     * @Number(name="number", min=3, max=9, default="5")
     * @EnumString(name="enumString", values={"a", "b"}, default="b")
     * @EnumFloat(name="enumFloat", values={1.1, 1.2}, default="1.1")
     * @EnumInteger(name="enumInteger", values={-1,3,-9}, default="9")
     * @EnumNumber(name="enumNumber", values={1,2,4,6}, default="8")
     */
    public function index()
    {

    }

    /**
     * @RequestMapping("path")
     */
    public function validatePath()
    {

    }

    /**
     * @RequestMapping("path")
     */
    public function validateGet()
    {

    }

    /**
     * @RequestMapping("path")
     */
    public function validatePost()
    {

    }

    /**
     *
     */
    public function hasNotAnnotation()
    {

    }
}