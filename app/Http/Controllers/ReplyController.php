<?php

namespace App\Http\Controllers;

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

    public function store($channel, Thread $thread)
    {
        try {
            $this->validate(request(), [
                'body' => 'required|spamFree'
            ]);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Spam detected one more time.', 422);
        }
        return $reply->load('owner');
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

        try {
            $this->validate(request(), [
                'body' => 'required|spamFree'
            ]);

            $reply->update(['body' => request('body')]);
        } catch (\Exception $e) {

            return response('Your Reply seem to have some Spam!', 422);
        }
    }
}
