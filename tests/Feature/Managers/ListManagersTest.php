<?php

namespace Tests\Feature\Managers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ListManagersTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_fetch_a_single_manager()
    {
        $this->withoutExceptionHandling();
        $manager = User::factory()->create();

        $response = $this->getJson(route('api.v1.managers.show', $manager));
        $response->assertJsonApiResource($manager, [
            'name' => $manager->name,
        ]);

        $this->assertTrue(
            Str::isUuid($response->json('data.id')),
            "The user 'id' is not a valid UUID"
        );

    }

    /** @test  */
    public function can_fetch_all_manager()
    {
        $managers = User::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.managers.index'));
        $response->assertJsonApiResourceCollection($managers, [
            'name'
        ]);
    }
}
