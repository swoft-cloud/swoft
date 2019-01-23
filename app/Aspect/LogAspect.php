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
     * @var
     */
    private $test;

    /**
     * @Before()
     */
    public function before()
    {
        $this->test .= ' before1 ';
    }

    /**
     * @After()
     */
    public function after()
    {
        $this->test .= ' after1 ';
    }

    /**
     * @AfterReturning()
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        return $result . ' afterReturn1 ';
    }

    /**
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->test .= ' around-before1 ';
        $result     = $proceedingJoinPoint->proceed();
        $this->test .= ' around-after1 ';
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