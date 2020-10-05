<?php

namespace Nord\Lumen\NewRelic;

use Illuminate\Support\ServiceProvider;
use Intouch\Newrelic\Newrelic;

/**
 * Class NewRelicServiceProvider
 * @package Nord\Lumen\NewRelic
 */
class NewRelicServiceProvider extends ServiceProvider
{

    /**
     * Registers the service provider
     */
    public function register()
    {
        $this->app->singleton(Newrelic::class, function() {
            return new Newrelic();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/newrelic.php', 'newRelic'
        );
    }

}
