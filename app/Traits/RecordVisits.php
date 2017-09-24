<?php

namespace App\Traits;


use Illuminate\Support\Facades\Redis;

trait RecordVisits {

    public function recordsVisit()
    {
        Redis::incr($this->getThreadVisitkey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->getThreadVisitkey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->getThreadVisitkey());

        return $this;

    }

    private function getThreadVisitkey()
    {
        return "threads.{$this->id}.visits";
    }
}