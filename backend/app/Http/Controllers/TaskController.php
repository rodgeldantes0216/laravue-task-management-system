<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    /**
     * Display a listing of the user's tasks.
     *
     * Retrieves tasks for the authenticated user from the cache if available,
     * otherwise queries the database and caches the result for 10 minutes.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cacheKey = 'tasks_user_' . auth()->id() . '_' . ($request->input('status') ?? 'all') . '_' . ($request->input('priority') ?? 'all');


        $tasks = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            return Task::where('user_id', auth()->id())
                ->status($request->input('status'))
                ->priority($request->input('priority'))
                ->orderBy('order')
                ->get();
        });
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * Validates the incoming request using the TaskRequest and creates a new task
     * associated with the authenticated user. Returns the created task as a JSON
     * response with a 201 status code.
     *
     * @param \App\Http\Requests\TaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());
        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     *
     * Retrieves a task by its ID and checks that the authenticated user owns the task
     * using the authorizeTask method. Returns the task as a JSON response.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * Validates the incoming request using the TaskRequest and updates a task
     * associated with the authenticated user. Returns the updated task as a JSON
     * response.
     *
     * @param \App\Http\Requests\TaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorizeTask($task);
        $task->update($request->validated());
        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * Checks that the authenticated user owns the task using the authorizeTask method
     * and then deletes the task. Returns a JSON response with a success message.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Reorder the tasks.
     *
     * Expects a JSON array of objects with "id" and "order" properties.
     * Updates the "order" column of the tasks that match the IDs in the array
     * and belong to the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        foreach ($request->input('tasks', []) as $item) {
            Task::where('id', $item['id'])
                ->where('user_id', auth()->id())
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Reordered']);
    }

    /**
     * Authorize the task for the authenticated user.
     *
     * Checks if the task belongs to the authenticated user by comparing
     * the task's user_id with the authenticated user's ID. If the task does
     * not belong to the authenticated user, an unauthorized response is returned.
     *
     * @param \App\Models\Task $task
     */
    private function authorizeTask(Task $task)
    {
        if (($task->user_id ?? null) !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}


