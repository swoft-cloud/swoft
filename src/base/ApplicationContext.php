<?php

namespace swoft\base;

use swoft\App;
use swoft\di\BeanFactory;

/**
 * 应用上下文
 *
 * @uses      ApplicationContext
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ApplicationContext
{
    /**
     * 运行过程中创建一个Bean
     *
     * Below are some examples:
     *
     * // 类名称创建
     * ApplicationContext::createBean('name', '\swoft\web\UrlManage');
     *
     * // 配置信息创建，切支持properties.php配置引用和bean引用
     * ApplicationContext::createBean(
     *  [
     *      'class' => '\swoft\web\UrlManage',
     *      'field' => 'value1',
     *      'field2' => 'value'2
     *  ]
     * );
     *
     * @param string       $beanName the name of bean
     * @param array|string $type     class definition
     * @param array        $params   constructor parameters
     *
     * @return mixed
     */
    public static function createBean($beanName, $type, $params = [])
    {
        if (!empty($params) && is_array($type)) {
            array_unshift($type, $params);
        }
        
        return BeanFactory::createBean($beanName, $type);
    }

    /**
     * 查询一个bean
     *
     * @param string $name bean名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return BeanFactory::getBean($name);
    }

    /**
     * bean是否存在
     *
     * @param string $name Bean名称
     *
     * @return bool
     */
    public static function containsBean($name)
    {
        return BeanFactory::hasBean($name);
    }

}