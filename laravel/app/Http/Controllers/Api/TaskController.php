<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Список задач пользователя через его проекты
        $tasks = Task::whereHas('project', function ($query) {
            $query->where('owner_id', auth()->id());
        })->with('customFieldValues.customField')->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|integer',
            'priority' => 'nullable|integer',
            'due_date' => 'nullable|date',
        ]);

        $project = auth()->user()->projects()->findOrFail($validated['project_id']);

        $task = $project->tasks()->create($validated);

        // Сохраняем кастомные поля
        $this->saveCustomFields($task, $request->input('custom_fields', []));

        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::with('customFieldValues.customField')
            ->whereHas('project', function ($query) {
                $query->where('owner_id', auth()->id());
            })
            ->findOrFail($id);

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::whereHas('project', function ($query) {
            $query->where('owner_id', auth()->id());
        })->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|integer',
            'priority' => 'nullable|integer',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        // Сохраняем кастомные поля
        $this->saveCustomFields($task, $request->input('custom_fields', []));

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::whereHas('project', function ($query) {
            $query->where('owner_id', auth()->id());
        })->findOrFail($id);

        $task->delete();

        return response()->json(['message' => 'Задача удалена']);
    }

    private function saveCustomFields(Task $task, array $customFields)
    {
        foreach ($customFields as $slug => $value) {
            $field = CustomField::where('slug', $slug)
                ->where('entity_type', Task::class)
                ->first();

            if ($field) {
                CustomFieldValue::updateOrCreate(
                    [
                        'custom_field_id' => $field->id,
                        'entity_id' => $task->id,
                        'entity_type' => Task::class,
                    ],
                    ['value' => $value]
                );
            }
        }
    }
}
