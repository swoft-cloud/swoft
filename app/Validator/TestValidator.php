<?php


namespace App\Validator;

use App\Annotation\Mapping\AlphaDash;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TestValidator
 * @package App\Validator
 * @since 2.0
 * @Validator(name="TestValidator")
 */
class TestValidator
{
    /**
     * @IsString()
     *
     * @var string
     */
    protected $name = 'defualtName';

    /**
     * @IsInt(message="类型必须传递且整数")
     *
     * @var int
     */
    protected $type;

    /**
     * @AlphaDash(message="密码只能是字母,数字,短横,下划线")
     * @var string
     */
    protected $password;

}
