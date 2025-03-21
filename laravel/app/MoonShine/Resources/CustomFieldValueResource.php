<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomFieldValue;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\ResourceModel;

/**
 * @extends ModelResource<CustomFieldValue>
 */
class CustomFieldValueResource extends ModelResource
{
    protected string $model = CustomFieldValue::class;

    protected string $title = 'CustomFieldValues';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),

                BelongsTo::make('Custom Field', 'field', 'title', new CustomFieldResource()),

                Text::make('Entity ID', 'entity_id'),

                Text::make('Entity Type', 'entity_type'),

                Text::make('Value', 'value'),
            ]),
        ];
    }

    /**
     * @param CustomFieldValue $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
