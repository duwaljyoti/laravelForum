<?php

namespace App\Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    public function testAReplyCanBeMarkedAsBest()
    {
        $user = create(User::class);
        $this->signIn($user);
        $thread = create(Thread::class, ['user_id' => $user->id]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-reply.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    public function testOnlyAuthorOfTheThreadCanFavouriteReply()
    {
        $this->withExceptionHandling();
        $user = create(User::class);
        $this->signIn($user);
        $threadFromAnotherUser = create(Thread::class);
        $aReplyFromAnotherUser = create(Reply::class, ['user_id' => $threadFromAnotherUser]);
        $this->postJson(route('best-reply.store', [$aReplyFromAnotherUser->id]))->assertStatus(403);
        $this->assertFalse($aReplyFromAnotherUser->isBest());

        $thread2 = create(Thread::class, ['user_id' => $user->id]);
        $reply = create(Reply::class, ['thread_id' => $thread2->id]);
        $this->postJson(route('best-reply.store', [$reply->id]));
        $this->assertTrue($reply->fresh()->isBest());
    }
}