<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Models\Permission;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * @var array<int, string>
     */
    protected array $permissionIds = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['permissions'] = self::permissionsStateFromRecord($this->record->permissions()->get());

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissionIds = RoleForm::selectedPermissionIds($data);

        unset($data['permissions']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncPermissions($this->permissionIds);
    }

    /**
     * @param  iterable<Permission>  $permissions
     * @return array<int, string>
     */
    private static function permissionsStateFromRecord(iterable $permissions): array
    {
        return collect($permissions)
            ->map(static fn (Permission $permission): string => (string) $permission->getKey())
            ->values()
            ->all();
    }
}
