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
use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Number;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Http\Message\Server\Request;

/**
 * @Controller("validator")
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
     * @param string  $name
     * @param Request $request
     *
     * @return array
     */
    public function string(Request $request, string $name)
    {
        $getName  = $request->query('name');
        $postName = $request->post('name');

        return [$getName, $postName, $name];
    }

    /**
     * @RequestMapping("number/{id}")
     *
     * @Number(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7)
     * @Number(from=ValidatorFrom::POST, name="id", min=5, max=10, default=8)
     * @Number(from=ValidatorFrom::PATH, name="id", min=5, max=10)
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array
     */
    public function number(Request $request, int $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }

    /**
     * @RequestMapping("integer/{id}")
     *
     * @Integer(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7)
     * @Integer(from=ValidatorFrom::POST, name="id", min=5, max=10, default=8)
     * @Integer(from=ValidatorFrom::PATH, name="id", min=5, max=10)
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array
     */
    public function integer(Request $request, int $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }

    /**
     * @RequestMapping("float/{id}")
     *
     * @Floats(from=ValidatorFrom::GET, name="id", min=5.1, max=5.9, default=5.6)
     * @Floats(from=ValidatorFrom::POST, name="id", min=5.1, max=5.9, default=5.6)
     * @Floats(from=ValidatorFrom::PATH, name="id", min=5.1, max=5.9)
     *
     * @param Request $request
     * @param float   $id
     *
     * @return array
     */
    public function float(Request $request, float $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }


    /**
     * @RequestMapping("enum/{name}")
     *
     * @Enum(from=ValidatorFrom::GET, name="name", values={1,"a",3}, default=1)
     * @Enum(from=ValidatorFrom::POST, name="name", values={1,"a",3}, default=1)
     * @Enum(from=ValidatorFrom::PATH, name="name", values={1,"a",3}, default=1)
     *
     * @param string  $name
     * @param Request $request
     *
     * @return array
     */
    public function estring(Request $request, $name)
    {
        $getName  = $request->query('name');
        $postName = $request->post('name');

        return [$getName, $postName, $name];
    }

}