<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected string $role;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->role = $data['role'];

        unset($data['role']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->addRole($this->role);
    }
}
