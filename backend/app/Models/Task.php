<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
    ];

    /**
     * The user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Scope a query to only include tasks with a specified status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }


    /**
     * Scope a query to only include tasks with a specified priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }

}
