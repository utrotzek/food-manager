<?php

namespace App\Http\Controllers;

use Google\Client;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoogleCalendarApiController extends Controller
{
    public function authLink(Request $request): Response
    {
        $client = new Google_Client($this->buildApiConfig());
        $client->setApplicationName(config('calendar.google_api_application_name'));
        $client->setScopes(
            [
            Google_Service_Calendar::CALENDAR_READONLY,
            Google_Service_Calendar::CALENDAR_EVENTS]
        );
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                return new Response([
                    'auth_url' => $authUrl,
                    'message' => 'Please obtain a oauth2 token by visiting the following URL',
                    200
                ]);
            }
        }

        return new Response([
            'message' => 'Access token is still valid, no need to authenticate'
        ], 204);
    }


    public function authConfirm(Request $request): Response
    {
        $client = new Google_Client($this->buildApiConfig());
        $client->setApplicationName(config('calendar.google_api_application_name'));
        $client->setScopes(
            [
                Google_Service_Calendar::CALENDAR_READONLY,
                Google_Service_Calendar::CALENDAR_EVENTS]
        );
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $code = $request->input('code');
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);

        return new Response([
            'token' => $accessToken['access_token'],
            'refresh_token' => $accessToken['refresh_token']
        ]);
    }

    /**
     * @return array
     */
    private function buildApiConfig(): array
    {
        return [
            'application_name'              => config('calendar.google_api_application_name'),
            'client_id'                     => config('calendar.google_api_client_id'),
            'client_secret'                 => config('calendar.google_api_client_secret'),
            'project_id'                    => config('calendar.google_api_project_id'),
            'auth_uri'                      => config('calendar.google_api_auth_uri'),
            'token_uri'                     => config('calendar.google_api_token_uri'),
            'auth_provider_x509_cert_url'   => config('calendar.google_api_auth_provider_x509_cert_url'),
            'redirect_uri'                  => config('app.url').config('calendar.google_api_redirect_uri')
        ];
    }
}
