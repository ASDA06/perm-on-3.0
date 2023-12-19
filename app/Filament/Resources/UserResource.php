<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PermissionsClass;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Utilisateurs';

    protected static ?string $navigationGroup = 'Authentification';


    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        $loggedService = Service::where('id', auth()->user()->service_id)->value('departement_id');

        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Card::make([
                            TextInput::make('name')
                                ->label('Nom')
                                ->required(),

                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->unique(table: static::$model, ignorable: fn($record) => $record)
                                ->regex('/.*@laposte\.tg$/')
                                ->disabledOn('edit') // field must end with @laposte.tg
                                ->label('email'),

                            TextInput::make('password')
                                ->same('passwordConfirmation')
                                ->password()
                                ->maxLength(255)
                                ->required(fn($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                                ->dehydrateStateUsing(fn($state, $record) => !empty($state) ? Hash::make($state) : $record->password)
                                ->label('Mot de passe'),

                            TextInput::make('passwordConfirmation')
                                ->password()
                                ->dehydrated(false)
                                ->maxLength(255)
                                ->label('Confirmation de mot de passe'),

                                Select::make('roles')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload(true)
                                ->label('Rôles'),

                                Select::make('service_id')
                                ->label('Service')
                                ->required()
                                ->options(
                                    Service::pluck('nom_service', 'id')
                                )
                        ])->columns(2)

                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nom'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),

                TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i:s')
                    ->label('Date d\'inscription'),

                TextColumn::make('service'),

                BadgeColumn::make('roles.name')
                    ->label('Rôles'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {

        return auth()->user()->hasAnyPermission([         
            PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::utilisateurs_read()->value,
            PermissionsClass::utilisateurs_update()->value
        ]);
    }
}
