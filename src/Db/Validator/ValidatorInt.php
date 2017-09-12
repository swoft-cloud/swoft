<?php

namespace Swoft\Db\Validator;

use Swoft\Di\Annotation\Bean;
use Swoft\Exception\ValidatorException;

/**
 *
 * @Bean("ValidatorInt")
 * @uses      ValidatorInt
 * @version   2017年09月12日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorInt implements IValidator
{
    /**
     * @param string $cloum
     * @param mixed  $value
     * @param array  ...$params
     *
     * @throws ValidatorException
     * @return bool
     */
    public function validate(string $cloum, $value, ...$params)
    {
        if(!is_int($value)){
            throw new ValidatorException("数据库字段值验证失败，不是int类型，column=".$cloum);
        }
        return true;
    }
}