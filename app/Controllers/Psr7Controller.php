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

use Psr\Http\Message\UploadedFileInterface;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Message\Server\Request;

/**
 * @Controller(prefix="/psr7")
 * @uses      Psr7Controller
 * @version   2017-11-05
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Psr7Controller
{

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function get(Request $request)
    {
        $param1 = $request->query('param1');
        $param2 = $request->query('param2', 'defaultValue');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function post(Request $request)
    {
        $param1 = $request->post('param1');
        $param2 = $request->post('param2');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function input(Request $request)
    {
        $param1 = $request->input('param1');
        $inputs = $request->input();
        return compact('param1', 'inputs');
    }

    /**
     * @RequestMapping()
     */

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function raw(Request $request)
    {
        $param1 = $request->raw();
        return compact('param1');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function cookies(Request $request)
    {
        $cookie1 = $request->cookie();
        return compact('cookie1');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function header(Request $request)
    {
        $header1 = $request->header();
        $host = $request->header('host');
        return compact('header1', 'host');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function json(Request $request)
    {
        $json = $request->json();
        $jsonParam = $request->json('jsonParam');
        return compact('json', 'jsonParam');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     *
     * @return array
     */
    public function files(Request $request)
    {
        $files = $request->file();
        foreach ($files as $file) {
            if ($file instanceof UploadedFileInterface) {
                try {
                    $file->moveTo('@runtime/uploadfiles/1.png');
                    $move = true;
                } catch (\Throwable $e) {
                    $move = false;
                }
            }
        }

        return compact('move');
    }

}