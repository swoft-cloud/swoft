# PhpDocReader

[![Build Status](https://img.shields.io/travis/PHP-DI/PhpDocReader.svg)](https://travis-ci.org/mnapoli/PhpDocReader)
[![Coverage Status](https://img.shields.io/coveralls/PHP-DI/PhpDocReader.svg)](https://coveralls.io/r/mnapoli/PhpDocReader)
![](https://img.shields.io/packagist/dt/PHP-DI/phpdoc-reader.svg)

This project is used by:

- [PHP-DI](http://php-di.org/)
- [phockito-unit-php-di](https://github.com/balihoo/phockito-unit-php-di)

Fork the README to add your project here.

## Features

PhpDocReader parses `@var` and `@param` values in PHP docblocks:

```php

use My\Cache\Backend;

class Cache
{
    /**
     * @var Backend
     */
    protected $backend;

    /**
     * @param Backend $backend
     */
    public function __construct($backend)
    {
    }
}
```

It supports namespaced class names with the same resolution rules as PHP:

- fully qualified name (starting with `\`)
- imported class name (eg. `use My\Cache\Backend;`)
- relative class name (from the current namespace, like `SubNamespace\MyClass`)
- aliased class name  (eg. `use My\Cache\Backend as FooBar;`)

Primitive types (`@var string`) are ignored (returns null), only valid class names are returned.

## Usage

```php
$reader = new PhpDocReader();

// Read a property type (@var phpdoc)
$property = new ReflectionProperty($className, $propertyName);
$propertyClass = $reader->getPropertyClass($property);

// Read a parameter type (@param phpdoc)
$parameter = new ReflectionParameter(array($className, $methodName), $parameterName);
$parameterClass = $reader->getParameterClass($parameter);
```
