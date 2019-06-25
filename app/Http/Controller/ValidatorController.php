<?php


namespace App\Http\Controller;


use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;

/**
 * Class ValidatorController
 * @package App\Http\Controller
 * @Controller()
 */
class ValidatorController
{
    /**
     * 验证 TestValidator 验证器中 所有定义的字段
     * @RequestMapping()
     * @Validate(validator="TestValidator")
     * @param Request $request
     * @return array
     */
    function validateAll(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 只验证 TestValidator 验证器中 type 字段
     * @RequestMapping()
     * @Validate(validator="TestValidator",fields={"type"})
     * @param Request $request
     * @return array
     */
    function validateType(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 只验证 TestValidator 验证器中 password 字段
     * @RequestMapping()
     * @Validate(validator="TestValidator",fields={"password"})
     * @param Request $request
     * @return array
     */
    function validatePassword(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 使用 userValidator 自定义验证器
     * @RequestMapping()
     * @Validate(validator="userValidator")
     * @param Request $request
     * @return array
     */
    function validateCustomer(Request $request): array
    {
        return $request->getParsedBody();

    }
}
