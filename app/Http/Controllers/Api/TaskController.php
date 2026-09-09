<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // get all  tasks
    public function index(): JsonResponse
    {
        $task = Task::latest()->get();

        return response()->json([
            'message' => 'Task retrieved successfully',
            'data' => $task,
        ], 200
        );
    }

    // create task
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task,
        ], 201
        );
    }

    // update task
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'message' => 'Task not Found!',
            ], 404
            );
        }

        $task->update($request->validated());

        return response()->json([
            'message' => 'Task updated successfully!',
            'data' => $task,
        ], 200
        );
    }

    // delete task
    public function destroy(string $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'message' => 'Task not Found!',
            ], 404
            );
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully!',
            'data' => $task,
        ], 200
        );
    }
}
