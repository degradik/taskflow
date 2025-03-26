<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Http\Request;

class CustomFieldValueController extends Controller
{
    // Список значений всех кастомных полей задачи
    public function index(Task $task)
    {
        $values = $task->customFieldValues()->with('customField')->get();

        return response()->json($values);
    }

    // Создать / обновить значение кастомного поля
    public function storeOrUpdate(Request $request, Task $task, CustomField $customField)
    {
        if ($customField->task_id !== $task->id) {
            return response()->json(['message' => 'Поле не принадлежит задаче'], 403);
        }

        $validated = $request->validate([
            'value' => 'required'
        ]);

        // либо обновляем, либо создаем
        $value = CustomFieldValue::updateOrCreate(
            [
                'task_id' => $task->id,
                'custom_field_id' => $customField->id
            ],
            [
                'value' => $validated['value']
            ]
        );

        return response()->json([
            'message' => 'Значение сохранено',
            'value' => $value
        ]);
    }

    // Удалить значение поля
    public function destroy(Task $task, CustomField $customField, CustomFieldValue $customFieldValue)
    {
        if (
            $customFieldValue->task_id !== $task->id ||
            $customFieldValue->custom_field_id !== $customField->id
        ) {
            return response()->json(['message' => 'Значение не принадлежит задаче или полю'], 403);
        }

        $customFieldValue->delete();

        return response()->json(['message' => 'Значение удалено']);
    }
}
