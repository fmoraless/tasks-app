<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterTasksTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_filter_tasks_by_title()
    {
        Task::factory()->create([
            'title' => 'Laravel'
        ]);
        Task::factory()->create([
            'title' => 'Other title'
        ]);

        // tasks?filter[title]=Laravel

        $url = route('api.v1.tasks.index',[
            'filter' => [
                'title' => 'Laravel'
            ]
        ]);

        //dd(urldecode($url));
        // http://localhost/api/v1/tasks?filter[title]=Laravel"

        $this->getJson($url)
            ->assertJsonCount(1,'data')
            ->assertSee('Laravel')
            ->assertDontSee('Other title');
    }

    /** @test  */
    public function cannot_filter_tasks_by_unknown_filters()
    {
        Task::factory()->count(2)->create();


        // tasks?filter[unknown]=filter

        $url = route('api.v1.tasks.index',[
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);

        $this->getJson($url)->assertStatus(400);
    }
}
