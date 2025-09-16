<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact\Contact;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            CreateAction::make(),
        ];

    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-o-rectangle-stack')
                ->badge(Contact::count()),

            'unreplied' => Tab::make('Not Replied')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn ($query) => $query->where('is_read', 0))
                ->badge(Contact::where('is_read', 0)->count())
                ->badgeColor('danger'),

            'replied' => Tab::make('Replied')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn ($query) => $query->where('is_read', 1))
                ->badge(Contact::where('is_read', 1)->count())
                ->badgeColor('success'),
        ];
    }}
