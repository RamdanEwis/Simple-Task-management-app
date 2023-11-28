<?php

namespace App\Http\Controllers\API;

use App\Constants\Status_Responses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Http\Resources\Events\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;


class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $events = Cache::remember('events', 60, function () {
            \Log::info('Cache miss: fetching from database');
            return Event::all();
        });
        return ok_response(EventResource::collection($events) );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {

        $validatedData = $request->validated();

        $event = $this->eventService->createLocalEvent($validatedData);

        $googleCalendarEvent = $this->eventService->createGoogleCalendarEvent($event);

        $this->eventService->updateLocalEventWithGoogleCalendarId($event, $googleCalendarEvent->id);

        return created_response($event);
    }
        /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return Response
     */
    public function show(Event $event)
    {
       return  ok_response(new EventResource($event));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Event $event
     * @return Response
     */
    public function update(UpdateRequest $request, Event $event)
    {
        $event = $this->eventService->findEventById($event);

        $this->eventService->updateEvent($event, $request->start_date, $request->end_date);
        return ok_response(new EventResource($event),'Event Is Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return ok_response([],'Event Is Deleted Successfully');
    }
}
