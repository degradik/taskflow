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
use MoonShine\ResourceModel;

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
            Block::make([
                ID::make()->sortable(),
                
                Text::make('Title')->required(),

                Text::make('Slug')->required(),
    
                Select::make('Type')->options([
                    'text'   => 'Text',
                    'number' => 'Number',
                    'date'   => 'Date',
                    'select' => 'Select',
                ])->required(),
    
                Text::make('Entity Type')->required(),
    
                Json::make('Options')->nullable()
            ]),
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
