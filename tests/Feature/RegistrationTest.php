<?php

namespace App\Tests\Feature;

use App\Mail\PleaseConfirmYourEmailAddress;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase{
    use DatabaseMigrations;

    public function testAConfirmationEmailIsSentUponRegistration()
    {
        // fake that an email has been sent
        Mail::fake();

        $user = create(User::class);
        // upon creation of an user this event is fired.
        event(new Registered($user));

        Mail::assertSent(PleaseConfirmYourEmailAddress::class);
    }

    public function testAUserShouldBeAbleToFullyConfirmTheEmail()
    {
        $this->post('/register', [
            'name' => 'jyotiDummyUser',
            'email' => 'testjay12@gmail.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword'
        ]);

        $user = User::whereName('jyotiDummyUser')->first();
        $this->assertFalse($user->confirmed);

        $this->get("/register/confirm?token={$user->confirmation_token}")
            ->assertRedirect('/threads');

        $this->assertTrue($user->fresh()->confirmed);
    }
}