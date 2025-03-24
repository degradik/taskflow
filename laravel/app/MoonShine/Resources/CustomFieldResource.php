<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomField;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Fields\Text;
use MoonShine\Fields\Select;
use MoonShine\Fields\Json;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\Resource;




/**
 * @extends ModelResource<CustomField>
 */
class CustomFieldResource extends ModelResource
{
    protected string $model = CustomField::class;

    protected string $title = 'CustomFields';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Задача', 'task', 'title')->required(),
            Text::make('Название', 'title')->required(),
            Select::make('Тип', 'type')
                ->options([
                    'text' => 'Текст',
                    'number' => 'Число',
                    'date' => 'Дата',
                    'select' => 'Список',
                ])
                ->required(),
            Json::make('Опции', 'options')
                ->fields([
                    Text::make('Ключ'),
                    Text::make('Значение'),
                ])
                ->hideOnIndex(),
        ];
    }

    /**
     * @param CustomField $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
