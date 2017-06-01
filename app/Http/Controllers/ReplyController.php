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
}
