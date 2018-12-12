<?php

namespace Nord\Lumen\NewRelic;

use Closure;
use Illuminate\Http\Request;

/**
 * Class NewRelicBackgroundJobMiddleware
 * @package Nord\Lumen\NewRelic
 */
class NewRelicBackgroundJobMiddleware extends NewRelicMiddleware
{

    /**
     * @inheritdoc
     */
    public function handle(Request $request, Closure $next)
    {
        // Mark the request as a background job
        $this->newRelic->backgroundJob();

        return parent::handle($request, $next);
    }
}
