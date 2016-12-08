<?php

namespace Nord\Lumen\NewRelic;

use Illuminate\Support\ServiceProvider;
use Intouch\Newrelic\Newrelic;
use Laravel\Lumen\Application;
use Monolog\Handler\NewRelicHandler;

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


    /**
     * Registers the Monolog New Relic handler for exception logging
     */
    public function boot()
    {
        if (app('config')['newrelic']['register_monolog_handler']) {
            app('log')->pushHandler(new NewRelicHandler());
        }
    }

}
