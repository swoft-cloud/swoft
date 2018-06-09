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
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller(prefix="/psr7")
 */
class Psr7Controller
{

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function get(Request $request): array
    {
        $param1 = $request->query('param1');
        $param2 = $request->query('param2', 'defaultValue');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function post(Request $request): array
    {
        $param1 = $request->post('param1');
        $param2 = $request->post('param2');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function input(Request $request): array
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
     * @return array
     */
    public function raw(Request $request): array
    {
        $param1 = $request->raw();
        return compact('param1');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function cookies(Request $request): array
    {
        $cookie1 = $request->cookie();
        return compact('cookie1');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function header(Request $request): array
    {
        $header1 = $request->header();
        $host = $request->header('host');
        return compact('header1', 'host');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function json(Request $request): array
    {
        $json = $request->json();
        $jsonParam = $request->json('jsonParam');
        return compact('json', 'jsonParam');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function files(Request $request): array
    {
        $files = $request->file();
        foreach ($files as $file) {
            if ($file instanceof UploadedFileInterface) {
                try {
                    $file->moveTo('@runtime/uploadfiles/' . $file->getClientFilename());
                    $move = true;
                } catch (\Throwable $e) {
                    $move = false;
                }
            }
        }

        return compact('move');
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array|null|\Swoft\Http\Message\Upload\UploadedFile
     */
    public function multiFilesInOneKey(Request $request)
    {
        $files = $request->file('files');
        foreach ($files as $file) {
            if ($file instanceof UploadedFileInterface) {
                try {
                    $file->moveTo('@runtime/uploadfiles/' . $file->getClientFilename());
                    $move[$file->getClientFilename()] = true;
                } catch (\Throwable $e) {
                    $move[$file->getClientFilename()] = false;
                }
            }
        }

        return compact('move');
    }

}