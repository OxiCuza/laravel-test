<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'user testing premium',
            'email' => 'userpremium@localhost.com',
            'password' => 'passwordpassword',
            'password_confirmation' => 'passwordpassword',
            'role_id' => 1,
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'credit',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'email' => $response->json('data.email'),
            'credit' => 40,
        ]);
    }

    public function test_user_invalid_register(): void
    {
        $data = [
            'name' => 'user testing premium',
            'email' => 'userpremium@localhost.com',
            'password' => 'passwordpassword',
            'role_id' => 1,
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'password'
                ],
            ]);
    }

    public function test_user_can_login(): void
    {
        $data = [
            'email' => 'premium@localhost.com',
            'password' => 'passwordpassword',
        ];

        $response = $this->postJson('/api/v1/auth/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token'
                ],
            ]);
    }

    public function test_user_invalid_login(): void
    {
        $data = [
            'email' => 'premium@localhost.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/auth/login', $data);

        $response->assertStatus(400)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials',
                'data' => null,
            ]);
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->getRegular());

        $response = $this->post('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'OK!',
                'data' => null,
            ]);
    }
}
