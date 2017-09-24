<?php

namespace Tests\Feature;

use App\Http\Controllers\TrendingController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $trending;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp()
    {
        parent::setUp();
        $this->trending = new TrendingController();
        $this->trending->reset();
    }

    public function testThreadsWithMostViewMustAppearInTheThreadsListingPage()
    {
        $thread = create('App\Thread');

        $this->assertEmpty($this->trending->get());

        $this->trending->reset();

        $this->get($thread->path());

        $this->assertCount(1, $trendingThreads = $this->trending->get());

        $this->assertEquals($thread->title, $this->trending->get()[0]->title);
    }
}
