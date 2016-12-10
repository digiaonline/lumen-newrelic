<?php

namespace Nord\Lumen\NewRelic\Tests;

use Exception;
use InvalidArgumentException;
use Nord\Lumen\NewRelic\NewRelicExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NewRelicExceptionHandlerTest
 * @package Nord\Lumen\NewRelic\Tests
 */
class NewRelicExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests that exceptions that are not on the ignore list get reported
     *
     * @expectedException \Exception
     * @expectedExceptionMessage testReportException
     */
    public function testReportException()
    {
        $handler = new TestNewRelicExceptionHandler();
        $handler->report(new Exception('testReportException'));
    }


    /**
     * Tests that exceptions on the default ignore list are not reported
     */
    public function testReportDefaultIgnoredException()
    {
        $handler = new TestNewRelicExceptionHandler();
        $handler->report(new NotFoundHttpException());
    }


    /**
     * Tests that specifically defined ignored exceptions are not reported
     */
    public function testReportIgnoredException()
    {
        $handler = new TestNewRelicExceptionHandler([InvalidArgumentException::class]);
        $handler->report(new InvalidArgumentException('testReportIgnoredException'));
    }

}

/**
 * Class TestNewRelicExceptionHandler
 * @package Nord\Lumen\NewRelic\Tests
 */
class TestNewRelicExceptionHandler extends NewRelicExceptionHandler
{

    /**
     * @inheritdoc
     */
    protected function logException(Exception $e)
    {
        // Used to indicate that this method was actually executed
        throw $e;
    }

}
