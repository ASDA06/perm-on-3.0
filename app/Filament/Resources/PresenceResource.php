<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Presence;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PermissionsClass;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PresenceResource\Pages;
use App\Filament\Resources\PresenceResource\RelationManagers;


class PresenceResource extends Resource
{
    protected static ?string $model = Presence::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->required(),

                TimePicker::make('heure_arrivee')
                    ->label('Heure d\'arrivée')
                    ->required()
                    ->seconds(false),

                TimePicker::make('heure_depart')
                    ->after('heure_arrivee')
                    ->label('Heure de départ')
                    ->seconds(false),

                Hidden::make('user_id')
                    ->default(auth()->user()->id)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date('d-m-Y'),

                TextColumn::make('nom')
                    ->searchable(),

                TextColumn::make('heure_arrivee')
                    ->label('Heure d\'arrivée')
                    ->color(Color::Cyan),

                TextColumn::make('heure_depart')
                    ->label('Heure de départ')
                    ->placeholder('-')
                    ->color(Color::Yellow),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_cible'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_cible'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '=', $date),
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])->defaultsort('heure_arrivee', 'asc');
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
            'index' => Pages\ListPresences::route('/'),
            'create' => Pages\CreatePresence::route('/create'),
            // 'edit' => Pages\EditPresence::route('/{record}/edit'),
        ];
    }


    public static function canViewAny(): bool
    {

        return auth()->user()->hasAnyPermission([
            PermissionsClass::presences_create()->value,
            PermissionsClass::presences_read()->value,
            PermissionsClass::presences_update()->value
        ]);
    }
}
