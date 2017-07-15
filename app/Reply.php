<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Thread;
use App\Favourite;
use App\Traits\Favouritable;
use App\Traits\RecordActivity;

class Reply extends Model
{
    use Favouritable, RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    protected $appends = ['favourites_count', 'isFavourited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function($reply) {
            $reply->thread->fresh()->increment('reply_count');
        });

        static::deleted(function($reply) {
            $reply->thread->fresh()->decrement('reply_count');
        });
    }
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
    	return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

}
