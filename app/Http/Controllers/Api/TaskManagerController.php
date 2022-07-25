<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManagerResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskManagerController extends Controller
{
    public function index(Task $task)
    {
        return ManagerResource::identifier($task->manager);
    }

    public function show(Task $task)
    {
        return ManagerResource::make($task->manager);
    }

    public function update(Task $task, Request $request)
    {
        $request->validate([
            'data.id' => 'exists:users,id',
        ]);

       $managerId = $request->input('data.id');

        $task->update(['user_id' => $managerId]);

        return ManagerResource::identifier($task->manager);
    }
}
