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
}
