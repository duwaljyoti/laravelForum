<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItHasOwner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    public function testItShouldDetectIfTheReplyWasJustCreated()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
    }

    public function testItShouldReturnBackListOfMentionedPeople()
    {
        $reply = create('App\Reply', [
            'body' => 'oi @hima hey had been missing you .. @jyoti'
        ]);

        $this->assertEquals(['hima', 'jyoti'], $reply->mentionedUsers());
    }
}
