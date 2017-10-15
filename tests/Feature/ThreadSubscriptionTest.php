<?php

namespace Test\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldSubscribeThread()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->post("{$thread->path()}/subscribe");

        $this->assertTrue($thread->isSubscribed);
    }

    public function testItShouldUnSubscribeThreads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post('/threads/' . $thread->channel->id . '/' . $thread->slug . '/subscribe');
        $this->assertTrue($thread->isSubscribed);
        $this->delete('/threads/' . $thread->channel->id . '/' . $thread->slug . '/subscribe');
        $this->assertFalse($thread->isSubscribed);

    }
}