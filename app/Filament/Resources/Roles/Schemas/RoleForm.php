<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Permission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
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

                TextInput::make('description'),

                Fieldset::make('Permissions')
                    ->contained(false)
                    ->schema(static::permissionSections())
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @return array<int, Section>
     */
    protected static function permissionSections(): array
    {
        return collect(static::groupedPermissionsOptions())
            ->map(static function (array $options, string $category): Section {
                return Section::make(Str::headline($category))
                    ->columnSpan(1)
                    ->schema([
                        CheckboxList::make("permissions.{$category}")
                            ->options($options)
                            ->bulkToggleable()
                            ->columns(2),
                    ]);
            })
            ->values()
            ->all();
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
     * @param  iterable<Permission>  $permissions
     * @return array<string, array<int, string>>
     */
    public static function permissionsStateFromRecord(iterable $permissions): array
    {
        $state = [];

        foreach ($permissions as $permission) {
            $category = Str::of($permission->name)
                ->before('-')
                ->toString();

            $state[$category][] = (string) $permission->getKey();
        }

        return $state;
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
