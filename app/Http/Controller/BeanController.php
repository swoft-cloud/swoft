<?php declare(strict_types=1);

namespace App\Http\Controller;

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
     * @RequestMapping()
     *
     * @return array
     */
    public function request(): array
    {
        $id = (string)Co::tid();

        /** @var RequestBean $request */
        $request = BeanFactory::getRequestBean('requestBean', $id);
        return $request->getData();
    }

    /**
     * @return array
     *
     * @RequestMapping()
     */
    public function requestClass(): array
    {
        $id = (string)Co::tid();

        /* @var RequestBeanTwo $request */
        $request = BeanFactory::getRequestBean(RequestBeanTwo::class, $id);
        return $request->getData();
    }
}
