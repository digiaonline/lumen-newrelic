<?php

return [

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