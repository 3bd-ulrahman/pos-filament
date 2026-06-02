<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Permission;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('display_name'),

                ViewField::make('permissions')
                    ->label('Permissions')
                    ->markAsRequired()
                    ->required()
                    ->default([])
                    ->columnSpanFull()
                    ->view('filament.forms.components.permissions-selector')
                    ->viewData([
                        'groupedPermissions' => static::groupedPermissionsOptions(),
                    ]),
            ]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function groupedPermissionsOptions(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(static function (Permission $permission): string {
                return Str::of($permission->name)
                    ->before('-')
                    ->toString();
            })
            ->map(static function ($permissions): array {
                return $permissions
                    ->mapWithKeys(static function (Permission $permission): array {
                        return [
                            (string) $permission->getKey() => $permission->display_name ?: $permission->name,
                        ];
                    })
                    ->all();
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    public static function selectedPermissionIds(array $data): array
    {
        return collect($data['permissions'] ?? [])
            ->flatten()
            ->filter()
            ->map(static fn ($permissionId): string => (string) $permissionId)
            ->unique()
            ->values()
            ->all();
    }
}
