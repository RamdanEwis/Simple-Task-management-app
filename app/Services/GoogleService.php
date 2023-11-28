<?php

namespace App\Services;

use App\Models\User;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleService
{
    protected $googleClient;

    public function __construct()
    {
        $this->googleClient = $this->setupGoogleClient();
    }
    public function authenticate()
    {
        return redirect($this->googleClient->createAuthUrl());
    }

    private function setupGoogleClient()
    {
        $all_scopes = implode(',', [
            \Google_Service_Calendar::CALENDAR_READONLY,
            \Google_Service_Calendar::CALENDAR,
            \Google_Service_Calendar::CALENDAR_EVENTS,
            \Google_Service_Calendar::CALENDAR_EVENTS_READONLY,
        ]);
        $client = new Google_Client();
        $client->setApplicationName(config('app.google_application_name'));
        $client->setClientId(config('app.google_client_id'));
        $client->setClientSecret(config('app.google_client_secret'));
        $client->setRedirectUri(config('app.google_redirect_uri'));
        $client->setScopes(Google_Service_Calendar::CALENDAR);

        return $client;
    }
    public function listEvents($calendarId, $optParams)
    {
        $service = new Google_Service_Calendar($this->googleClient);
        $results = $service->events->listEvents($calendarId, $optParams);
        return $results->getItems();
    }

    public function insertEvent($calendarId, Google_Service_Calendar_Event $eventData, $options = [])
    {
        $service = new Google_Service_Calendar($this->googleClient);
        return $service->events->insert($calendarId, $eventData, $options);
    }


}
