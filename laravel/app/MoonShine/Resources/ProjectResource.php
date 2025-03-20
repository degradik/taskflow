<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Date;
use MoonShine\Fields\Select;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\BelongsTo;

use App\MoonShine\Resources\UserResource;

/**
 * @extends ModelResource<Project>
 */
class ProjectResource extends ModelResource
{
    protected string $model = Project::class;

    protected string $title = 'Projects';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->required(),

            BelongsTo::make('Владелец', 'owner', 'name', new UserResource())->required(),

            BelongsToMany::make('Пользователи', 'users', 'name', new UserResource())->required(),


            Textarea::make('Description'),

            Select::make('Status')
                ->options([
                    0 => 'Draft',
                    1 => 'Active',
                    2 => 'Completed',
                ])
                ->default(0),

            Date::make('Deadline'),

            // скрытое поле или Select для выбора владельца, если надо
        ];
    }

    /**
     * @param Project $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
