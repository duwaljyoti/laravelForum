<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateinForumTest extends TestCase
{
	use DatabaseMigrations;

	public function testAnUthenticatedUserMayNotParticipateInForum()
	{
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('login');
	}

    public function testAnAuthenticatedUserMayParticipateInForum()
    {
        //Given there is an authenticated user
    	$this->signIn();

        //Given there is a thread
        $thread = create('App\Thread');

        //Given the user replies to one of the thread
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());
        // $this->assertInstanceOf('App\User', $reply->owner);


        //The rely should be visible in the threads
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    public function testAThreadSHouldContainABody()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }

    // public function testAReplyCanBeDeleted()
    // {
    //     $this->signIn();
    //     $reply = create('App\Reply');

    //     $this->json('DELETE', "/replies/{$reply->id}/delete");

    //     $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    // }    

    public function testAnUnaAuthenticatedUserMayNotDeleteReply()
    {

        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function testAnAuthenticatedUserCanDeleteReply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
