<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_can_get_tasks(): void
    {
        $user = User::factory()->create();
        $this->post('/api/tasks', [
            'title' => 'Comprar pães',
            'description' => 'Deve ser comprado 258 pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);

        $responseGetTasks = $this->getJson('/api/tasks');
        $responseGetUniqueTask = $this->getJson('/api/tasks/1');

        $responseGetTasks->assertStatus(200);
        $responseGetUniqueTask->assertStatus(200);
        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Comprar pães',
            'description' => 'Deve ser comprado 258 pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);
    }

    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/tasks', [
            'title' => 'Comprar pães',
            'description' => 'Deve ser comprado 258 pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Comprar pães',
            'description' => 'Deve ser comprado 258 pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);
    }

    public function test_can_edit_taks(): void
    {
        $user = User::factory()->create();
        $this->post('/api/tasks', [
            'title' => 'Comprar pães',
            'description' => 'Deve ser comprado 258 pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);

        $response = $this->put('/api/tasks/1', [
            'title' => 'Comprar pães',
            'description' => 'Agora deve ser comprado somente 255 pães para o lanche da tarde',
            'status' => 'doing',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Comprar pães',
            'description' => 'Agora deve ser comprado somente 255 pães para o lanche da tarde',
            'status' => 'doing',
            'user_id' => $user->id,
        ]);
    }

    public function test_can_changed_status_task(): void
    {
        $user = User::factory()->create();
        $this->post('/api/tasks', [
            'title' => 'Não iremos mais precisar de pães',
            'description' => 'não iremos mais precisar de pães para o lanche da tarde',
            'status' => 'todo',
            'user_id' => $user->id,
        ]);

        $response = $this->put('/api/tasks/1/status', [
            'status' => 'doing',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'doing']);
        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'status' => 'doing',
            'user_id' => $user->id,
        ]);
    }

    public function test_can_delete_task(): void
    {
        $user = User::factory()->create();
        $this->post('/api/tasks', [
            'title' => 'Não iremos mais precisar de pães',
            'description' => 'não iremos mais precisar de pães para o lanche da tarde',
            'status' => 'doing',
            'user_id' => $user->id,
        ]);

        $response = $this->delete('/api/tasks/1');

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    public function test_can_restore_task()
    {
        $user = User::factory()->create();
        $this->post('/api/tasks', [
            'title' => 'Não iremos mais precisar de pães',
            'description' => 'não iremos mais precisar de pães para o lanche da tarde',
            'status' => 'doing',
            'user_id' => $user->id,
        ]);

        $this->delete('/api/tasks/1');

        $response = $this->put('/api/tasks/1/restore');

        $response->assertStatus(200);
        echo $response->getContent();
        $response->assertJson([['id' => 1]]);
    }
}
