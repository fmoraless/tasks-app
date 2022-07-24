<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_cannot_update_tasks()
    {
        $task = Task::factory()->create();

        $response = $this->patchJson(route('api.v1.tasks.update', $task))
            ->assertJsonApiError(
                title: 'Unauthenticated',
                detail: 'This action requires authentication.',
                status: '401'
            );

    }

    /** @test  */
    public function can_update_tasks()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->manager);

        $response = $this->patchJson(route('api.v1.tasks.update', $task), [
            'title' => $task->title,
            'description' => 'Updated Task 1 description',

        ])->assertOk();

        $response->assertJsonApiResource($task, [
            'title' => $task->title,
            'description' => 'Updated Task 1 description',
        ]);

    }

    /** @test  */
    public function title_is_required()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->manager);

        $this->patchJson(route('api.v1.tasks.update', $task), [
            'description' => 'Update Task 1 description',
        ])->assertJsonApiValidationErrors('title');
        //$response->assertJsonValidationErrors('data.attributes.title');

    }

    /** @test  */
    public function title_must_have_at_least_4_characters()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->manager);

        $this->patchJson(route('api.v1.tasks.update', $task), [
            'title' => 'Tas',
            'description' => 'Update Task 1 description',
        ])->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function title_must_be_unique()
    {
        $task1 = Task::factory()->create();
        $task2 = Task::factory()->create();
        Sanctum::actingAs($task1->manager);

        $this->patchJson(route('api.v1.tasks.update', $task1), [
            'title' => $task2->title,
            'description' => 'Probando title único test'

        ])->assertJsonApiValidationErrors('title');
    }

    /** @test  */
    public function description_is_required()
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->manager);

        $this->patchJson(route('api.v1.tasks.update', $task), [
            'title' => 'Update title Task 1',
        ])->assertJsonApiValidationErrors('description');

    }
}
