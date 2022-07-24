<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManagerRelationshipTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_fetch_the_associated_manager_identifier()
    {
        $task = Task::factory()->create();
        $url = route('api.v1.tasks.relationships.manager', $task);
        $response = $this->getJson($url);

        $response->assertExactJson([
            'data' => [
                'id' => $task->manager->getRouteKey(),
                'type' => 'managers',
            ]
        ]);
    }

    /** @test  */
    public function can_fetch_the_associated_manager_resource()
    {
        $task = Task::factory()->create();
        $url = route('api.v1.tasks.manager', $task);

        $response = $this->getJson($url);

        $response->assertJson([
            'data' => [
                'type' => 'managers',
                'id' => $task->manager->getRouteKey(),
                'attributes' => [
                    'name' => $task->manager->name,
                ]
            ]
        ]);
    }

    /** @test  */
    public function can_update_the_associated_manager()
    {
        $manager = User::factory()->create();
        $task = Task::factory()->create();

        $url = route('api.v1.tasks.relationships.manager', $task);

        /* para desabilitar el formateo del documento JSON API */
        $this->withoutJsonApiDocumentFormatting();

        $response = $this->patchJson($url, [
            'data' => [
                'type' => 'managers',
                'id' => $manager->getRouteKey(),
            ]
        ]);

        $response->assertExactJson([
            'data' => [
                'type' => 'managers',
                'id' => $manager->getRouteKey(),
            ]
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'user_id' => $manager->id,
        ]);
    }

    /** @test  */
    public function manager_must_exist_in_database()
    {
        $task = Task::factory()->create();

        $url = route('api.v1.tasks.relationships.manager', $task);

        /* para desabilitar el formateo del documento JSON API */
        $this->withoutJsonApiDocumentFormatting();

        $this->patchJson($url, [
            'data' => [
                'type' => 'managers',
                'id' => 'not-exists',
            ]
        ])->assertJsonApiValidationErrors('data.id');

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'user_id' => $task->user_id,
        ]);
    }
}
