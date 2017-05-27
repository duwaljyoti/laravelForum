<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Thread;
use App\Favourite;
use App\Traits\Favouritable;

class Reply extends Model
{
    use Favouritable, Traits\RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
    	return $this->belongsTo(Thread::class);
    }

}
