<?php

namespace App\Controllers;

use Swoft\Exception\BadMethodCallException;
use Swoft\Exception\RuntimeException;
use Swoft\Exception\ValidatorException;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller("exception")
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