<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;
use App\Filters\ThreadFilters;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'create', 'destroy']);
    }

    /**
     * @param $channel
     * @param $filters
     * @return mixed
     */
    public function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // Filters down according to the filter and get me the result
        $threads = $threads->paginate(5);

        return $threads;        
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     *
     * @param TrendingController $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, TrendingController $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()) {
            return $threads;
        }

        $trendingThreads = $trending->get();

        return view('threads.index', compact('threads', 'trendingThreads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|spamFree',
            'body' => 'required|spamFree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'slug' => str_slug(request('title')),
            'body' => request('body')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Thread Created here.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, TrendingController $trending)
    {
        if(auth()->check()) {
            auth()->user()->read($thread);
        }

        $thread->increment('visits');

        $trending->push($thread);

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // if($thread->user_id != auth()->id()) {
        //     abort(403, 'You do not have correct priviliges to perform the action');
        // }

        $this->authorize('update', $thread);

        $thread->delete();


        if(request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

}
