<?php

namespace Nord\Lumen\NewRelic;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NewRelicExceptionHandler
 * @package Nord\Lumen\NewRelic
 */
class NewRelicExceptionHandler implements ExceptionHandler
{

    /**
     * @var array list of class names of exceptions that should not be reported to New Relic. Defaults to the
     *            NotFoundHttpException class used for 404 requests.
     */
    protected $ignoredExceptions = [
        NotFoundHttpException::class,
    ];


    /**
     * NewRelicExceptionHandler constructor.
     *
     * @param array $ignoredExceptions (optional)
     */
    public function __construct(array $ignoredExceptions = [])
    {
        if (!empty($ignoredExceptions)) {
            $this->ignoredExceptions = $ignoredExceptions;
        }
    }


    /**
     * @inheritdoc
     */
    public function report(Exception $e)
    {
        if (!in_array(get_class($e), $this->ignoredExceptions)) {
            $this->logException($e);
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


    /**
     * Logs the exception to New Relic (if the extension is loaded)
     *
     * @param Exception $e
     */
    protected function logException(Exception $e)
    {
        if (extension_loaded('newrelic')) {
            newrelic_notice_error($e->getMessage(), $e);
        }
    }

}
