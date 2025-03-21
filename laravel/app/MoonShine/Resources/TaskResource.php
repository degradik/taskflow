<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\CustomField;
use App\Models\CustomFieldValue;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Select;
use MoonShine\Fields\Date;
use MoonShine\Fields\Number;
use MoonShine\Fields\Group;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Relationships\BelongsTo;

/**
 * @extends ModelResource<Task>
 */
class TaskResource extends ModelResource
{
    protected string $model = Task::class;

    protected string $title = 'Tasks';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        // Основные поля задачи
        $baseFields = [
            ID::make()->sortable(),

            Text::make('Название', 'title')
                ->required(),

            Textarea::make('Описание', 'description'),

            BelongsTo::make('Проект', 'project', ProjectResource::class)
                ->required(),
        ];

        // Кастомные поля для текущей сущности Task
        $customFieldDefinitions = CustomField::where('entity_type', Task::class)->get();

        $customFields = [];

        foreach ($customFieldDefinitions as $field) {
            $customFields[] = $this->generateCustomField($field);
        }

        // Добавляем кастомные поля в группу (чтобы они были вместе)
        if (!empty($customFields)) {
            $baseFields[] = Group::make('Дополнительные поля', $customFields);
        }

        return $baseFields;
    }

    /**
     * Генерация поля на основе типа CustomField
     */
    protected function generateCustomField(CustomField $field): Field
    {
        return match ($field->type) {
            'text' => Text::make($field->title, 'custom_fields.' . $field->slug)->nullable(),
            'number' => Number::make($field->title, 'custom_fields.' . $field->slug)->nullable(),
            'date' => Date::make($field->title, 'custom_fields.' . $field->slug)->nullable(),
            'select' => Select::make($field->title, 'custom_fields.' . $field->slug)
                              ->options($field->options ?? [])
                              ->nullable(),
            default => Text::make($field->title, 'custom_fields.' . $field->slug)->nullable(),
        };
    }
    

    /**
     * Получаем данные, включая значения кастомных полей
     */
    public function getItemData(Model $item): array
    {
        $data = parent::getItemData($item);

        $customFieldDefinitions = CustomField::where('entity_type', Task::class)->get();

        foreach ($customFieldDefinitions as $field) {
            $value = CustomFieldValue::where('custom_field_id', $field->id)
                ->where('entity_id', $item->id)
                ->where('entity_type', Task::class)
                ->first();

            $data['custom_fields'][$field->slug] = $value?->value ?? null;
        }

        return $data;
    }

    /**
     * Сохраняем кастомные поля после сохранения основной модели
     */
    public function afterSave(Model $item, Fields $fields): Model
    {
        $request = request();

        $customFieldDefinitions = CustomField::where('entity_type', Task::class)->get();

        foreach ($customFieldDefinitions as $field) {
            $value = $request->input('custom_fields.' . $field->slug);

            CustomFieldValue::updateOrCreate(
                [
                    'custom_field_id' => $field->id,
                    'entity_id' => $item->id,
                    'entity_type' => Task::class,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return $item;
    }

    /**
     * Правила валидации
     *
     * @param Task $item
     * @return array<string, string[]|string>
     */
    public function rules(Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'exists:projects,id'],
        ];
    }
}
