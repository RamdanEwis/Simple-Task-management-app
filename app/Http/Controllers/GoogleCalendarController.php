<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarController extends Controller
{
    private $googleClient;

    public function __construct()
    {
        $this->googleClient = $this->setupGoogleClient();
    }

    private function setupGoogleClient()
    {
        $client = new Google_Client();
        $client->setApplicationName("Web client 2");
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setScopes(Google_Service_Calendar::CALENDAR);

        return $client;
    }

    public function authenticate()
    {
        return redirect($this->googleClient->createAuthUrl());
    }

    public function callback(Request $request)
    {
        if ($request->has('code')) {
            $this->googleClient->authenticate($request->input('code'));
            $request->session()->put('google_token', $this->googleClient->getAccessToken());
            $calendarId = '17c2d81a082e153e96955591adc7d20fdb1018dd0911adc0835be4e00ec4b880@group.calendar.google.com';
            $optParams = [
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
            ];
            $service = new Google_Service_Calendar($this->googleClient);
            $events = $service->events->listEvents($calendarId, $optParams)->getItems();
            return view('events.index', compact('events'));
        }

        return redirect()->route('calendar.list');
    }

    public function listEvents()
    {
        try {
            $service = new Google_Service_Calendar($this->googleClient);
            $calendarId = 'devramdanewis@gmail.com';
            $optParams = [
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
            ];
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = $results->getItems();
            return view('events.index2', compact('events'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function store()
    {
        $calender_id = 'your_calendar_id'; // Replace with your calendar ID
        $service = new Google_Service_Calendar($this->googleClient);
        $event_data = new Google_Service_Calendar_Event([
            'summary' => 'Google I/O 2015',
            'location' => '808 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => [
                'dateTime' => '2023-11-28T09:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ],
            'end' => [
                'dateTime' => '2023-12-08T17:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ],
        ]);
        $event = $service->events->insert($calender_id, $event_data, ['conferenceDataVersion' => 1]);
    }
}
