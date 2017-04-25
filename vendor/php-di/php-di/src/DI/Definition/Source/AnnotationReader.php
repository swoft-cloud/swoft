<?php

namespace DI\Definition\Source;

use DI\Annotation\Inject;
use DI\Annotation\Injectable;
use DI\Definition\EntryReference;
use DI\Definition\Exception\AnnotationException;
use DI\Definition\Exception\DefinitionException;
use DI\Definition\ObjectDefinition;
use DI\Definition\ObjectDefinition\MethodInjection;
use DI\Definition\ObjectDefinition\PropertyInjection;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use InvalidArgumentException;
use PhpDocReader\PhpDocReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use UnexpectedValueException;

/**
 * Provides DI definitions by reading annotations such as @ Inject and @ var annotations.
 *
 * Uses Autowiring, Doctrine's Annotations and regex docblock parsing.
 * This source automatically includes the reflection source.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AnnotationReader implements DefinitionSource
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var PhpDocReader
     */
    private $phpDocReader;

    /**
     * @var bool
     */
    private $ignorePhpDocErrors;

    public function __construct($ignorePhpDocErrors = false)
    {
        $this->ignorePhpDocErrors = (bool) $ignorePhpDocErrors;
    }

    /**
     * {@inheritdoc}
     * @throws AnnotationException
     * @throws InvalidArgumentException The class doesn't exist
     */
    public function getDefinition($name)
    {
        if (!class_exists($name) && !interface_exists($name)) {
            return null;
        }

        $class = new ReflectionClass($name);
        $definition = new ObjectDefinition($name);

        $this->readInjectableAnnotation($class, $definition);

        // Browse the class properties looking for annotated properties
        $this->readProperties($class, $definition);

        // Browse the object's methods looking for annotated methods
        $this->readMethods($class, $definition);

        return $definition;
    }

    /**
     * Browse the class properties looking for annotated properties.
     */
    private function readProperties(ReflectionClass $class, ObjectDefinition $definition)
    {
        foreach ($class->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $this->readProperty($property, $definition);
        }

        // Read also the *private* properties of the parent classes
        /** @noinspection PhpAssignmentInConditionInspection */
        while ($class = $class->getParentClass()) {
            foreach ($class->getProperties(ReflectionProperty::IS_PRIVATE) as $property) {
                if ($property->isStatic()) {
                    continue;
                }
                $this->readProperty($property, $definition, $class->getName());
            }
        }
    }

    private function readProperty(ReflectionProperty $property, ObjectDefinition $definition, $classname = null)
    {
        // Look for @Inject annotation
        /** @var $annotation Inject */
        $annotation = $this->getAnnotationReader()->getPropertyAnnotation($property, 'DI\Annotation\Inject');
        if ($annotation === null) {
            return null;
        }

        // @Inject("name") or look for @var content
        $entryName = $annotation->getName() ?: $this->getPhpDocReader()->getPropertyClass($property);

        if ($entryName === null) {
            throw new AnnotationException(sprintf(
                '@Inject found on property %s::%s but unable to guess what to inject, use a @var annotation',
                $property->getDeclaringClass()->getName(),
                $property->getName()
            ));
        }

        $definition->addPropertyInjection(
            new PropertyInjection($property->getName(), new EntryReference($entryName), $classname)
        );
    }

    /**
     * Browse the object's methods looking for annotated methods.
     */
    private function readMethods(ReflectionClass $class, ObjectDefinition $objectDefinition)
    {
        // This will look in all the methods, including those of the parent classes
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic()) {
                continue;
            }

            $methodInjection = $this->getMethodInjection($method);

            if (! $methodInjection) {
                continue;
            }

            if ($method->isConstructor()) {
                $objectDefinition->setConstructorInjection($methodInjection);
            } else {
                $objectDefinition->addMethodInjection($methodInjection);
            }
        }
    }

    private function getMethodInjection(ReflectionMethod $method)
    {
        // Look for @Inject annotation
        /** @var $annotation Inject|null */
        try {
            $annotation = $this->getAnnotationReader()->getMethodAnnotation($method, 'DI\Annotation\Inject');
        } catch (AnnotationException $e) {
            throw new AnnotationException(sprintf(
                '@Inject annotation on %s::%s is malformed. %s',
                $method->getDeclaringClass()->getName(),
                $method->getName(),
                $e->getMessage()
            ), 0, $e);
        }
        $annotationParameters = $annotation ? $annotation->getParameters() : [];

        // @Inject on constructor is implicit
        if (! ($annotation || $method->isConstructor())) {
            return null;
        }

        $parameters = [];
        foreach ($method->getParameters() as $index => $parameter) {
            $entryName = $this->getMethodParameter($index, $parameter, $annotationParameters);

            if ($entryName !== null) {
                $parameters[$index] = new EntryReference($entryName);
            }
        }

        if ($method->isConstructor()) {
            return MethodInjection::constructor($parameters);
        } else {
            return new MethodInjection($method->getName(), $parameters);
        }
    }

    /**
     * @param int                 $parameterIndex
     * @param ReflectionParameter $parameter
     * @param array               $annotationParameters
     *
     * @return string|null Entry name or null if not found.
     */
    private function getMethodParameter($parameterIndex, ReflectionParameter $parameter, array $annotationParameters)
    {
        // @Inject has definition for this parameter (by index, or by name)
        if (isset($annotationParameters[$parameterIndex])) {
            return $annotationParameters[$parameterIndex];
        }
        if (isset($annotationParameters[$parameter->getName()])) {
            return $annotationParameters[$parameter->getName()];
        }

        // Skip optional parameters if not explicitly defined
        if ($parameter->isOptional()) {
            return null;
        }

        // Try to use the type-hinting
        $parameterClass = $parameter->getClass();
        if ($parameterClass) {
            return $parameterClass->getName();
        }

        // Last resort, look for @param tag
        return $this->getPhpDocReader()->getParameterClass($parameter);
    }

    /**
     * @return Reader The annotation reader
     */
    public function getAnnotationReader()
    {
        if ($this->annotationReader === null) {
            AnnotationRegistry::registerAutoloadNamespace('DI\Annotation', __DIR__ . '/../../../');
            $this->annotationReader = new SimpleAnnotationReader();
            $this->annotationReader->addNamespace('DI\Annotation');
        }

        return $this->annotationReader;
    }

    /**
     * @return PhpDocReader
     */
    private function getPhpDocReader()
    {
        if ($this->phpDocReader === null) {
            $this->phpDocReader = new PhpDocReader($this->ignorePhpDocErrors);
        }

        return $this->phpDocReader;
    }

    private function readInjectableAnnotation(ReflectionClass $class, ObjectDefinition $definition)
    {
        try {
            /** @var $annotation Injectable|null */
            $annotation = $this->getAnnotationReader()
                ->getClassAnnotation($class, 'DI\Annotation\Injectable');
        } catch (UnexpectedValueException $e) {
            throw new DefinitionException(sprintf(
                'Error while reading @Injectable on %s: %s',
                $class->getName(),
                $e->getMessage()
            ), 0, $e);
        }

        if (! $annotation) {
            return;
        }

        if ($annotation->getScope()) {
            $definition->setScope($annotation->getScope());
        }
        if ($annotation->isLazy() !== null) {
            $definition->setLazy($annotation->isLazy());
        }
    }
}
