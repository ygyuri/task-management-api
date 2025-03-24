<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Artisan;





class TaskControllerTest extends TestCase
{
    use RefreshDatabase; // Rollback database after each test
    /**
     * Test task creation (store).
     */
    public function test_create_task()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test description',
            'priority' => 'high',
            'due_date' => '2025-05-01',
            'user_email' => 'test@example.com'
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'data' => $taskData
        ]);

        // Ensure the task is stored in the database
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    /**
     * Test task deletion (destroy).
     */
    public function test_delete_task()
    {
        // First, create a task
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task deleted successfully'
        ]);

        // Ensure the task is soft-deleted in the database
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
