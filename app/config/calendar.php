<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google API Oauth2 authentication information
    |--------------------------------------------------------------------------
    | Authentication information for the Google Calendar API to read and write
    | data.
    */

    'google_api_application_name'              => env('CALENDAR_GOOGLE_API_APPLICATION_NAME', 'Food Manager Web APP'),
    'google_api_client_id'                     => env('CALENDAR_GOOGLE_API_CLIENT_ID'),
    'google_api_client_secret'                 => env('CALENDAR_GOOGLE_API_CLIENT_SECRET'),
    'google_api_project_id'                    => env('CALENDAR_GOOGLE_API_PROJECT_ID', 'food-manager-320920'),
    'google_api_auth_uri'                      => env('CALENDAR_GOOGLE_API_AUTH_URI', 'https=//accounts.google.com/o/oauth2/auth'),
    'google_api_token_uri'                     => env('CALENDAR_GOOGLE_API_TOKEN_URI', 'https=//oauth2.googleapis.com/token'),
    'google_api_auth_provider_x509_cert_url'   => env('CALENDAR_GOOGLE_API_AUTH_PROVIDER_X509_CERT_URL', 'https=//www.googleapis.com/oauth2/v1/certs'),
    'google_api_redirect_uri'                  => env('CALENDAR_GOOGLE_API_REDIRECT_PATH', '/settings/calendar/auth')
];
