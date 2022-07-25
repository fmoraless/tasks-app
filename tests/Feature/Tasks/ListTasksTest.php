<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class ListTasksTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_fetch_a_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson(route('api.v1.tasks.show', $task));

        $response->assertJsonApiResource($task, [
            'title' => $task->title,
            'description' => $task->description
        ])->assertJsonApiRelationshipLinks($task, ['manager']);
    }

    /** @test  */
    public function can_fetch_all_tasks()
    {
        $this->withoutExceptionHandling();

        $tasks = Task::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.tasks.index'));
        $response->assertJsonApiResourceCollection($tasks, [
           'title', 'description'
        ]);
    }

    /** @test  */
    public function it_returns_a_json_api_error_when_a_task_is_not_found()
    {

        $response = $this->getJson(route('api.v1.tasks.show', 'not-existing-task'));

        $response->assertJsonApiError(
            title: "Not Found",
            detail: "Not records found with the id 'not-existing-task' in 'tasks' resource",
            status: "404",
        );
    }
}
