<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function count()
    {
        return Redis::get($this->getKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->getkey());
    }

    public function getKey()
    {
        return "threads.{$this->thread->id}.visits";
    }

    public function reset()
    {
        Redis::del($this->getKey());
    }
}