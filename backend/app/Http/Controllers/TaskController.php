<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\AuditsTasks;

class TaskController extends Controller
{
    use AuditsTasks;


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

    public function store(TaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());
        $this->logTaskAction($task, 'create');
        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorizeTask($task);
        $old = clone $task;
        $task->update($request->validated());
        $this->logTaskAction($task, 'update', $old);
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $this->logTaskAction($task, 'delete', $task);
        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }

   public function reorder(Request $request)
    {
        foreach ($request->input('tasks', []) as $item) {
            Task::where('id', $item['id'])
                ->where('user_id', auth()->id())
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Reordered']);
    }

    private function authorizeTask(Task $task)
    {
        if (($task->user_id ?? null) !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
