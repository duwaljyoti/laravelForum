<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Gate;

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

    public function store($channel, Thread $thread, CreatePostForm $createPostForm)
    {
        if ($thread->locked) {
            return response('', 403);
        }

        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');
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
        $this->validate(request(), [
            'body' => 'required|spamFree'
        ]);

        $reply->update(['body' => request('body')]);
    }
}
