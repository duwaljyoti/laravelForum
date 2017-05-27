<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouriteTest extends TestCase
{
    use DatabaseMigrations;

    public function testAGuestUserMayNotFavouriteAnything()
    {
        $this->withExceptionHandling();
        $this->post('replies/1/favourite')
            ->assertRedirect('login');
    }

    public function testAnAuthUserMayFavouriteAnyPost()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post('replies/' . $reply->id . '/favourite');

        $this->assertCount(1, $reply->favourites);
    }

    public function testAnAuthenticatedUserMayFavouriteAnyPostOnlyOnce()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        try {
            $response = $this->post('replies/' . $reply->id . '/favourite');
            $response = $this->post('replies/' . $reply->id . '/favourite');
        }
        catch(\Exception $e) {
            $this->fail('Favouriting tow times is not possible');
        }

        $this->assertCount(1, $reply->favourites);
    }
}
