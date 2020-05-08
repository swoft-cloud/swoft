# Tests

## Http tests

**If you want to run http client tests, you must start the http server.**

### Run http tests

Can only be run inside phpstorm.

![http-client-tests](testdata/http-client-tests.png)

## API tests

**If you want to run api tests, you must start the http server.**

## Start server

```bash
php bin/swoft http:start
# OR
php bin/swoft http:start -d
```

### Run api tests

- use vendor phpunit

```bash
vendor/bin/phpunit --testsuite apiTests
```

- use global installed phpunit

```bash
phpunit --testsuite apiTests
```

## Unit tests

### Run unit tests

- use vendor phpunit

```bash
vendor/bin/phpunit --testsuite unitTests
```

- use global installed phpunit

```bash
phpunit --testsuite unitTests
```
