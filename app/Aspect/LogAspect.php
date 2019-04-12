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
 * @Aspect(1)
 * @PointBean(
 *     include={"testLog"}
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
        echo 'apsect1 before' . PHP_EOL;
    }

    /**
     * @After()
     */
    public function after()
    {
        echo 'apsect1 after' . PHP_EOL;
    }

    /**
     * @AfterReturning()
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        echo 'apsect1 afterReturn ' . PHP_EOL;

        return 'new value afterReturn ';
    }

    /**
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        echo 'apsect1 around before ' . PHP_EOL;
        $result = $proceedingJoinPoint->proceed();
        echo 'apsect1 around after ' . PHP_EOL;
        return $result;
    }

    /**
     * @AfterThrowing()
     */
    public function afterThrowing()
    {
        echo "apsect1 afterThrowing !\n";
    }
}