<?php

namespace App\Controllers;

use Psr\Http\Message\UploadedFileInterface;
use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Web\Request;

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
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionGet(Request $request)
    {
        $param1 = $request->query('param1');
        $param2 = $request->query('param2', 'defaultValue');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionPost(Request $request)
    {
        $param1 = $request->post('param1');
        $param2 = $request->post('param2');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionInput(Request $request)
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
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionRaw(Request $request)
    {
        $param1 = $request->raw();
        return compact('param1');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionCookies(Request $request)
    {
        $cookie1 = $request->cookie();
        return compact('cookie1');
    }

    /**
     * @RequestMapping()
     *
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionHeader(Request $request)
    {
        $header1 = $request->header();
        $host = $request->header('host');
        return compact('header1', 'host');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionJson(Request $request)
    {
        $json = $request->json();
        $jsonParam = $request->json('jsonParam');
        return compact('json', 'jsonParam');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionFiles(Request $request)
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