<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\MakesJsonApiRequests;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_cannot_create_tasks()
    {
        $this->postJson(route('api.v1.tasks.store'))
            ->assertJsonApiError(
                title: 'Unauthenticated',
                detail: 'This action requires authentication.',
                status: '401'
            );

        $this->assertDatabaseCount('tasks', 0);
    }

    /** @test  */
    public function can_create_tasks()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.v1.tasks.store'), [
            'title' => 'Task 1',
            'description' => 'Task 1 description',
            '_relationships' => [
                'manager' => $user
            ]
        ])->assertCreated();

        $task = Task::first();

        $response->assertJsonApiResource($task, [
            'title' => 'Task 1',
            'description' => 'Task 1 description',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Task 1',
            'user_id' => $user->id,
        ]);
    }

    /** @test  */
    public function title_is_required()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('api.v1.tasks.store'), [
            'description' => 'Task 1 description',
        ]);

        $response->assertJsonApiValidationErrors('title');
        //$response->assertJsonValidationErrors('data.attributes.title');

    }

    /** @test  */
    public function title_must_have_at_least_4_characters()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('api.v1.tasks.store'), [
            'title' => 'Tas',
            'description' => 'Task 1 description',
        ]);
        $response->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function title_must_be_unique()
    {
        Sanctum::actingAs(User::factory()->create());

        $task = Task::factory()->create();

        $response = $this->postJson(route('api.v1.tasks.store'), [
            'title' => $task->name,
            'description' => 'Probando title único test'

        ]);
        $response->assertJsonApiValidationErrors('title');
    }

    /** @test  */
    public function description_is_required()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('api.v1.tasks.store'), [
            'title' => 'Task 1',
        ]);
        $response->assertJsonApiValidationErrors('description');

    }

    /** @test  */
    public function manager_relationship_is_required()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.tasks.store'), [
            'title' => 'Task 1',
            'description' => 'Task 1 description',
        ])->assertJsonApiValidationErrors('relationships.manager');

    }

    /** @test  */
    public function user_must_exist_in_database()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.tasks.store'), [
            'title' => 'Task 1',
            'description' => 'Task 1 description',
            '_relationships' => [
                'manager' => User::factory()->make()
            ]
        ])->assertJsonApiValidationErrors('relationships.manager');

    }
}
