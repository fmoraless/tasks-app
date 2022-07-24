<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AccessTokenTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_emit_access_tokens()
    {
        $this->withoutJsonApiDocumentFormatting();

        //$this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'Test device',
        ]);

        $token = $response->json('plain-text-token');

        /* verificar token */
        $dbToken = PersonalAccessToken::findToken($token);
        $this->assertTrue($dbToken->tokenable()->is($user));
        //dd($dbToken->tokenable()->toArray());

    }
}
