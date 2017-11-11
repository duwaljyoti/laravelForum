<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function testAUserShouldBeAbleToSearchThreads()
    {
        config(['scout.driver' => 'algolia']);
        $searchQuery = 'justARandomStringForTest';

        create(Thread::class, [], 1)->make();
        create(Thread::class, ['title' => $searchQuery], 2)->make();

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->getJson("/threads/search?query={$searchQuery}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        // Clean up.
        Thread::latest()->take(4)->unsearchable();
    }
}
