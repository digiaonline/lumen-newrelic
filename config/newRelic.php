<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Override newrelic.ini
    |--------------------------------------------------------------------------
    |
    | This config option enables overriding the application name and license 
    | specified in the newrelic.ini file. This is useful for cases where you 
    | cannot set the contents of newrelic.ini, or when you have a multi-site 
    | server and each site to appear separately in NewRelic.
    |
    | As naming is done on a per transaction basis, and can have a performance
    | impact on the app, this setting defaults to false and must be manally
    | overridden.
    | 
    */
    'override_ini' => env('NEW_RELIC_OVERRIDE_INI', false),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This config option sets the application name that data is reported under
    | in New Relic APM. New Relic highly recommends that you replace the
    | default name with a descriptive name to avoid confusion and unintended
    | aggregation of data. Data for all applications with the same name will
    | be merged in the New Relic UI, so set this carefully.
    |
    | If none if provided, the value from newrelic.ini is used.
    |
    */
    'application_name' => env('NEW_RELIC_APP_NAME', ini_get('newrelic.appname')),

    /*
    |--------------------------------------------------------------------------
    | License
    |--------------------------------------------------------------------------
    |
    | Sets the New Relic license key to use. If this is not set, the New Relic
    | module will not attempt to post data to its collectors.
    |
    | If none if provided, the value from newrelic.ini is used.
    |
    */
    'license' => env('NEW_RELIC_LICENSE_KEY', ini_get('newrelic.license')),

];