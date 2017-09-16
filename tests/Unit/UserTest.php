<?php

namespace App\Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldGiveTheLatestPostOfTheUser()
    {
        $user = create('App\User');
        $reply = create('App\Reply', [ 'user_id' => $user->id ]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    public function testItShouldReturnDefaultImageIfNoAvatarIsUnavailable()
    {
        $user = create(User::class);

        $this->assertEquals('storage/avatars/default.jpg', $user->avatar());

        $user->avatar_path = 'testimage.jpg';
        $this->assertEquals('testimage.jpg', $user->avatar());
    }
}