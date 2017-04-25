# Contributing

[![Build Status](https://travis-ci.org/PHP-DI/PHP-DI.png?branch=master)](https://travis-ci.org/PHP-DI/PHP-DI) [![Coverage Status](https://coveralls.io/repos/PHP-DI/PHP-DI/badge.png?branch=master)](https://coveralls.io/r/PHP-DI/PHP-DI?branch=master)

PHP-DI is licensed under the MIT License.


## Set up

* Check out the sources using git or download them

```bash
$ git clone https://github.com/PHP-DI/PHP-DI.git
```

* Install the libraries using composer:

```bash
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

If you are running Windows or are having trouble, read [the official documentation](http://getcomposer.org/doc/00-intro.md#installation).


## Run the tests

The tests are run with [PHPUnit](http://www.phpunit.de/manual/current/en/installation.html):

```bash
$ phpunit
```


## Learning the internals

Read the [How it works](doc/how-it-works.md) documentation.


## What to do?

- Add tests: pick up uncovered situations in the [code coverage report](https://coveralls.io/r/PHP-DI/PHP-DI)
- Resolve issues: [issue list](https://github.com/PHP-DI/PHP-DI/issues)
- Improve the documentation
- …


## Coding style

The code follows PSR0, PSR1 and [PSR2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

Also, do not hesitate to add your name to the author list of a class in the docblock if you improve it.
