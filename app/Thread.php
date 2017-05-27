<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Acivity;

class Thread extends Model
{
    use Traits\RecordActivity;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected static function boot() 
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
    	return $this->hasMany('App\Reply')
            ->with('owner');
    }

    public function creator()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
