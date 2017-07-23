<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;

class ReplyController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index']]);
	}

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    public function store($channel, Thread $thread, Spam $spam)
    {
        $this->validateReply();

    	$reply = $thread->addReply([
    		'body' => request('body'),
    		'user_id' => auth()->id(),
    	]);

        if(request()->expectsJson()) {
            return $reply->load('owner');
        }


    	return back()
            ->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()) {
            return response(['status' => 200]);
        }

        return back()
            ->with('flash', 'Your Reply has been deleted.');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validateReply();

        $reply->update(['body' => request('body')]);
    }

    public function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));
    }

}
