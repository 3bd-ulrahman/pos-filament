<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected string $role;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role'] = $this->record->roles()->value('roles.id');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->role = $data['role'];

        unset($data['role']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles([$this->role]);
    }
}
