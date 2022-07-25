<?php

namespace Tests\Feature\Auth;

use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_register()
    {
        $this->withoutJsonApiDocumentFormatting();

        $response = $this->postJson(route('api.v1.register'), [
            'name' => 'John Doe',
            'email' => 'admin@admin.com',
            'device_name' => 'Device Name',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $token = $response->json('plain-text-token');
        //dd($token);

        /* verificar token */
        $this->assertNotNull(PersonalAccessToken::findToken($token));

            $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'admin@admin.com',
        ]);
    }

    /** @test  */
    /*public function user_name_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $this->postJson(route('api.v1.register'), [
                'name' => '',
                'email' => 'admin@admin.com',
                'device_name' => 'Device Name',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])->assertJsonValidationErrors(['name' => 'The name field is required.']);

        $data = $this->validCredentials(['name' => null]);
        $response = $this->postJson(route('api.v1.register'), $data);

        $response->assertJsonValidationErrors('name');

    }*/
    /*public function validCredentials(): array
    {
        return [
            'name' => 'John Doe',
            'email' => 'admin@admin.com',
            'device_name' => 'Device Name',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }*/

}
