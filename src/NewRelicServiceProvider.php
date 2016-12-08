<?php

namespace Nord\Lumen\NewRelic;

use Illuminate\Support\ServiceProvider;
use Intouch\Newrelic\Newrelic;
use Laravel\Lumen\Application;

/**
 * Class NewRelicServiceProvider
 * @package Nord\Lumen\NewRelic\Providers
 */
class NewRelicServiceProvider extends ServiceProvider
{

    const CONFIG_KEY = 'newrelic';


    /**
     * Registers the service provider
     */
    public function register()
    {
        /* @var Application $app */
        $app = $this->app;

        $app->configure(self::CONFIG_KEY);
        $app->singleton(Newrelic::class, function() {
            return new Newrelic();
        });
    }

}
