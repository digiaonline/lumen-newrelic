# lumen-newrelic

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
	.....
	'Nord\Lumen\Cors\CorsMiddleware',
]);
```

This will ensure that transactions are named properly.

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
