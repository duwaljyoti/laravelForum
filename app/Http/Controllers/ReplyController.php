<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Thread;

class ReplyController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store($channel, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

    	$thread->addReply([
    		'body' => request('body'),
    		'user_id' => auth()->id(),
    	]);


    	return back()
            ->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply)
    {
        // if(auth()->id() != $reply->user_id) {

        //     return response([], 403);
        // }

        $this->authorize('update', $reply);

        $reply->delete();

        return back()
            ->with('flash', 'Your Reply has been deleted.');
    }
}
