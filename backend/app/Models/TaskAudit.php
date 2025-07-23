<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAudit extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'action',
        'old_values',
        'new_values',
    ];

    /**
     * The user that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task associated with the audit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
