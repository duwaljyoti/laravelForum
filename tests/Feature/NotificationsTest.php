<?php

namespace Tests\Feature;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    public function testANotificationIsReceivedWhenANewReplyComesToAThreadFromOtherUser()
    {
        // still need to replace the repeating lines of codes..
        $user = auth()->user();
        $this->assertCount(0, $user->notifications);


        $thread = create('App\Thread')->subscribe();


        $thread->addReply([
            'user_id' => $user->id,
            'body' => 'Some body texts'
        ]);

        $this->assertCount(0, $user->fresh()->notifications);

        $anotherUser = create('App\User');

        $thread->addReply([
            'user_id' => $anotherUser->id,
            'body' => 'Some body texts'
        ]);

        $this->assertCount(1, $user->fresh()->notifications);
    }

    public function testAUserCanMarkANotificationAsRead()
    {
        $user = auth()->user();

        create(DatabaseNotification::class);

        // The above single line replaces four lines below..

//        $thread = create('App\Thread')->subscribe();
//        $thread->addReply([
//            'user_id' => create('App\User')->id,
//            'body'=> 'Body Texts'
//        ]);

        $endpoint = "profile/{$user->name}/notifications/{$user->unreadNotifications->first()->id}";

        $this->assertCount(1, $user->fresh()->unreadNotifications);

        $this->delete($endpoint);

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }

    public function testAUserCanFindUnreadNotifications()
    {
        $user = auth()->user();

        create(DatabaseNotification::class);
        $endpoint = "profile/{$user->name}/notifications";
        $response = $this->getJson($endpoint)->json();
        $this->assertCount(1, $response);
    }
}
