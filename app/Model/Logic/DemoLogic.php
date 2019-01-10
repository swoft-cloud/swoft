<?php

namespace App\Model\Logic;

use App\Model\Data\DemoData;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * @Bean(alias="deloc")
 */
class DemoLogic
{
    /**
     * @Inject()
     * @var DemoData
     */
    private $data;

    /**
     * @var string
     */
    private $definitionData;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $type = 0;

    /**
     * @var DemoData
     */
    private $construnctData;

    /**
     * DemoLogic constructor.
     *
     * @param string $name
     * @param string $type
     * @param DemoData
     */
    public function __construct(string $name, string $type, DemoData $data)
    {
        $this->name = $name;
        $this->type = $type;

        $this->construnctData = $data;
    }

    /**
     * @return DemoData
     */
    public function getData(): DemoData
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getDefinitionData(): string
    {
        return $this->definitionData;
    }
}