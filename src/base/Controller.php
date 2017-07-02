<?php

namespace swoft\base;

/**
 *
 *
 * @uses      Controller
 * @version   2017年04月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller
{
    protected $actionPrefix = "action";
    protected $defaultAction = "index";

    public function run(string $actionId, array $params = []): \swoft\web\Response
    {
        if(empty($actionId)){
            $actionId = $this->defaultAction;
        }
        $response = $this->runAction($actionId, $params);
        if(!($response instanceof \swoft\web\Response)){
            $response = RequestContext::getResponse();
        }
        return $response;
    }

    public function runAction(string $actionId, array $params = [])
    {
        return $this->runActionWithFilters($actionId, $params);
    }

    public function runActionWithFilters(string $actionId, array $params = [])
    {
        $this->beforeAction();
        $response = $this->runActionWithParams($actionId, $params);
        $this->afterAction();

        return $response;
    }

    public function runActionWithParams(string $actionId, array $params = [])
    {
        $bindParams = [];
        $methodName = $this->getMethodName($actionId);
//        $method = new \ReflectionMethod($this, $methodName);
//        $reflectionParams = $method->getParameters();
//        foreach ($reflectionParams as $reflectionParam) {
//            $paramType = $reflectionParam->getType();
//            if($paramType == \swoft\web\Request::class){
//                $bindParams[] = RequestContext::getRequest();
//            }elseif($paramType == \swoft\web\Response::class){
//                $bindParams[] = RequestContext::getResponse();
//            }else{
//                $bindParams[] = array_shift($params);
//            }
//        }

        /* @var \swoft\web\Response|null $response*/
//        $response = $method->invokeArgs($this, $bindParams);

        $response = $this->$methodName();

        return $response;
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