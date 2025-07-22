<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::where('is_admin', false)->get();

        foreach ($users as $user) {
            \App\Models\Task::factory(5)->create(['user_id' => $user->id]);
        }
    }
}
