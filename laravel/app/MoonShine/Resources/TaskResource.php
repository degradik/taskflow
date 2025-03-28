<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;


use App\Models\Project;
use App\Models\User;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Select;
use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;

use App\Models\CustomField;
use App\Models\CustomFieldValue;

use MoonShine\Fields\Number;
use MoonShine\ResourceModel;

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
        return [
            Block::make([
                ID::make()->sortable(),

                BelongsTo::make('Project', 'project', 'title')
                    ->required(),
    
                Text::make('Title')
                    ->required(),
    
                Textarea::make('Description'),
    
                BelongsTo::make('Assigned To', 'assignedUser', 'name', new UserResource())
                    ->nullable(),
    
                Select::make('Status')
                    ->options([
                        0 => 'New',
                        1 => 'In Progress',
                        2 => 'Completed',
                    ])
                    ->default(0),
    
                Select::make('Priority')
                    ->options([
                        0 => 'Low',
                        1 => 'Medium',
                        2 => 'High',
                    ])
                    ->default(0),
    
                Date::make('Due Date', 'due_date'),
            ]),
        ];
    }

    /**
     * @param Task $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
