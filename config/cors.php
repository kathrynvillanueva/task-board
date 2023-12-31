<?php

return [
     /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
    'supportsCredentials' => false,
    'paths' => ['*'],
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedMethods' => ['*'],
    'allowedHeaders' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];
