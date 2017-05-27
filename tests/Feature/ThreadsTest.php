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

    public function testAUserCanSeeTheRepliesOfAThreadIfItHasOne()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/test-channel/' . $this->thread->id);

        $response->assertSee($reply->body);

    }

    public function testAUserCanReplyToAThread()
    {
        $this->thread->addReply([
            'body' => 'some data',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function testAUserCanFilterThreadAccordingToTheTagOrChannel()
    {
        $channel = create('App\Channel');
        $threadOfChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadOfAnotherChannel = create('App\Thread');

        $response = $this->get('threads/' . $channel->slug)
            ->assertSee($threadOfChannel->title)
            ->assertDontSee($threadOfAnotherChannel->title);
    }

}
