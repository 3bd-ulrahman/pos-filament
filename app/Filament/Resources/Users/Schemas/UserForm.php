<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required(),

                Radio::make('role')
                    ->label('Choose a Role')
                    ->options(fn () => Role::query()->pluck('display_name', 'id')->toArray())
                    ->extraInputAttributes(['class' => 'checkbox-looking-radio'])
                    ->columns(3)
                    ->required()
                    ->exists('roles', 'id'),
            ]);
    }
}
