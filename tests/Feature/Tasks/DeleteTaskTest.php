<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_cannot_delete_tasks()
    {
        $task = Task::factory()->create();

        $this->deleteJson(route('api.v1.tasks.destroy', $task))
            ->assertJsonApiError(
                title: 'Unauthenticated',
                detail: 'This action requires authentication.',
                status: '401'
            );
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

    /** @test  */
    public function cannot_delete_tasks_assigned_to_other_managers()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson(route('api.v1.tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseCount('tasks', 1);
    }
}
