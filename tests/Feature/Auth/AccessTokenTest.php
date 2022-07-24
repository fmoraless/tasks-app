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

        $data = $this->validCredentials(['email' => $user->email]);
        $response = $this->postJson(route('api.v1.login'), $data);

        $token = $response->json('plain-text-token');

        /* verificar token */
        $dbToken = PersonalAccessToken::findToken($token);
        $this->assertTrue($dbToken->tokenable()->is($user));
        //dd($dbToken->tokenable()->toArray());

    }

    /** @test  */
    public function password_must_be_valid()
    {
        $this->withoutJsonApiDocumentFormatting();

        $user = User::factory()->create();

        $data = $this->validCredentials([
            'email' => $user->email,
            'password' => 'incorrect-password'
        ]);
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrorFor('email');

    }

    /** @test  */
    public function user_must_be_registered()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data = $this->validCredentials();
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrorFor('email');

    }

    /** @test  */
    public function email_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data = $this->validCredentials(['email' => null]);
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);

    }

    /** @test  */
    public function email_must_be_valid()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data = $this->validCredentials(['email' => 'invalid-email']);
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['email' => 'email']);

    }

    /** @test  */
    public function password_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data = $this->validCredentials(['password' => null]);
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);

    }

    /** @test  */
    public function device_name_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data = $this->validCredentials(['device_name' => null]);
        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['device_name' => 'The device name field is required.']);

    }

    /* $overrides = [] para hacerlo opcional */
    public function validCredentials(mixed $overrides = []): array
    {
        return array_merge([
            'email' => 'francisco@nokoder.com',
            'password' => 'password',
            'device_name' => 'Test device',
        ], $overrides);
    }
}
