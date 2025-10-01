<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with('keywords')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        if ($request->has('keyword_ids')) {
            $task->keywords()->attach($request->keyword_ids);
        }

        $task->load('keywords');
        return response()->json($task, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());

        if ($request->has('keyword_ids')) {
            $task->keywords()->sync($request->keyword_ids);
        }

        $task->load('keywords');
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Toggle the status of a task.
     */
    public function toggle($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->is_done = !$task->is_done;
        $task->save();

        $task->load('keywords');
        return response()->json($task);
    }
}
