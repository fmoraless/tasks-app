<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_can_delete_tasks()
    {
        $task = Task::factory()->create();

        $this->deleteJson(route('api.v1.tasks.destroy', $task))
            ->assertUnauthorized();
    }

    /** @test  */
    public function can_delete_tasks()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->manager);

        $this->deleteJson(route('api.v1.tasks.destroy', $task))
            ->assertNoContent();
        $this->assertDatabaseCount('tasks', 0);
    }
}
