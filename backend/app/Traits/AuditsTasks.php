<?php

namespace App\Traits;

use App\Models\TaskAudit;

trait AuditsTasks
{
    /**
     * Logs an action that was performed on a task.
     *
     * @param mixed $task The task that was modified, or the ID of the task if it was deleted.
     * @param string $action The action that was performed: 'create', 'update', or 'delete'.
     * @param mixed $old The state of the task before the action was performed, if any.
     */
    public function logTaskAction($task, $action, $old = null)
    {
        TaskAudit::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id ?? null,
            'action' => $action,
            'old_values' => $old ? json_encode($old) : null,
            'new_values' => json_encode($task->toArray()),
        ]);
    }
}
