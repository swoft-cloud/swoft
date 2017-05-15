<?php

namespace swoft\web;

use swoft\helpers\ArrayHelper;
use swoft\base\ApplicationContext;
use Swoole\Http\Response;

/**
 *
 *
 * @uses      UrlManager
 * @version   2017年04月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class UrlManager
{
    private $rules = [];

    public $suffix;

    public function init()
    {
        if(!empty($this->rules)){
            $this->rules = $this->buildRules($this->rules);
        }
    }

    protected function buildRules($rules)
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = ['route' => $rule];
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    $key = $matches[4];
                }
                $rule['pattern'] = $key;
            }
            if (is_array($rule)) {
                $beanName = md5($key);
                $type = [
                    'class' => 'swoft\web\UrlRule'
                ];
                $type = ArrayHelper::merge($type, $rule);
                $rule = ApplicationContext::createBean($beanName, $type);
            }
            if (!$rule instanceof UrlRuleInterface) {

                //                 throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }
        return $compiledRules;
    }

    public function parseRequest(Request $request){
        /* @var $rule UrlRule */
        foreach ($this->rules as $rule) {
            if (($result = $rule->parseRequest($this, $request)) !== false) {
                return $result;
            }
        }

        $pathInfo = $request->getPathInfo();
        if (substr($pathInfo, 0, 1) === '/') {
            $pathInfo = substr($pathInfo, 1);
        }

        if (strlen($pathInfo) > 1 && substr_compare($pathInfo, '//', -2, 2) === 0) {
            return false;
        }

        $suffix = (string) $this->suffix;
        if ($suffix !== '' && $pathInfo !== '') {
            $n = strlen($this->suffix);
            if (substr_compare($pathInfo, $this->suffix, -$n, $n) === 0) {
                $pathInfo = substr($pathInfo, 0, -$n);
                if ($pathInfo === '') {
                    // suffix alone is not allowed
                    return false;
                }
            } else {
                // suffix doesn't match
                return false;
            }
        }

        return [$pathInfo, []];
    }
}