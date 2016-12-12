<?php

namespace Nord\Lumen\NewRelic\Tests;

use Illuminate\Http\Request;
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
     * Tests that the service provider registers without issues
     */
    public function testRegisterServiceProvider()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);
    }


    /**
     * Tests that the exception handler is added correctly
     */
    public function testRegisterExceptionHandler()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);

        $app->instance(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            new ChainedExceptionHandler(
                new \Laravel\Lumen\Exceptions\Handler(), [
                new NewRelicExceptionHandler(),
            ])
        );

        // Define a route that throws an exception
        $app->get('/', function() {
            throw new \Exception();
        });

        // Verify that the error handling doesn't silenty fail (which would happen if the exception handler isn't 
        // registered correctly)
        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertContains('Whoops, looks like something went wrong', $response->getContent());
    }
}
