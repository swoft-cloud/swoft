<?php

namespace Swoft\Db\EntityGenerator;

/**
 * Stub操作类 
 *
 * @uses      SetGetGenerator
 * @version   2017年11月7日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */

class SetGetGenerator
{
    /**
     * @var $folder 模板目录
     */
    public $folder = 'stub';
    
    /**
     * @var string $modelStub ModelStub
     */
    private $modelStubFile = 'Model.stub';

    /**
     * @var string $PropertyStubFile PropertyStub 
     */
    private $propertyStubFile = 'Property.stub';

    /**
     * @var string $setterStub SettrStub
     */
    private $setterStubFile = 'Setter.stub';

    /**
     * @var string $getterStub GetterStub
     */
    private $getterStubFile = 'Getter.stub';

    /**
     * @var string $propertyStub 需要替换property的内容
     */
    private $propertyStub = '';

    /**
     * @var string $setterStub 需要替换setter的内容
     */
    private $setterStub = '';

    /**
     * @var string $getterStub 需要替换的getter的内容
     */
    private $getterStub = '';

    public function __construct()
    {
        $this->folder = __DIR__ . '/' . $this->folder . '/';
    }

    /**
     * @__invoke
     * @override
     *
     * @param array  $uses        需要use的类
     * @param string $entity      实体
     * @param        $entityName  实体中文名
     * @param string $entityClass 实体类
     * @param string $entityDate  实体生成日期
     * @param array  $fields      字段
     */
    public function __invoke(array $uses, 
        string $extends,
        string $entity,
        $entityName,
        string $entityClass,
        string $entityDate,
        array $fields)
    {
        $entityStub = $this->generateModel();
        $usesContent = '';
        foreach ($uses as $useClass) {
            $usesContent .= "use {$useClass};" . PHP_EOL;
        }

        $this->parseFields($fields);

        var_dump(str_replace([
            '{{uses}}',
            '{{extends}}',
            '{{entity}}',
            '{{entityName}}',
            '{{entityClass}}',
            '{{entityDate}}',
            '{{property}}',
            '{{setter}}',
            '{{getter}}'
        ], [
            $usesContent,
            $extends,
            $entity,
            $entityName,
            $entityClass,
            $entityDate,
            $this->propertyStub,
            $this->setterStub,
            $this->getterStub
        ], $entityStub));
        exit;
    }

    /**
     * 开始解析字段信息
     *
     * @param array $fields 字段
     */
    private function parseFields(array $fields)
    {
        $propertyStub = $this->generateProperty();
        $setterStub = $this->generateSetter();
        $getterStub = $this->generateGetter();
        foreach ($fields as $fieldInfo) {
            $this->parseProperty($propertyStub, $fieldInfo);
            $this->parseSetter($setterStub, $fieldInfo);
            $this->parseGetter($getterStub, $fieldInfo);
        }
    }

    /**
     * 解析Property
     *
     * @param string $propertyStub 属性模板
     * @param array  $fieldInfo    字段信息
     *
     */
    private function parseProperty(string $propertyStub, array $fieldInfo)
    {
        $property = $fieldInfo['name'];
        $primaryKey = $fieldInfo['key'] === 'PRI' ? true : false;
        $required = $primaryKey ? false : ($fieldInfo['nullable'] === 'NO' ? true : false);
        $default = !empty($fieldInfo['default']) ? $fieldInfo['default'] : false;
        // TODO 后期调整优化
        $type = $fieldInfo['type'] == 'int' ? 'TYPES::INT' : 'TYPES::STRING';
        $this->propertyStub .= str_replace([
            "{{@Id}}\n",
            '{{property}}',
            '{{type}}',
            "{{@Required}}\n",
            '{{hasDefault}}'
        ], [
            $primaryKey ? "     * @Id()\n" : '',
            $property,
            $type,
            $required ? "     * @Required()\n" : '',
            $default !== false ? " = {$default};" : ($required ? ' = \'\';' : ';')
        ], $propertyStub);
    }

    /**
     * 解析Setter
     *
     * @param string $setterStub setter模板
     * @param array  $fieldInfo   字段信息
     *
     */
    private function parseSetter(string $setterStub, array $fieldInfo)
    {
        $function = 'set' . ucfirst($fieldInfo['name']);
        $attribute = $fieldInfo['name'];
        // TODO 后期调整优化
        $type = $fieldInfo['type'] == 'int' ? 'int' : 'string';
        $this->setterStub .= str_replace([
            '{{function}}',
            '{{attribute}}',
            '{{type}}'
        ], [
            $function,
            $attribute,
            $type
        ], $setterStub);
    }

    /**
     * 解析Getter
     *
     * @param string $getterStub getter模板
     * @param array  $fieldInfo   字段信息
     *
     */
    private function parseGetter(string $getterStub, array $fieldInfo)
    {
        $function = 'get' . ucfirst($fieldInfo['name']);
        $attribute = $fieldInfo['name'];
        // TODO 后期调整优化
        $returnType = $fieldInfo['type'] == 'int' ? 'int' : 'string';
        $this->getterStub .= str_replace([
            '{{function}}',
            '{{attribute}}',
            '{{returnType}}'
        ], [
            $function,
            $attribute,
            $returnType
        ], $getterStub);
    }

    /**
     * 创建Model模板
     *
     * return string
     */
    private function generateModel(): string
    {
        return file_get_contents($this->folder . $this->modelStubFile);
    }

    /**
     * 创建Setter模版
     *
     * return string
     */
    private function generateSetter(): string
    {
        return file_get_contents($this->folder . $this->setterStubFile);
    }

    /**
     * 创建Getter模板
     *
     * @return srting
     */
    private function generateGetter(): string
    {
        return file_get_contents($this->folder . $this->getterStubFile);
    }

    /**
     * 创建Property模板
     *
     * @return srting
     */
    private function generateProperty(): string
    {
        return file_get_contents($this->folder . $this->propertyStubFile);
    }
}
