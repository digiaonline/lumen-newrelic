# lumen-newrelic

[![GitHub Actions status](https://github.com/digiaonline/lumen-newrelic/workflows/Test/badge.svg)](https://github.com/digiaonline/lumen-newrelic/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digiaonline/lumen-newrelic/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/digiaonline/lumen-newrelic/?branch=main)
[![Coverage Status](https://coveralls.io/repos/github/digiaonline/lumen-newrelic/badge.svg?branch=main)](https://coveralls.io/github/digiaonline/lumen-newrelic?branch=main)

This library provides New Relic instrumentation for the Lumen framework. When installed this library will ensure that 
your transactions are properly named and that your exceptions are properly logged in New Relic.

## Requirements

- PHP >= 7.2
- [Composer](http://getcomposer.org)
- [Lumen](https://lumen.laravel.com/) 5.5 or newer

## Usage

### Installation

Run the following command to install the package through Composer:

```sh
composer require nordsoftware/lumen-newrelic
```

### Configuration

You don't have to set any config values if you are happy to rely on the content of your `newrelic.ini` file. However,
it's possible to override the license and application name using environment variables or the `.env` file. This can be
useful if you deploy the same app in multiple environments, and therefore need distinguished names for each.

Be aware that doing so can have a [small performance impact](https://github.com/In-Touch/newrelic/blob/5dc4eb7a25731f92cdbfb7a094a788cf137df40e/src/Newrelic.php#L82-L87) on the app.

The environment variables available are:

* `NEW_RELIC_OVERRIDE_INI` (defaults to `false`)
* `NEW_RELIC_APP_NAME`
* `NEW_RELIC_LICENSE_KEY`

If you don't set `NEW_RELIC_OVERRIDE_INI` to `true`, the app will not override the `newrelic.ini` file, regardless of the 
value of the other two variables.

Check out the description in `/config/newRelic.php` for an explanation of the effect.

### Bootstrapping

In `bootstrap/app.php`, replace the `$app->singleton()` call which registers the exception handler with the following 
snippet:

```php
$app->instance(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    new Nord\Lumen\ChainedExceptionHandler\ChainedExceptionHandler(
        new \App\Exceptions\Handler(),
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

## Marking transactions as background jobs

If you have a dedicated route for webhooks, notifications, or other long-running background tasks you can apply the 
`Nord\Lumen\NewRelic\NewRelicBackgroundJobMiddleware` to the route in question. This will mark the transaction as a 
background job in New Relic so that long processing times don't skew your response time graphs.

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
