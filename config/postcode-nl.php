<?php

return [

    /*
     * Request options required for GuzzleHttp client.
     */
    'requestOptions' => [

        /*
         * Authentication
         *
         * Register an account with Postcode.nl to obtain a key and secret. See https://api.postcode.nl/#register for
         * further information.
         */
        'auth' => [
            env('POSTCODENL_KEY'),
            env('POSTCODENL_SECRET')
        ],

        /*
         * Timeout (in seconds)
         *
         * By default, the client waits 10 seconds for a response. You may set a different timeout.
         */
        'timeout' => env('POSTCODENL_TIMEOUT', 10),

    ],

    /*
     * Enable routes
     *
     * This package comes with a set of routes, which are not loaded by default. In order to use them, set this
     * option to true.
     */
    'enableRoutes' => env('POSTCODENL_ENABLE_ROUTES', false),

];
