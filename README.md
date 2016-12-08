# lumen-newrelic

New Relic instrumentation for the Lumen framework. When installed this library will ensure that your transactions are 
properly named and that your exceptions are properly logged in New Relic.

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

### Configure

Copy the configuration template in `config/newrelic.php` to your application's `config` directory.

### Bootstrapping

Add the following lines to ```bootstrap/app.php```:

```php
$app->register('Nord\Lumen\Cors\CorsServiceProvider');
```

```php
$app->middleware([
	.....
	'Nord\Lumen\Cors\CorsMiddleware',
]);
```

Additionally, to ensure exceptions are properly logged to New Relic you'll want to add this to your production 
`.env` file:

```
NEWRELIC_REGISTER_MONOLOG_HANDLER=true
```

The log handler will throw an exception if the New Relic PHP module is not installed, so don't enable this in your 
development environment.

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
