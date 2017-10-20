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

    public function testAReplyShouldKnowIfItsTheBestReply()
    {
        $reply = create(Reply::class);
        $this->assertFalse($reply->isBest());
        $reply->thread->update(['best_reply_id' => $reply->id]);
        $this->assertTrue($reply->isBest());
    }

    public function testOnDeletionOfBestReplyItShouldNullTheBestReplyIdColumnOfThread()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $thread = $reply->thread;
        $reply->thread->markAsBestReply($reply);
        $this->assertEquals($thread->best_reply_id, $reply->id);
        $this->delete(route('reply.destroy', ['id' => $reply->id]));
        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
