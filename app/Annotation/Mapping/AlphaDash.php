<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * Class AlphaDash
 *
 * @since 2.0
 *
 * @Annotation
 * @Attributes({
 *     @Attribute("message",type="string")
 * })
 */
class AlphaDash
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * StringType constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->message = $values['value'];
        }
        if (isset($values['message'])) {
            $this->message = $values['message'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
