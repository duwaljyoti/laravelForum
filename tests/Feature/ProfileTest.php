<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    public function testAUserHasAProfile()
    {
        $user = create('App\User');

        $response = $this->get("/profile/{$user->name}")
            ->assertSee($user->name);
    }

    public function testProfileShouldDisplayAllThreadsAssociatedToAUser()
    {
    	$this->signIn();

    	$threadsOfAssociatedUser = create('App\Thread', ['user_id' => auth()->id()]);

    	$threadsOfOtherUser =  create('App\Thread');

    	$this->get("/profile/" . \Auth::user()->name)
    		->assertSee($threadsOfAssociatedUser->title);
    }
}
