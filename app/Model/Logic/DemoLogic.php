<?php

namespace App\Model\Logic;

use App\Model\Data\DemoData;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Config\Annotation\Config;

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
     * @Config("name")
     * @var string
     */
    private $configName = '';

    /**
     * @Config("db.host")
     * @var string
     */
    private $configHost = '';

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
        $this->data->getDao();
        echo 'name=' . $this->name . PHP_EOL;
        echo 'type=' . $this->type . PHP_EOL;
        echo 'configName=' . $this->configName . PHP_EOL;
        echo 'configHost=' . $this->configHost . PHP_EOL;

        return $this->construnctData;
    }

    /**
     * @return string
     */
    public function getDefinitionData(): string
    {
        return $this->definitionData;
    }
}