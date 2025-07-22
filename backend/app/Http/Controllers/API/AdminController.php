<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with(['tasks' => function ($q) {
            $q->select('id', 'user_id', 'status');
        }])->select('id', 'name', 'email')->paginate(10);

        $data = $users->map(function ($user) {
            $completed = $user->tasks->where('status', 'completed')->count();
            $pending = $user->tasks->where('status', 'pending')->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'task_stats' => [
                    'total' => $user->tasks->count(),
                    'completed' => $completed,
                    'pending' => $pending,
                ]
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }
}

