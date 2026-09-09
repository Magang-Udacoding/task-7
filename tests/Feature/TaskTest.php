<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Task;

class TaskTest extends TestCase
{
    public function test_can_get_all_tasks(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data',
            ]);
    }

    public function test_can_create_task_successfully(): void
    {
        $payload = [
            'title' => 'Belajar Laravel',
            'description' => 'Mempelajari CRUD API',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task created successfully',
                'data' => [
                    'title' => 'Belajar Laravel',
                    'description' => 'Mempelajari CRUD API',
                    'is_completed' => false,
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Belajar Laravel',
        ]);
    }

    public function test_create_task_fails_when_title_is_missing(): void
    {
        $response = $this->postJson('/api/tasks', [
            'description' => 'Tanpa judul',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_can_update_task_successfully(): void
    {
        $task = Task::create([
            'title' => 'Judul Lama',
            'description' => 'Deskripsi Lama',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Judul Baru',
            'is_completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task updated successfully!',
                'data' => [
                    'title' => 'Judul Baru',
                    'is_completed' => true,
                ],
            ]);
    }

    public function test_update_task_returns_404_when_not_found(): void
    {
        $response = $this->putJson('/api/tasks/9999', [
            'title' => 'Tidak Ada',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not Found!',
            ]);
    }

    public function test_can_delete_task_successfully(): void
    {
        $task = Task::create([
            'title' => 'Task yang akan dihapus',
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully!',
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_delete_task_returns_404_when_not_found(): void
    {
        $response = $this->deleteJson('/api/tasks/9999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not Found!',
            ]);
    }
}
