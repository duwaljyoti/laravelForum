<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadSubscriptionController extends Controller
{
    public function subscribe($channelId, Thread $thread)
    {
        return $thread->subscribe(auth()->id());
    }

    public function destroy($channel, Thread $thread)
    {
        return $thread->unSubscribe();
    }
}