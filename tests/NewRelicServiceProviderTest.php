<?php

namespace Nord\Lumen\NewRelic\Tests;

use Laravel\Lumen\Application;
use Nord\Lumen\ChainedExceptionHandler\ChainedExceptionHandler;
use Nord\Lumen\NewRelic\NewRelicExceptionHandler;
use Nord\Lumen\NewRelic\NewRelicServiceProvider;

/**
 * Class NewRelicServiceProviderTest
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private static $expectedConfigurationKeys = [
        'transaction_name_pattern',
    ];


    /**
     * Tests that the service provider registers without issues
     */
    public function testRegisterServiceProvider()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);
    }


    /**
     * Tests that the service provider is configured correctly
     */
    public function testServiceProviderConfiguration()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);

        $configKey = NewRelicServiceProvider::CONFIG_KEY;

        $this->assertArrayHasKey($configKey, $app['config']);

        foreach (self::$expectedConfigurationKeys as $expectedConfigurationKey) {
            $this->assertArrayHasKey($expectedConfigurationKey, $app['config'][$configKey]);
        }
    }


    /**
     * Tests that the exception handler is added correctly
     *
     * @expectedException \Exception
     */
    public function testRegisterMonologHandler()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            new ChainedExceptionHandler(
                new \Laravel\Lumen\Exceptions\Handler(), [
                new NewRelicExceptionHandler(),
            ])
        );

        throw new \Exception();
    }
}
