<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Role; // Import Role model

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_privacy_consent()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test Patient',
            'email' => 'patient@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'privacy_consent' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'access_token', 'token_type']);

        $this->assertDatabaseHas('users', [
            'email' => 'patient@example.com',
            'user_type' => 'patient',
            'has_consented_privacy_policy' => true,
        ]);
    }

    public function test_user_cannot_register_without_privacy_consent()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test Patient',
            'email' => 'patient2@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'privacy_consent' => false, // explicitly false or missing
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['privacy_consent']);

        $this->assertDatabaseMissing('users', [
            'email' => 'patient2@example.com',
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'user_type' => 'patient',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'access_token', 'token_type']);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'user_type' => 'patient',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Invalid login credentials']);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'user_type' => 'patient',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logged out successfully']);
        
        // Assert token is revoked (assuming Sanctum)
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);
    }
}
