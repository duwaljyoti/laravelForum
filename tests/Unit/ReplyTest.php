<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Reply;

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
        $reply = new Reply([
            'body' => 'oi @hima hey had been missing you .. @jyoti'
        ]);

        $this->assertEquals(['hima', 'jyoti'], $reply->mentionedUsers());
    }

    public function testItShouldWrapAnchorTagsForMentionedUsers()
    {
        $reply = new Reply([
            'body' => "oi hey had been missing you @hima."
        ]);

        $this->assertEquals(
            'oi hey had been missing you <a href="/profile/hima">@hima</a>.',
            $reply->body
        );
    }
}
