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

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;


/**
 * @Controller(prefix="/session")
 */
class SessionController
{

    /**
     * @RequestMapping()
     * @return array
     */
    public function dump(): array
    {
        return session()->all();
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function set(Request $request): array
    {
        $key = $request->input('key');
        $value = $request->input('value');
        session()->put([$key => $value]);
        return session()->all();
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Http\Message\Server\Request $request
     * @return array
     */
    public function remove(Request $request): array
    {
        $key = $request->input('key');
        session()->remove($key);
        return session()->all();
    }

    /**
     * @RequestMapping()
     */
    public function flush()
    {
        session()->flush();
        return session()->all();
    }

    /**
     * @RequestMapping()
     */
    public function regenerateId()
    {
        return session()->migrate(true);
    }
}