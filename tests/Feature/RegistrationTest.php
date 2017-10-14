<?php

namespace App\Tests\Feature;

use App\Mail\PleaseConfirmYourEmailAddress;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase{
    use DatabaseMigrations;

    private function createUserByHittingEndPoint()
    {
        $this->post(route('register'), [
            'name' => 'jyotiDummyUser',
            'email' => 'testjay12@gmail.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword'
        ]);
    }

    public function testAConfirmationEmailIsSentUponRegistration()
    {
        // fake that an email has been sent
        Mail::fake();

        $this->createUserByHittingEndPoint();

        Mail::assertSent(PleaseConfirmYourEmailAddress::class);
    }

    public function testAUserShouldBeAbleToFullyConfirmTheEmail()
    {
        Mail::fake();

        $this->createUserByHittingEndPoint();

        $user = User::whereName('jyotiDummyUser')->first();
        $this->assertFalse($user->confirmed);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        $this->assertTrue($user->fresh()->confirmed);
    }

    public function testUponUsingInvalidTokenAnExceptionShouldBeThrown()
    {
        $this->withExceptionHandling();

        $this->get(route('register.confirm', ['token' => 1223232323232232323232]))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Invalid Token');
    }
}