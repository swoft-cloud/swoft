<?php

namespace swoft\base;

use swoft\helpers\ArrayHelper;

/**
 *
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Application
{
    protected $id;
    protected $name;
    protected $beans;
    protected $params;
    protected $basePath;
    protected $runtimePath;
    protected $settingPath;

    public function init()
    {
        $beans = ArrayHelper::merge($this->coreBeans(), $this->beans);
        foreach ($beans as $beanName => $definition){
            ApplicationContext::createBean($beanName, $definition);
        }
    }
    public function run()
    {
        $this->parseCommand();
    }

    public function coreBeans()
    {
        return [
            'urlManager' => ['class' => 'swoft\web\urlManager']
        ];
    }

    abstract function parseCommand();
}