<?php

namespace App\Listeners;

use App\Events\ThreadReceivedANewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedANewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedANewReply $event)
    {
        $event->reply->thread
            ->subscription
            ->where('user_id', '!=', $event->reply->user_id)
            ->each->notify($event->reply);
    }
}
