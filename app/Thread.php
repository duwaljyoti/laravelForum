<?php

namespace App;

use App\Events\ThreadReceivedANewReply;
use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribed'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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
        event(new ThreadReceivedANewReply($reply));

        return $reply;

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
        $subscription = $this->subscription()->create([
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

        return $this->subscription()->delete(['user_id' => $userId]);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscription()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function hasUpdatesFor($user)
    {
        // looking for some kind of cache:key
        $key = $user->visitedThreadCacheKey($this);

        //compare it with the data we have saved in cache.
        return $this->updated_at > cache($key);
    }

    // confusing part
    public function setSlugAttribute($value)
    {
        // we check if we already have a slug with the value we receive
        // if we have it we will increment it
        // or else we will just return the slug.

        if (static::where('slug', $slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    private function incrementSlug($slug)
    {
        // we will now get the the model with the latest id since it was last created and get the slug..
        $max = static::whereTitle($this->title)->latest('id')->value('slug');

        // we check if the number we recieved is a number if it is we will try to increase it.
        // if its not a number we will simply append 2 to it.
        if (is_numeric(substr($max, -1))) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $max);
        }

        return "{$slug}-2";
    }
}
