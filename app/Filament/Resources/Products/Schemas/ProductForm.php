<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('purchase_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('sale_price')
                    ->required()
                    ->numeric()
                    ->prefix('£'),

                TextInput::make('stock')
                    ->required()
                    ->numeric(),
            ]);
    }
}
