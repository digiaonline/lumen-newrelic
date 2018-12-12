<?php

namespace Nord\Lumen\NewRelic\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intouch\Newrelic\Newrelic;
use Laravel\Lumen\Application;
use Nord\Lumen\NewRelic\NewRelicBackgroundJobMiddleware;
use Nord\Lumen\NewRelic\NewRelicMiddleware;

/**
 * Class NewRelicMiddlewareTest
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicMiddlewareTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test with an unnamed closure based route
     */
    public function testClosureBasedTransactionNames()
    {
        $newrelic = $this->getMockedNewRelic();

        $app = new Application();
        $app->instance(Newrelic::class, $newrelic);
        $app->middleware([NewRelicMiddleware::class]);

        $app->get('/', function () {
            return 'Hello World';
        });

        $newrelic->expects($this->once())
                 ->method('nameTransaction')
                 ->with('index.php');

        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * Test with a named route
     */
    public function testNamedRouteTransactionNames()
    {
        $newrelic = $this->getMockedNewRelic();

        $app = new Application();
        $app->instance(Newrelic::class, $newrelic);
        $app->middleware([NewRelicMiddleware::class]);

        $app->get('/route', [
            'as' => 'routeName',
            function () {
                return 'Hello World';
            },
        ]);

        $newrelic->expects($this->once())
                 ->method('nameTransaction')
                 ->with('routeName');

        $response = $app->handle(Request::create('/route', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * Test with a controller based route
     */
    public function testControllerBasedTransactionNames()
    {
        $newrelic = $this->getMockedNewRelic();

        $app = new Application();
        $app->instance(Newrelic::class, $newrelic);
        $app->middleware([NewRelicMiddleware::class]);

        $app->get('/route/{id}', [
            'as'   => 'routeName',
            'uses' => 'Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction',
        ]);

        $newrelic->expects($this->once())
                 ->method('nameTransaction')
                 ->with('Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction');

        $response = $app->handle(Request::create('/route/1', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * Tests that the background job middleware in conjunction with the transaction naming middleware works properly
     */
    public function testBackgroundJobMiddleware()
    {
        $newrelic = $this->getMockedNewRelic();

        $app = new Application();
        $app->instance(Newrelic::class, $newrelic);

        $app->get('/route/{id}', [
            'middleware' => [
                NewRelicBackgroundJobMiddleware::class,
                NewRelicMiddleware::class,
            ],
            'uses'       => 'Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction',
        ]);

        $newrelic->expects($this->once())
                 ->method('backgroundJob');

        $newrelic->expects($this->once())
                 ->method('nameTransaction')
                 ->with('Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction');

        $response = $app->handle(Request::create('/route/1', 'GET'));
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Newrelic
     */
    private function getMockedNewRelic()
    {
        $newrelic = $this->getMockBuilder(Newrelic::class)
                         ->setMethods(['backgroundJob', 'nameTransaction'])
                         ->getMock();

        return $newrelic;
    }
}

/**
 * Class NewRelicTestController
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicTestController
{

    public function testAction()
    {
        return new Response();
    }
}
