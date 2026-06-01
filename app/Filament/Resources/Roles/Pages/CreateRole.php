<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * @var array<int, string>
     */
    protected array $permissionIds = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissionIds = RoleForm::selectedPermissionIds($data);

        unset($data['permissions']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncPermissions($this->permissionIds);
    }
}
