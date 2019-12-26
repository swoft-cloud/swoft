<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Validator\Annotation\Mapping\Validate;

/**
 * Class ValidatorController
 *
 * @Controller()
 */
class ValidatorController
{
    /**
     * Verify all defined fields in the TestValidator validator
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function validateAll(Request $request): array
    {
        $method = $request->getMethod();
        if ($method == RequestMethod::GET) {
            return $request->getParsedQuery();
        }
        return $request->getParsedBody();
    }

    /**
     * Verify only the type field in the TestValidator validator
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator", fields={"type"})
     *
     * @param Request $request
     *
     * @return array
     */
    public function validateType(Request $request): array
    {
        $method = $request->getMethod();
        if ($method == RequestMethod::GET) {
            return $request->getParsedQuery();
        }
        return $request->getParsedBody();
    }

    /**
     * Verify only the password field in the TestValidator validator
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator", fields={"password"})
     *
     * @param Request $request
     *
     * @return array
     */
    public function validatePassword(Request $request): array
    {
        $method = $request->getMethod();
        if ($method == RequestMethod::GET) {
            return $request->getParsedQuery();
        }
        return $request->getParsedBody();
    }

    /**
     * Customize the validator with userValidator
     *
     * @RequestMapping()
     *
     * @Validate(validator="userValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function validateCustomer(Request $request): array
    {
        $method = $request->getMethod();
        if ($method == RequestMethod::GET) {
            return $request->getParsedQuery();
        }
        return $request->getParsedBody();
    }
}
