<?php


namespace App\Validator;


use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Contract\ValidatorInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class CustomerValidator
 * @package App\Validator
 * @since 2.0
 * @Validator(name="userValidator")
 */
class CustomerValidator implements ValidatorInterface
{
    /**
     * @param array $data
     * @param array $params
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, array $params): array
    {
        $start = $data['start']??null;
        $end   = $data['end']??null;

        if ($start==null && $end==null) {
            throw new ValidatorException('开始时间和结束时间不能为空');
        }

        if ($start > $end) {
            throw new ValidatorException('开始不能大于结束时间');
        }

        return $data;
    }


}
