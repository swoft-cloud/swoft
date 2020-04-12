<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Common\MyBean;
use App\Model\Logic\RequestBean;
use App\Model\Logic\RequestBeanTwo;
use Swoft\Bean\BeanFactory;
use Swoft\Co;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class BeanController
 *
 * @since 2.0
 *
 * @Controller(prefix="bean")
 */
class BeanController
{
    /**
     * @RequestMapping("single")
     *
     * @return array
     */
    public function singleton(): array
    {
        $b = BeanFactory::getBean(MyBean::class);

        return [$b->myMethod()];
    }

    /**
     * @RequestMapping("req")
     *
     * @return array
     */
    public function request(): array
    {
        $id = (string)Co::tid();

        /** @var RequestBean $request */
        $request = BeanFactory::getRequestBean('requestBean', $id);

        $request->temp = ['rid' => $id];

        return $request->getData();
    }

    /**
     * @return array
     *
     * @RequestMapping("req2")
     */
    public function requestTwo(): array
    {
        $id = (string)Co::tid();

        /* @var RequestBeanTwo $request */
        $request = BeanFactory::getRequestBean(RequestBeanTwo::class, $id);
        return $request->getData();
    }
}
