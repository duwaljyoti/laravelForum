<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadTest extends TestCase
{
	use DatabaseMigrations;


	public function setUp()
	{
		parent::setUp();
		$this->thread = factory('App\Thread')->create();;
	}

    public function testAThread()
    {
        $thread = create('App\Thread');

        // dd('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread);
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    public function testAThreadHasReplies()
    {
    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function testAThreadHasUser()
    {
    	$this->assertInstanceOf('App\User', $this->thread->creator);
    }

    public function testAThreadShouldBelongToAChannel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    public function testAUserCanFilterOnlyHisThread()
    {
        $this->signIn(create('App\User'), ['name' => 'JohnDoe']);

        $threadOfJohnDoe = create('App\Thread', ['user_id' => auth()->id()]);
        $otherThread = create('App\Thread');

        $this->get('threads?by=' . auth()->user()->name)
            ->assertSee($threadOfJohnDoe->title)
            ->assertDontSee($otherThread->title);
    }

    public function testAnUserCanFilterThreadsWithPopularity()
    {
        //Given we have three threads
        //first has one reply
        //second has two replies 
        //third has three replies
        //the third should display first

        $threadwithNoReply = $this->thread;

        $threadwithFourReply = create('App\Thread');
        $secondThreadReply = create('App\Reply', ['thread_id' => $threadwithFourReply->id], 4);

        $threadWithThreeReply = create('App\Thread');
        $secondThreadReply = create('App\Reply', ['thread_id' => $threadWithThreeReply->id], 3);

        $response = $this->getJson('/threads?popular=true')->json();

        $this->assertEquals([4, 3, 0], array_column($response, 'replies_count'));
    }

    public function testItShouldReturnReplyOfThreads()
    {
        $thread = create('App\Thread');

        $threadReplies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $threadReplyResponse = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(1, $threadReplyResponse['data']);
        $this->assertEquals(2, $threadReplyResponse['total']);
    }
}
