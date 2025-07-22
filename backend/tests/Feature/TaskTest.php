<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Setup the test environment. This method is called before each test.
     * In this case, it creates a new user and simulates a login.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->createOne();
        $this->actingAs($this->user); // simulate login
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::store
     * Tests that a user can create a task by sending a POST request to
     * /api/tasks with the required data.
     */
    public function test_user_can_create_task()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Sample',
            'priority' => 'high',
            'status' => 'pending',
            'order' => 1,
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(201)->assertJsonFragment(['title' => 'Test Task']);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::update
     * Tests that a user can update a task by sending a PUT request to
     * /api/tasks/{task} with the required data.
     */
    public function test_user_can_update_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'status' => 'completed',
        ]);

        $response->assertOk()->assertJsonFragment(['title' => 'Updated Task']);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::destroy
     * Tests that a user can delete a task by sending a DELETE request to
     * /api/tasks/{task}.
     */
    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertOk();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     * Tests that a user can retrieve a list of tasks by sending a GET request to
     * /api/tasks.
     */
    public function test_user_can_list_tasks()
    {
        Task::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks');
        $response->assertOk()->assertJsonCount(3);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::reorder
     * Tests that a user can reorder tasks by sending a POST request to
     * /api/tasks/reorder with an array of objects containing "id" and "order" properties.
     */
    public function test_user_can_reorder_tasks()
    {
        $tasks = Task::factory(3)->create(['user_id' => $this->user->id]);

        $reordered = $tasks->map(function ($task, $index) {
            return ['id' => $task->id, 'order' => $index + 10];
        });

        $response = $this->postJson('/api/tasks/reorder', ['tasks' => $reordered->toArray()]);
        $response->assertOk();

        foreach ($reordered as $item) {
            $this->assertDatabaseHas('tasks', ['id' => $item['id'], 'order' => $item['order']]);
        }
    }
}
