<?php

namespace App\Http\Controllers;

use App\Http\Requests\Events\StoreRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    public function index()
    {
        $events = Event::all()->map(function ($booking) {
            return [
                'id'    => $booking->id,
                'name' => $booking->name,
                'start' => $booking->start_date,
                'end'   => $booking->end_date,
            ];
        });
        return view('events.index', ['events' => $events]);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $event = $this->eventService->createLocalEvent($validatedData);

        $googleCalendarEvent = $this->eventService->createGoogleCalendarEvent($event);

        $this->eventService->updateLocalEventWithGoogleCalendarId($event, $googleCalendarEvent->id);

        return response()->json([
            'id' => $event->id,
            'start' => $event->start_date,
            'end' => $event->end_date,
            'name' => $event->title,
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
