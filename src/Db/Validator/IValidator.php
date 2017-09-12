<?php

namespace Swoft\Db\Validator;

use Swoft\Exception\ValidatorException;

/**
 *
 *
 * @uses      IValidator
 * @version   2017年09月12日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IValidator
{
    /**
     * @param string $cloum
     * @param mixed  $value
     * @param array  ...$params
     * @throws ValidatorException
     *
     * @return bool
     */
    public function validate(string $cloum, $value, ...$params);
}