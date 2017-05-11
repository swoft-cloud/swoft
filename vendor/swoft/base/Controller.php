<?php

namespace swoft\base;

/**
 *
 *
 * @uses      Controller
 * @version   2017年04月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller
{
    public function run(string $actionId, array $params = [])
    {
        $this->runAction($actionId, $params);
    }

    public function runAction(string $actionId, array $params = [])
    {
        $this->runActionWithFilters($actionId, $params);
    }

    public function runActionWithFilters(string $actionId, array $params = [])
    {
        $this->beforeAction();
        $this->runActionWithParams($actionId, $params);
        $this->afterAction();
    }

    public function runActionWithParams(string $actionId, array $params = [])
    {
        $bindParams = [];
        $method = new \ReflectionMethod($this, $actionId);
        $reflectionParams = $method->getParameters();
        foreach ($reflectionParams as $reflectionParam) {
            $paramType = $reflectionParam->getType();
            if($paramType == \swoft\web\Request::class){
                $bindParams[] = RequestContext::getRequest();
            }else{
                $bindParams[] = array_shift($params);
            }
        }
        $method->invokeArgs($this, $bindParams);
    }

    protected function beforeAction()
    {

    }

    protected function afterAction()
    {

    }
}