# lumen-newrelic

[![Build Status](https://travis-ci.org/nordsoftware/lumen-newrelic.svg?branch=travis)](https://travis-ci.org/nordsoftware/lumen-newrelic)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nordsoftware/lumen-newrelic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nordsoftware/lumen-newrelic/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/nordsoftware/lumen-newrelic/badge.svg?branch=travis)](https://coveralls.io/github/nordsoftware/lumen-newrelic?branch=travis)

This library provides New Relic instrumentation for the Lumen framework. When installed this library will ensure that 
your transactions are properly named and that your exceptions are properly logged in New Relic.

## Requirements

- PHP 5.6 or newer
- [Composer](http://getcomposer.org)
- [Lumen](https://lumen.laravel.com/) 5.2 or newer

## Usage

### Installation

Run the following command to install the package through Composer:

```sh
composer require nordsoftware/lumen-newrelic
```

### Bootstrapping

In `bootstrap/app.php`, replace the `$app->singleton()` call which registers the exception handler with the following 
snippet:

```php
$app->instance(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    new Nord\Lumen\ChainedExceptionHandler\ChainedExceptionHandler(
        new Laravel\Lumen\Exceptions\Handler(),
        [new Nord\Lumen\NewRelic\NewRelicExceptionHandler()]
    )
);
```

This will ensure that exceptions are correctly reported to New Relic.

Now, add the middleware too:

```php
$app->middleware([
	...
	Nord\Lumen\NewRelic\NewRelicMiddleware::class,
]);
```

This will ensure that transactions are named properly.

Finally, register the service provider:

```php
$app->register(Nord\Lumen\NewRelic\NewRelicServiceProvider::class);
```

## Ignoring certain exceptions

By default the exception handler ignores exceptions of type 
`Symfony\Component\HttpKernel\Exception\NotFoundHttpException`. You can customize the list of ignored exceptions by 
passing an array to the exception handler's constructor:

```php
$exceptionHandler = new Nord\Lumen\NewRelic\NewRelicExceptionHandler([
	FooException::class,
	BarException::class,
]);
```

If you don't want any exception to be ignored, pass an empty array to the constructor.

## Customizing transaction names

By default the transaction name will use the `controller@action` assigned to the route. If that fails, it will use the 
route's name. If no name is defined, it will fallback to just `index.php`.

If this doesn't meet your requirements, extend the `Nord\Lumen\NewRelic\NewRelicMiddleware` class and override the 
`getTransactionName()` method, then register that middleware class instead.

## Running tests

Clone the project and install its dependencies by running:

```sh
composer install
```

Run the following command to run the test suite:

```sh
vendor/bin/phpunit
```

## License

See [LICENSE](LICENSE)
