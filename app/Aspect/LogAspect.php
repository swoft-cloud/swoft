<?php declare(strict_types=1);


namespace App\Aspect;

use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;

/**
 * Class LogAspect
 *
 * @Aspect()
 * @PointBean(
 *     include={"deloc"}
 * )
 * @since 2.0
 */
class LogAspect
{
    /**
     * @Before()
     */
    public function before()
    {
        echo ' before1 ' . PHP_EOL;
    }

    /**
     * @After()
     */
    public function after()
    {
        echo ' after ' . PHP_EOL;
    }

    /**
     * @AfterReturning()
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        echo ' afterReturn ' . PHP_EOL;

        return $result . ' afterReturn1';
    }

    /**
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        echo ' around-before1 ' . PHP_EOL;
        $result = $proceedingJoinPoint->proceed();
        echo ' around-after1 ' . PHP_EOL;
        return $result . $this->test;
    }

    /**
     * @AfterThrowing()
     */
    public function afterThrowing()
    {
        echo "aop=1 afterThrowing !\n";
    }
}