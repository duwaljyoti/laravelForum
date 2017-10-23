<?php

namespace App\Http\Controllers;

use App\Thread;

class LockThreadController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
