<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Acivity;

class Thread extends Model
{
    use Traits\RecordActivity;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribed'];

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
        $reply = $this->replies()->create($reply);
        $this->notifyUsers($reply);

        return $reply;

    }

    public function notifyUsers($reply)
    {
        $this->subscription
            ->where('user_id', '!=', $reply->user_id)
//            ->filter(function($subscription) use ($reply) {
//            return  $subscription->user_id != $reply->user_id;
//        })
            ->each->notify($reply);


//            ->each(function($subscription) use ($reply) {
//                dd($subscription);
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            });

//        foreach($this->subscription as $subscription) {
//            if($subscription->user_id != $reply->id) {
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            }
//        }

        //  alternate way of adding a reply count.
        //  $this->increment('reply_count');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $subscription =  $this->subscription()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function subscription()
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    public function unSubscribe($userId = null)
    {
        $userId = auth()->id() ?: $userId;

        return $this->subscription()->delete([ 'user_id' => $userId ]);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscription()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user)
    {
        // looking for some kind of cache:key
            $key = $user->visitedThreadCacheKey($this);

        //compare it with the data we have saved in cache.
        return $this->updated_at > cache($key);
    }
}
