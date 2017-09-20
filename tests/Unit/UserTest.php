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

        $this->assertEquals(asset('storage/avatars/default.jpg'), $user->avatar_path);

        $user->avatar_path = 'testimage.jpg';
        $this->assertEquals(asset('storage/testimage.jpg'), $user->avatar_path);
    }
}