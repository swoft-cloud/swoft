<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Log;
use Swoft\Bean\Collector;

/**
 *
 *
 * @uses      LogParser
 * @version   2017年10月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class LogParser extends AbstractParser
{

    /**
     * Listen注解解析
     *
     * @param string   $className
     * @param Log $objectAnnotation
     * @param string   $propertyName
     * @param string   $methodName
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if(isset(Collector::$processses[$className])){
            Collector::$processses[$className]['log']['flushInterval'] = $objectAnnotation->getFlushInterval();
        }
        if(isset(Collector::$crontab[$className])){
            Collector::$crontab[$className]['log']['flushInterval'] = $objectAnnotation->getFlushInterval();
        }
        return null;
    }
}