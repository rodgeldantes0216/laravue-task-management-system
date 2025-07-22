<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanOldTasks extends Command
{
    protected $signature = 'tasks:cleanup';
    protected $description = 'Delete tasks older than 30 days';

    /**
     * Execute the console command to delete tasks older than 30 days.
     *
     * This command retrieves all tasks created more than 30 days ago,
     * logs their deletion, and removes them from the database.
     *
     * @return void
     */
    public function handle()
    {
        $cutoff = Carbon::now()->subDays(30);

        $oldTasks = Task::where('created_at', '<', $cutoff)->get();

        foreach ($oldTasks as $task) {
            Log::info("Deleting Task #{$task->getKey()}: {$task->title}");
            $task->delete();
        }

        $this->info('Old tasks cleaned up successfully.');
    }
}
