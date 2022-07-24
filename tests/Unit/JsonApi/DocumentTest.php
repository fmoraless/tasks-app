<?php

namespace Tests\Unit\JsonApi;

use App\JsonApi\Document;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{


    /** @test  */
    public function can_create_json_api_documents()
    {
        $user = Mockery::mock('User', function ($mock) {
            $mock->shouldReceive('getResourceType')->andReturn('users');
            $mock->shouldReceive('getRouteKey')->andReturn('user-id');
        });
        $document = Document::type('tasks')
             ->id('task-id')
             ->attributes([
                 'title' => 'Task title',
             ])->relationshipData([
                  'user' => $user
            ])->toArray();

        $expected = [
            'data' => [
                'type' => 'tasks',
                'id' => 'task-id',
                'attributes' => [
                    'title' => 'Task title',
                ],
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'users',
                            'id' => 'user-id',
                        ],
                    ]
                ],
            ],
        ];

        $this->assertEquals($expected, $document);
    }
}
