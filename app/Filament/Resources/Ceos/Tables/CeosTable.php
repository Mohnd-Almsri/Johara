<?php

namespace App\Filament\Resources\Ceos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CeosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                textColumn::make('id')->searchable()->sortable(),
                textColumn::make('paragraph_1')->searchable()->sortable()->limit(30),
                textColumn::make('paragraph_2')->searchable()->sortable()->limit(30),
                textColumn::make('paragraph_3')->limit(50)->searchable()->sortable()->limit(30)
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                deleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
