<?php

namespace App\Observers;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class EventObserver
{
    protected $key = 'events';
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
       $this->clearEventCache();
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
      $this->clearEventCache();
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        $this->clearEventCache();
    }
    protected function clearEventCache(): void
    {
        if (Cache::has($this->key)){
            Cache::forget($this->key);
        }
    }

}
