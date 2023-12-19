<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Departement;
use App\Enums\PermissionsClass;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceResource\RelationManagers;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nom_service')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('departement_id')
                    ->label('Nom du département')
                    ->required()
                    ->options(
                        Departement::select(['nom_departement','id'])->pluck('nom_departement','id')
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom_service')
                    ->label('Nom du service'),
                TextColumn::make('nom_departement')
                    ->label('Nom du département'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     // Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }    

    
    public static function canViewAny(): bool
    {

        return auth()->user()->hasAnyPermission([         
            PermissionsClass::services_create()->value,
            PermissionsClass::services_read()->value,
            PermissionsClass::services_update()->value
        ]);
    }

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
}
