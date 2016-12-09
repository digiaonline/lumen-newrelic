<?php

namespace Nord\Lumen\NewRelic\Tests;

use Closure;
use Illuminate\Http\Request;
use Laravel\Lumen\Application;
use Nord\Lumen\NewRelic\NewRelicMiddleware;
use Nord\Lumen\NewRelic\NewRelicServiceProvider;

/**
 * Class NewRelicMiddlewareTest
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicMiddlewareTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test with a closure based route
     */
    public function testClosureBasedTransactionNames()
    {
        $app = $this->getApplication();

        $app->get('/', function() {
            return 'Hello World';
        });

        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('index.php', $response->getContent());
    }


    /**
     * Test with a controller based route
     */
    public function testControllerBasedTransactionNames()
    {
        $app = $this->getApplication();

        $app->get('/route', [
            'as' => 'routeName',
            function() {
                return 'Hello World';
            },
        ]);

        $response = $app->handle(Request::create('/route', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('routeName',
            $response->getContent());
    }


    /**
     * Test with a named route
     */
    public function testNamedRouteTransactionNames()
    {
        $app = $this->getApplication();

        $app->get('/route/{id}', [
            'as'   => 'routeName',
            'uses' => 'Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction',
        ]);

        $response = $app->handle(Request::create('/route/1', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Nord\Lumen\NewRelic\Tests\NewRelicTestController@testAction',
            $response->getContent());
    }


    /**
     * @return Application
     */
    private function getApplication()
    {
        $app = new Application();
        $app->register(NewRelicServiceProvider::class);
        $app->middleware(NewRelicTestMiddleware::class);

        return $app;
    }

}

/**
 * Class NewRelicTestMiddleware
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicTestMiddleware extends NewRelicMiddleware
{

    /**
     * @inheritdoc
     */
    public function handle(Request $request, Closure $next)
    {
        $next($request);

        return response($this->getTransactionName($request));
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
        return response('testAction');
    }
}
