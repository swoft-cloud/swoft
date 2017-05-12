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
    protected $actionPrefix = "action";
    protected $defaultAction = "index";

    public function run(string $actionId, array $params = [])
    {
        if(empty($actionId)){
            $actionId = $this->defaultAction;
        }
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
        $methodName = $this->getMethodName($actionId);
        $method = new \ReflectionMethod($this, $methodName);
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

    public function getMethodName(string $actionId)
    {
        $methodName = $this->actionPrefix.ucfirst($actionId);
        if(method_exists($this, $methodName)){

        }
        return $methodName;
    }

    protected function beforeAction()
    {

    }

    protected function afterAction()
    {

    }
}