<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
	use DatabaseMigrations;

     
    public function testItShouldRecordWhenAThreadIsCreated()
    {
    	$this->signIn();

    	$thread = create('App\Thread');

    	$this->assertDatabaseHas('activities', [
    		'type'  => 'thread_created',
    		'user_id' => auth()->id(),
    		'subject_id' => $thread->id,
    		'subject_type' => 'App\Thread'
    	]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function testItShouldRecordWhenAReplyIsCreated()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());

    }

    public function testItShouldFetchActivityFeed()
    {
        $this->signIn();

        $threads = create('App\Thread',  ['user_id' => auth()->id()], 2);

        auth()->user()->activities->first->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertContains(Carbon::now()->format('Y-m-d'), $feed->keys());
        $this->assertContains(Carbon::now()->subWeek()->format('Y-m-d'), $feed->keys());
    }
}
