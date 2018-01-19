<?php

namespace App\Controllers;

use Swoft\Exception\BadMethodCallException;
use Swoft\Exception\RuntimeException;
use Swoft\Exception\ValidatorException;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * the demo of exception
 *
 * @Controller("exception")
 * @uses      ExceptionController
 * @version   2018年01月17日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ExceptionController
{
    /**
     * @RequestMapping()
     * @throws \Exception
     */
    public function exceptioin()
    {
        throw new \Exception("this is exception");
    }

    /**
     * @RequestMapping()
     * @throws RuntimeException
     */
    public function runtimeException()
    {
        throw new RuntimeException("my exception");
    }

    /**
     * @RequestMapping()
     * @throws ValidatorException
     */
    public function defaultException()
    {
        throw new ValidatorException("validator exception! ");
    }

    /**
     * @RequestMapping()
     * @throws BadMethodCallException
     */
    public function viewException()
    {
        throw new BadMethodCallException("view exception! ");
    }
}