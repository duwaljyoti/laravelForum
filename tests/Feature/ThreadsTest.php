<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    public function testAUserCanViewAllTheThreads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function testAUserCanViewSingleThread()
    {
        $this->get('/threads/test-channel/' . $this->thread->id)
            ->assertSee($this->thread->title);
    }

    public function testAUserCanReplyToAThread()
    {
        $this->thread->addReply([
            'body' => 'some data',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
        $this->assertEquals(1, $this->thread->fresh()->reply_count);

    }

    public function testAUserCanFilterThreadAccordingToTheTagOrChannel()
    {
        $channel = create('App\Channel');
        $threadOfChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadOfAnotherChannel = create('App\Thread');

        $this->get('threads/' . $channel->slug)
            ->assertSee($threadOfChannel->title)
            ->assertDontSee($threadOfAnotherChannel->title);
    }

    public function testAThreadCanBeSubscribed()
    {
        // given we have a signed in user and a thread
//        $user = $this->signIn();

        //if he subscribes a thread
        $this->thread->subscribe($userId = 1);

        //all the subscribed threads should be displayed
        $this->assertEquals(1, $this->thread->subscription->where('user_id', $userId)->count());


    }

    public function testAThreadCanBeUnSubscribed()
    {
        $this->thread->subscribe($userId = 12);

        $this->thread->unSubscribe();

        $this->assertEquals(0,
            $this->thread->subscription->where('user_id', $userId )->count());
    }
}
