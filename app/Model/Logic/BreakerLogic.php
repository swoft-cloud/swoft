<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Model\Logic;

use Exception;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Breaker\Annotation\Mapping\Breaker;

/**
 * Class BreakerLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class BreakerLogic
{
    /**
     * @Breaker(fallback="funcFallback")
     *
     * @return string
     * @throws Exception
     */
    public function func(): string
    {
        // Do something

        throw new Exception('Breaker exception');
    }

    /**
     * @Breaker()
     *
     * @return string
     * @throws Exception
     */
    public function func2(): string
    {
        // Do something

        return 'func2';
    }

    /**
     * @return string
     */
    public function funcFallback(): string
    {
        return 'funcFallback';
    }

    /**
     * @Breaker(fallback="loopFallback")
     *
     * @return string
     * @throws Exception
     */
    public function loop(): string
    {
        // Do something

        throw new Exception('Breaker exception');
    }

    /**
     * @Breaker()
     *
     * @return string
     * @throws Exception
     */
    public function unFallback(): string
    {
        // Do something

        throw new Exception('Breaker exception');
    }

    /**
     * @Breaker(fallback="loopFallback2")
     *
     * @return string
     * @throws Exception
     */
    public function loopFallback(): string
    {
        // Do something

        throw new Exception('Breaker exception');
    }

    /**
     * @Breaker(fallback="loopFallback3")
     *
     * @return string
     * @throws Exception
     */
    public function loopFallback2(): string
    {
        // Do something

        throw new Exception('Breaker exception');
    }

    /**
     * @return string
     */
    public function loopFallback3(): string
    {
        return 'loopFallback3';
    }
}
