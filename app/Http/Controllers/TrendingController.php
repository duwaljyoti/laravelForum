<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class TrendingController extends Controller
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function cacheKey()
    {
//        app()->environment('testing');
        return  app()->env === 'development' ? 'trending_threads' : 'testing_trending_threads';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
