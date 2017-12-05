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
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Web\Request;

/**
 * validator
 *
 * @Controller("validator")
 * @uses      ValidatorController
 * @version   2017年12月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorController
{
    /**
     * @RequestMapping("string/{name}")
     *
     * @Strings(from=ValidatorFrom::GET, name="name", min=3, max=10, default="boy")
     * @Strings(from=ValidatorFrom::POST, name="name", min=3, max=10, default="girl")
     * @Strings(from=ValidatorFrom::PATH, name="name", min=3, max=10)
     *
     * @param string $name
     * @param Request $request
     *
     * @return array
     */
    public function string(Request $request, string $name)
    {
        $getName = $request->query('name');
        $postName = $request->post('name');
        return [$getName, $postName, $name];
    }
}