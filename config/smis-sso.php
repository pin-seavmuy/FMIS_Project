<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMIS App Key
    |--------------------------------------------------------------------------
    |
    | The app key issued by SMIS. This must match the key used by your
    | front-end when creating the AuthClient.
    |
    */
    'app_key' => env('SMIS_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Auth gateway base URL
    |--------------------------------------------------------------------------
    |
    | Base URL of the SMIS auth gateway (e.g., http://localhost:3000).
    |
    */
    'auth_base_url' => env('SMIS_AUTH_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Clock Skew (seconds)
    |--------------------------------------------------------------------------
    |
    | Allowed clock skew when validating exp/iat claims in the token.
    |
    */
    'clock_skew' => 120,
];
