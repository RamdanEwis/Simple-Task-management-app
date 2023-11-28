{{-- events.index.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Events</h1>

    @if(count($events) > 0)
        <ul>
            @foreach($events as $event)
                <li>
                    <strong>{{ $event->getSummary() }}</strong>
                    <br>
                    {{ $event->getDescription() }}
                    <br>
                    Start Time: {{ $event->start->dateTime ?? $event->start->date }}
                    <br>
                    End Time: {{ $event->end->dateTime ?? $event->end->date }}
                </li>
            @endforeach
        </ul>
    @else
        <p>No events found.</p>
    @endif
@endsection
