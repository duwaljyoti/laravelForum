<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->signIn();
    }

    public function testAValidTitleAndBodyIsRequiredToUpdateTheThread()
    {
        $threadNewTitle = 'A new Title';
        $thread = create(Thread::class, ['user_id' => auth()->id()], 1)->first();
        $this->put($thread->path(), [
            'title' => $threadNewTitle,
        ])->assertSessionHasErrors('body');
    }

    public function testACreatorCanUpdateThread()
    {
        $threadNewTitle = 'A new Title';
        $threadNewBody = 'A New Body';
        $thread = create(Thread::class, ['user_id' => auth()->id()], 1)->first();
        $this->put($thread->path(), [
            'title' => $threadNewTitle,
            'body' => $threadNewBody
        ]);

        tap($thread->fresh(), function($thread) use ($threadNewBody, $threadNewTitle) {
            $this->assertEquals($thread->title, $threadNewTitle);
            $this->assertEquals($thread->body, $threadNewBody);
        });
    }

    public function testOnlyOwnerCanUpdateThread()
    {
//        $this->expectException(AuthorizationException::class);
        $thread = create(Thread::class, ['user_id' => 12]);

        $this->put($thread->path(), [
            'title' => 'some title',
            'body' => 'some random body'
        ])->assertStatus(403);
    }
}
