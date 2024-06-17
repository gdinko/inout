<?php

/*
 * You can place your custom package configuration in here.
 */

return [

    /**
     * Configure Inout env (test|production)
     */
    'env' => env('INOUT_ENV', 'test'),

    /**
     * Set Inout API Token
     */
    'api_token' => env('INOUT_API_TOKEN', 'test-api-token'),

    /**
     * Default Inout test base url
     */
    'test_base_url' => rtrim(env('INOUT_API_TEST_BASE_URI', 'https://test-api.inout.bg/api/v1'), '/'),

    /**
     * Default Inout production base url
     */
    'production_base_url' => rtrim(env('INOUT_API_PRODUCTION_BASE_URI', 'https://api1.inout.bg/api/v1'), '/'),

    /**
     * Set Inout Company ID
     */
    'company_id' => env('INOUT_COMPANY_ID', 0),

    /**
     * Set Request timeout
     */
    'timeout' => env('INOUT_API_TIMEOUT', 5),

    /**
     * Set Inout Test Mode
     */
    'test_mode' => env('INOUT_TEST_MODE', 1),
];
