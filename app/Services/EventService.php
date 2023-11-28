<?php
namespace App\Services;
use App\Models\Event;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;

class EventService
{
public function createLocalEvent(array $data)
{
return Event::create(array_merge($data, ['user_id' => auth()->id()]));
}

public function createGoogleCalendarEvent(Event $event)
{
$googleCalendarEvent = new GoogleCalendarEvent();
$googleCalendarEvent->name = $event->title;
$googleCalendarEvent->description = $event->description;
$googleCalendarEvent->startDateTime = $event->start_date;
$googleCalendarEvent->endDateTime = $event->end_date;
$googleCalendarEvent->addMeetLink();
$googleCalendarEvent->save();

return $googleCalendarEvent;
}

public function updateLocalEventWithGoogleCalendarId(Event $event, $googleCalendarEventId)
{
$event->update(['event_id' => $googleCalendarEventId]);
}

    public function findEventById($id)
    {
        return Event::find($id);
    }

    public function updateEvent(Event $event, $startDate, $endDate)
    {
        $event->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $googleCalendarEvent = GoogleCalendarEvent::find($event->event_id);
        $googleCalendarEvent
            ->startDateTime($startDate)
            ->endDateTime($endDate)
            ->save();
    }
}
