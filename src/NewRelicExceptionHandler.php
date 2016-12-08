<?php

namespace Nord\Lumen\NewRelic;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;

/**
 * Class NewRelicExceptionHandler
 * @package Nord\Lumen\NewRelic
 */
class NewRelicExceptionHandler implements ExceptionHandler
{

    /**
     * @inheritdoc
     */
    public function report(Exception $e)
    {
        if (extension_loaded('newrelic')) {
            newrelic_notice_error($e->getMessage(), $e);
        }
    }


    /**
     * @inheritdoc
     */
    public function render($request, Exception $e)
    {

    }


    /**
     * @inheritdoc
     */
    public function renderForConsole($output, Exception $e)
    {

    }

}
