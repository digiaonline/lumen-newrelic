<?php

namespace Nord\Lumen\NewRelic;

use Closure;
use Illuminate\Http\Request;
use Intouch\Newrelic\Newrelic;

/**
 * Class NewRelicMiddleware
 * @package Nord\Lumen\NewRelic
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

        if (config('newRelic.override_ini')) {
            $this->newRelic->setAppName(
                config('newRelic.application_name'),
                config('newRelic.license'),
                true
            );
        }

        return $response;
    }


    /**
     * Builds the transaction name. It will return the assigned controller action first, then the route name before
     * falling back to just "index.php"
     *
     * @param Request $request
     *
     * @return string
     */
    public function getTransactionName(Request $request)
    {
        $route = $request->route();

        if (is_array($route)) {
            // Try the assigned controller action
            if (isset($route[1]) && isset($route[1]['uses'])) {
                return $route[1]['uses'];
            } // Try named routes
            elseif (isset($route[1]) && isset($route[1]['as'])) {
                return $route[1]['as'];
            }
        }

        return 'index.php';
    }

}
