<?php

namespace Nord\Lumen\NewRelic;

use Closure;
use Illuminate\Http\Request;
use Intouch\Newrelic\Newrelic;

/**
 * Class NewRelicMiddleware
 * @package Nord\Lumen\NewRelic\Middleware
 */
class NewRelicMiddleware
{

    /**
     * @var Newrelic
     */
    protected $newRelic;


    /**
     * NewRelicMiddleware constructor.
     *
     * @param Newrelic $newRelic
     */
    public function __construct(Newrelic $newRelic)
    {
        $this->newRelic = $newRelic;
    }


    /**
     * Handles the request by naming the transaction for New Relic
     *
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next)
    {
        // We must let the response get handled before naming the transaction, otherwise the necessary route i
        // information won't be available in the request object.
        $response = $next($request);

        $this->newRelic->nameTransaction($this->getTransactionName($request));

        return $response;
    }


    /**
     * Builds the transaction name
     *
     * @param Request $request
     *
     * @return string
     */
    public function getTransactionName(Request $request)
    {
        return str_replace(
            [
                '{controller}',
                '{routeName}',
            ],
            [
                $this->getController($request->route()),
                $this->getRouteName($request->route()),
            ],
            app()['config']->get('newrelic.transaction_name_pattern')
        );
    }


    /**
     * Get the current controller / action
     *
     * @param mixed $route the details about the current route
     *
     * @return string
     */
    protected function getController($route)
    {
        if (is_array($route) && isset($route[1]) && isset($route[1]['uses'])) {
            return $route[1]['uses'];
        }

        return 'index.php';
    }


    /**
     * Get the current route name
     *
     * @param mixed $route the details about the current route
     *
     * @return string
     */
    protected function getRouteName($route)
    {
        if (is_array($route) && isset($route[1]) && isset($route[1]['as'])) {
            return $route[1]['as'];
        }

        return 'index.php';
    }

}
