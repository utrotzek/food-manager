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
        $client = new Google_Client([
            'application_name'              => 'Food Manager Web APP',
            'client_id'                     => '916946199150-0q3kmvl725i8fnnjk475s7hbajat33g3.apps.googleusercontent.com',
            'project_id'                    => 'food-manager-320920',
            'auth_uri'                      => 'https=>//accounts.google.com/o/oauth2/auth',
            'token_uri'                     => 'https=>//oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url'   => 'https=>//www.googleapis.com/oauth2/v1/certs',
            'client_secret'                 => 'Vw72ej-IY_rzjbQrVR3vgsnC',
            'redirect_uri'                  => 'https://food-manager.local.de/calendar/auth'
        ]);
        $client->setApplicationName('Google Calendar API PHP Quickstart');
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
        $client = new Google_Client([
            'application_name'              => 'Food Manager Web APP',
            'client_id'                     => '916946199150-0q3kmvl725i8fnnjk475s7hbajat33g3.apps.googleusercontent.com',
            'project_id'                    => 'food-manager-320920',
            'auth_uri'                      => 'https=>//accounts.google.com/o/oauth2/auth',
            'token_uri'                     => 'https=>//oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url'   => 'https=>//www.googleapis.com/oauth2/v1/certs',
            'client_secret'                 => 'Vw72ej-IY_rzjbQrVR3vgsnC',
            'redirect_uri'                  => 'https://food-manager.local.de/calendar/auth'
        ]);
        $client->setApplicationName('Google Calendar API PHP Quickstart');
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
            'token' => $accessToken
        ]);
    }
}
