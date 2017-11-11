<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Request $request, TrendingController $trending)
    {
        $threads = Thread::search($request->query('query'))->paginate();

        if ($request->expectsJson()) {
            return $threads;
        }
        $trendingThreads = $trending->get();

        return view('threads.index', compact('threads', 'trendingThreads'));

    }
}
