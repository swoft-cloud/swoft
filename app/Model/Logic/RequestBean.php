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

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RequestBean
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="requestBean")
 */
class RequestBean
{
    /**
     * @var array
     */
    public $temp = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        return ['requestBean'];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getName(string $type): string
    {
        return 'name';
    }
}
