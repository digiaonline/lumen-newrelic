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

        if (\file_exists(__DIR__.'/../config/newrelic.php')) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/newrelic.php', 'newrelic'
            );
        }

        // This will make sure to not call configure() on non-Lumen apps
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $this->app->configure('newrelic');
        }
    }

}
