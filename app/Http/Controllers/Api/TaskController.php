<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }

    /** Display a listing of the resource.*/
    public function index():AnonymousResourceCollection
    {
        $tasks = Task::query()
            ->allowedIncludes(['manager'])
            ->allowedFilters(['title', 'description'])
            ->jsonPaginate();

        return TaskResource::collection($tasks);
    }

    /** Store a newly created resource in storage. */
    public function store(SaveTaskRequest $request):TaskResource
    {
        $task = Task::create($request->validate());

        return TaskResource::make($task);
    }

    /** Display the specified resource. */
    public function show(Task $task): JsonResource
    {
        $task = Task::where('id', $task->id)
            ->allowedIncludes(['manager'])
            ->firstOrFail();
        return TaskResource::make($task);
    }

    /** Update the specified resource in storage. */
    public function update(Task $task, SaveTaskRequest $request): TaskResource
    {
        $task->update($request->validate());

        return TaskResource::make($task);
    }

    /** Remove the specified resource from storage. */
    public function destroy(Task $task): Response
    {
        $task->delete();
        return response()->noContent();
    }
}
