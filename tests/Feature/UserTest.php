<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Carlos Alexandre',
            'email' => 'carlos.alexandre@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Carlos Alexandre',
            'email' => 'carlos.alexandre@gmail.com'
        ]);
    }

    public function test_can_login_user(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'id' => 1,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => $user->name,
            'email' => $user->email,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
    }

    public function test_can_edit_user()
    {
        $user = User::factory()->create();

        $response = $this->put('/api/users/1', [
            'name' => 'Carlos Alexandre'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Carlos Alexandre'
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/1');

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
            'id' => 1,
            'name' => $user->name
        ]);
    }
}
