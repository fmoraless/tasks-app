<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaginateTasksTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_paginate_tasks()
    {
        $tasks = Task::factory()->count(6)->create();

        // tasks?page[size]=2&page[number]=2
        $url = route('api.v1.tasks.index', [
            'page' => [
                'size' => 2,
                'number' => 2
            ]
        ]);

        //dd(urldecode($url));
        //http://localhost/api/v1/tasks?page[size]=2&page[number]=2"

        $response = $this->getJson($url)
            ->assertSee([
                $tasks[2]->title,
                $tasks[3]->title,
            ]);
        $response->assertDontSee([
            $tasks[0]->title,
            $tasks[1]->title,
            $tasks[4]->title,
            $tasks[5]->title,
        ]);
        $response->assertJsonStructure([
            'links' => ['first', 'last','prev', 'next']
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        //dd($firstLink);
        $this->assertStringContainsString('page[size]=2', $firstLink);
        $this->assertStringContainsString('page[number]=1', $firstLink);

        $this->assertStringContainsString('page[size]=2', $lastLink);
        $this->assertStringContainsString('page[number]=3', $lastLink);

        $this->assertStringContainsString('page[size]=2', $prevLink);
        $this->assertStringContainsString('page[number]=1', $prevLink);

        $this->assertStringContainsString('page[size]=2', $nextLink);
        $this->assertStringContainsString('page[number]=3', $nextLink);
    }

    /** @test */
    public function can_paginate_filtered_articles()
    {
        Task::factory()->count(3)->create();
        Task::factory()->create(['title' => 'C laravel']);
        Task::factory()->create(['title' => 'A laravel']);
        Task::factory()->create(['title' => 'B laravel']);

        // tasks?filter[title]=laravel&page[size]=2&page[number]=2
        $url = route('api.v1.tasks.index', [
            'filter[title]' => 'laravel',
            'page' => [
                'size' => 1,
                'number' => 2
            ]
        ]);

        //dd(urldecode($url));

        $response = $this->getJson($url);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        //dd($firstLink);
        $this->assertStringContainsString('filter[title]=laravel', $firstLink);
        $this->assertStringContainsString('filter[title]=laravel', $lastLink);
        $this->assertStringContainsString('filter[title]=laravel', $prevLink);
        $this->assertStringContainsString('filter[title]=laravel', $nextLink);

    }
}
