<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Http\Request;

use App\Models\Project;

class TaskController extends Controller
{
    // Список задач проекта
    public function index(Project $project)
    {
        $tasks = $project->tasks()->with('assignedUser')->get();

        return response()->json($tasks);
    }

    // Создание задачи в проекте
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'integer',
            'priority' => 'integer',
            'due_date' => 'nullable|date',
        ]);

        $task = $project->tasks()->create($validated);

        return response()->json([
            'message' => 'Задача успешно создана',
            'task' => $task
        ], 201);
    }

    // Показать задачу
    public function show(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) {
            return response()->json(['message' => 'Задача не принадлежит проекту'], 403);
        }

        return response()->json($task);
    }

    // Обновить задачу
    public function update(Request $request, Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) {
            return response()->json(['message' => 'Задача не принадлежит проекту'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'integer',
            'priority' => 'integer',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Задача обновлена',
            'task' => $task
        ]);
    }

    // Удалить задачу
    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) {
            return response()->json(['message' => 'Задача не принадлежит проекту'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Задача удалена']);
    }
}
