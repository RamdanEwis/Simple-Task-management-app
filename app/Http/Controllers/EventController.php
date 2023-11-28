<?php

namespace App\Http\Controllers;

use App\Http\Requests\Events\StoreRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use App\Services\EventService;
use App\Services\GoogleService;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Illuminate\Support\Facades\Cache;
class EventController extends Controller
{
    protected $eventService;
    protected $googleService;
    private $calendar_id ;

    public function __construct(EventService $eventService, GoogleService $googleService)
    {
        $this->eventService = $eventService;
        $this->googleService = $googleService;
        $this->calendar_id = config('app.google_calendar_id');
    }
    public function index()
    {
        $events = Cache::remember('events', 60, function () {
            $optParams = [
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
            ];
            \Log::info('Cache miss: fetching from database');
            return $this->googleService->listEvents($this->calendar_id, $optParams);
        });
        return view('events.index', ['events' => $events]);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
        $event = $this->eventService->createLocalEvent($validatedData);

        $eventData = new \Google_Service_Calendar_Event([
            'summary' => $event->summary,
            'location' => '',
            'description' => $event->description,
            'start' => [
                'dateTime' => $event->start_date,
                'timeZone' => 'America/Los_Angeles',
            ],
            'end' => [
                'dateTime' => $event->end_date,
                'timeZone' => 'America/Los_Angeles',
            ],
        ]);
        $googleCalendarEvent = $this->googleService->insertEvent($this->calendar_id, $eventData);

        return response()->json([
            'id' => $event->id,
            'start' => $event->start_date,
            'end' => $event->end_date,
            'summary' => $event->summary,
            'description' => $event->description,
            'event_id' => $googleCalendarEvent->id,
        ]);
    }
    public function update(UpdateRequest $request, $id)
    {
        $event = $this->eventService->findEventById($id);

        if (!$event) {
            return response()->json(['error' => 'Unable to locate the event'], 404);
        }

        $this->eventService->updateEvent($event, $request->start_date, $request->end_date);

        return response()->json('Event updated');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted']);
    }
}
