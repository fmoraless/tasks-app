<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeManagerTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_include_related_manager_of_an_task()
    {
        $task = Task::factory()->create();
        // tasks/{task}?include=manager
        $url = route('api.v1.tasks.show', [
            'task' => $task,
            'include' => 'manager'
        ]);
        //dd(urldecode($url));
        //http://localhost/api/v1/tasks/8?include=manager"

        $this->getJson($url)->assertJson([
           'included' => [
               [
                   'type' => 'managers',
                   'id' => $task->manager->getRouteKey(),
                   'attributes' => [
                       'name' => $task->manager->name,
                   ]
               ]
           ]
        ]);
    }

    /** @test  */
    public function can_include_related_user_of_multiple_articles()
    {
        $task = Task::factory()->create();
        $task2 = Task::factory()->create();

        $url = route('api.v1.tasks.index', [
            'include' => 'manager'
        ]);
        /*\DB::listen(function ($query) {
            dump($query->sql);
        });*/
        $this->getJson($url)->assertJson([
            'included' => [
                [
                    'type' => 'managers',
                    'id' => $task->manager->getRouteKey(),
                    'attributes' => [
                        'name' => $task->manager->name,
                    ]
                ],
                [
                    'type' => 'managers',
                    'id' => $task2->manager->getRouteKey(),
                    'attributes' => [
                        'name' => $task2->manager->name,
                    ]
                ]
            ]
        ]);
    }

    /** @test  */
    public function cannot_include_unknown_relationships()
    {
        $task = Task::factory()->create();
        $url = route('api.v1.tasks.show', [
            'task' => $task,
            'include' => 'unknown, unknown2'
        ]);

        $this->getJson($url)->assertStatus(400);

        $url = route('api.v1.tasks.index', [
            'include' => 'unknown, unknown2'
        ]);

        $this->getJson($url)->assertJsonApiError(
            title: "Bad Request",
            detail: "The included relationship 'unknown' is not allowed in the 'tasks' resource.",
            status: "400",
        );
    }

}
