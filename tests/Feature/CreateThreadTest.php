<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;

class CreateThreadTest extends TestCase
{
	use DatabaseMigrations;

    public function testAnAuthenticatedUserShouldBeRedirectedIfTriedToAccessTheThreadCreateForm()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    public function testAGuestUserMayCreateThreadTest()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());

    }

    public function testAnAuthenticatedUserMustVerifyEmailBeforePublishingThread()
    {
        $this->publishThread()
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }


    public function testAnAuthenticatedUserCanCreateThread()
    {

        //Given we have an authenticated user
    	$this->be(factory('App\User')->create(['confirmed' => true]));

    	$this->signIn();

    	$thread = make('App\Thread');

    	//when he/she hits to the endpoint
    	$response = $this->post('/threads', $thread->toArray());

    	$this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
    		->assertSee($thread->body);
    }

    public function testAnAuthenticatedButUnConfirmedUserShouldBeRedirectedToThreadListingPageOnTryingToStoreThreads()
    {
        $this->publishThread()
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');

//        $this->signIn();
//        $thread = create(Thread::class);
//        create(User::class, [
//            'confirmed' => 1
//        ]);
//        $this->expectException(\Exception::class);
//        $this->post('/threads', $thread->toArray());
    }

    public function testAnAuthenticatedAndConfirmedUserCanCreateThread()
    {
        $user = create(User::class, ['confirmed' => 1]);
        $this->signIn($user);
        $thread = make(Thread::class);

        $response = $this->post('/threads', $thread->toArray());
        $this->assertDatabaseHas('threads', ['title' => $thread->title]);

        $this->get($response->headers->get('location'))
            ->assertSee($thread->title);
    }

    public function testATitleForThreadCannotBeEmpty()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function testABodyForThreadCannotBeEmpty()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function testAThreadShouldHaveAValidChannel()
    {

        $channel = factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 888])
            ->assertSessionHasErrors('channel_id');            
    }   


    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('threads', $thread->toArray());
    }

    public function testAnAuthorizedUserDeleteThread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $threadReply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $threadReply->id]);

        $this->assertEquals(0, Activity::count());

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $thread->id,
        //     'subject_type' => get_class($thread),
        // ]);
        
        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $threadReply->id,
        //     'subject_type' => get_class($threadReply),
        // ]);        
    }

    public function testAnAuthenticatedUserMayNotDeleteThread()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');

        $threadReply = create('App\Reply', ['thread_id' => $thread->id]);

        // $this->json('DELETE', $thread->path())
        //     ->assertStatus(401);

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        if($thread->user_id != auth()->id()) {

            $this->delete($thread->path())
                ->assertStatus(403);            
        }
    }

    public function testAThreadMayOnlyBeDeletedByThoseWhoHavePermission()
    {
        
    }
}
