<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

use App\Models\Task;

class CustomFieldController extends Controller
{
    // Список полей задачи
    public function index(Task $task)
    {
        return response()->json($task->customFields);
    }

    // Создать кастомное поле
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select',
            'options' => 'nullable|array'
        ]);

        $field = $task->customFields()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'options' => $validated['options'] ?? null
        ]);

        return response()->json([
            'message' => 'Кастомное поле создано',
            'field' => $field
        ], 201);
    }

    // Обновить кастомное поле
    public function update(Request $request, Task $task, CustomField $customField)
    {
        if ($customField->task_id !== $task->id) {
            return response()->json(['message' => 'Поле не принадлежит задаче'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:text,number,date,select',
            'options' => 'nullable|array'
        ]);

        $customField->update($validated);

        return response()->json([
            'message' => 'Кастомное поле обновлено',
            'field' => $customField
        ]);
    }

    // Удалить кастомное поле
    public function destroy(Task $task, CustomField $customField)
    {
        if ($customField->task_id !== $task->id) {
            return response()->json(['message' => 'Поле не принадлежит задаче'], 403);
        }

        $customField->delete();

        return response()->json(['message' => 'Поле удалено']);
    }
}