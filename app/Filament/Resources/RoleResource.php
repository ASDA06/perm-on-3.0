<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PermissionsClass;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Filament\Resources\RoleResource\RelationManagers\PermissionsRelationManager;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'Authentification';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Nom')
                                ->required(),
                            TextInput::make('guard_name')
                                ->label('Guard name')
                                ->required()
                                ->default(config('auth.defaults.guard')),
                            // BelongsToManyMultiSelect::make('permissions')
                            //     ->label(strval(__('filament-authentication::filament-authentication.field.permissions')))
                            //     ->relationship('permissions', 'name')
                            //     ->hidden()
                            //     ->preload(config('filament-spatie-roles-permissions.preload_permissions'))
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Nom    ')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }    

    
    public static function canViewAny(): bool
    {

        return auth()->user()->hasAnyPermission([         
            PermissionsClass::roles_create()->value,
            PermissionsClass::roles_read()->value,
            PermissionsClass::roles_update()->value
        ]);
    }
}
