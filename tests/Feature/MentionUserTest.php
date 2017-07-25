<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUserTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldNotifyTheUserWhoIsMentioned()
    {
        $this->withExceptionHandling();
        // we have two users.. jyoti and jane and we have a thread
        $jyoti = create('App\User', ['name' => 'jyoti']);

        $this->signIn($jyoti);

        $hima = create('App\User', ['name' => 'hima']);

        // given we have a thread
        $thread = create('App\Thread');

        // and when jyoti mentions hima in any thread.
        $reply = make('App\Reply', [
            'user_id' => $jyoti->id,
            'body'  => "hey do you have any idea on this.. @hima "
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        dump($hima->id);
        //she should be notified
        $this->assertCount(1, $hima->notifications);
    }
}
