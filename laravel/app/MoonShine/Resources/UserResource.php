<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Fields\Text;
use MoonShine\Fields\Email;
use MoonShine\Fields\File;
use MoonShine\Fields\Select;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Users';
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name', 'name')
                ->required()
                ->sortable(),

            Email::make('Email', 'email')
                ->required()
                ->sortable(),

            Select::make('Role', 'role')
                ->options([
                    'admin' => 'Admin',
                    'teamlead' => 'Teamlead',
                    'developer'  => 'Developer',
                ])
                ->required()
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'name'  => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $this->getItemID()],
            'role'  => ['required', 'in:admin,teamlead,developer'],
        ];
    }
}
