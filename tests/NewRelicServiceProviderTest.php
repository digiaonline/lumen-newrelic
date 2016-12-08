<?php

namespace Nord\Lumen\NewRelic\Tests;

use Laravel\Lumen\Application;
use Monolog\Handler\NewRelicHandler;
use Nord\Lumen\NewRelic\NewRelicServiceProvider;

/**
 * Class NewRelicServiceProviderTest
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
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
        $this->assertArrayHasKey('transaction_name_pattern', $app['config'][$configKey]);
        $this->assertArrayHasKey('register_monolog_handler', $app['config'][$configKey]);
    }


    /**
     * Tests that the Monolog New Relic error handler has been registered
     */
    public function testRegisterMonologHandler()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);

        $handlers        = app('log')->getHandlers();
        $newRelicHandler = null;

        foreach ($handlers as $handler) {
            if ($handler instanceof NewRelicHandler) {
                $newRelicHandler = $handler;
                break;
            }
        }

        $this->assertNotNull($newRelicHandler);
    }
}
