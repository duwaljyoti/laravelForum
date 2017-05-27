<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favourite;

class FavouriteController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store(Reply $reply)
    {
    	 $reply->favourite();

    	 return back();
    }
}
