<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RequestBeanTwo
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST)
 */
class RequestBeanTwo
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return ['data'];
    }
}