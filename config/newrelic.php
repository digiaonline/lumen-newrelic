<?php

return [

    /**
     * The pattern used when naming transactions. Valid modifiers are:
     *   * {controller} - the "uses" part of the route
     *   * {routeName} - the "as" part of the route
     */
    'transaction_name_pattern' => env('NEWRELIC_TRANSACTION_NAME_PATTERN', '{controller}'),

];
