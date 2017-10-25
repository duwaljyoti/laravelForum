<?php

namespace App\Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ThreadLockTest extends TestCase
{
    use DatabaseMigrations;

    public function testOnlyAdministratorsCanLockTheThread()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());
        $thread = create(Thread::class);
        $this->assertFalse($thread->locked);
        $this->post(route('thread.lock', $thread));
        $this->assertTrue(!!$thread->fresh()->locked);
    }

    public function testOrdinaryUsersCanNotLockTheThread()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create(Thread::class);
        $this->post(route('thread.lock', $thread))->assertStatus(403);
        $this->assertFalse(!!$thread->fresh()->locked);
    }

    public function testALockedThreadDoesNotAcceptReplies()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $thread->toggleLock();
        $this->post($thread->path() . '/replies', [
            'body' => 'just a test reply body',
            'user_id' => auth()->id(),
        ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testAnAdministratorCanUnlockLockedThread()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());
        $thread = create(Thread::class, ['locked' => true]);
        $this->delete("/threads/{$thread->slug}/unlock");
        $this->assertFalse($thread->fresh()->locked);
    }
}