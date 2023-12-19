<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Permanence;
use Filament\Tables\Table;
use App\Models\Departement;

use App\Enums\PermissionsClass;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermanenceResource\Pages;
use Awcodes\Curator\PathGenerators\DatePathGenerator;
use App\Filament\Resources\PermanenceResource\RelationManagers;
use App\Filament\Resources\PermanenceResource\Widgets\PermanenceList;

class PermanenceResource extends Resource
{
    protected static ?string $model = Permanence::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        $serviceConnecte = Service::where('id', auth()->user()->service_id)
            ->value('departement_id');

        $nombreDeServices = Service::whereHas('departement', function ($query) use ($serviceConnecte) {
            $query->where('id', $serviceConnecte);
        })->count();

        return $form
            ->schema([
                Hidden::make('departement_id')
                    ->default(Departement::whereHas('services', function ($query) use ($serviceConnecte) {
                        $query->where('departement_id', $serviceConnecte);
                    })->value('id')),

                Card::make()
                    ->schema([
                        Repeater::make('permanenceUsers')
                            ->addActionLabel('Ajouter un jour de permanence')
                            ->label('Jours de permanence')
                            ->itemLabel(function (array $state): ?string {
                                if (!$state['date']) {
                                    return null;

                                } else {
                                    return Carbon::parse($state['date'])->TranslatedFormat('l, \le d M Y');
                                }
                            })
                            ->relationship('permanenceUsers')
                            ->schema([
                                DatePicker::make('date')
                                    ->native(false)
                                    ->required(),
                                repeater::make("user_id")
                                    ->label('')
                                    ->addActionLabel('Ajouter un agent')
                                    ->schema([
                                        Select::make('service')
                                            ->label('Service')
                                            // ->dehydrated(false)
                                            ->options(Service::whereHas('departement', function ($query) use ($serviceConnecte) {
                                                $query->where('departement_id', $serviceConnecte);
                                            })->pluck('nom_service', 'services.id'))
                                            ->native(false)
                                            ->live(),

                                        Select::make('users')
                                        // ->dehydrated(false)
                                            ->native(false)
                                            ->required()
                                            ->label('Agent')
                                            ->preload()
                                            ->options(
                                                fn(Get $get): Collection => User::query()
                                                    ->where('service_id', $get('service'))
                                                    ->pluck('name', 'users.id')
                                            )
                                        ,
                                    ])->minItems($nombreDeServices)
                                    ->maxItems($nombreDeServices),

                            ])

                            ->grid(2)
                            ->reorderable(false)
                            ->collapsible()
                    ])
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date_debut')
                    ->label('Date de début')
                    ->date('d-m-Y'),

                TextColumn::make('date_fin')
                    ->label('Date de fin')
                    ->date('d-m-Y'),

                TextColumn::make('departement')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn(Permanence $record) => route('permanence.pdf.ddl', $record))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListPermanences::route('/'),
            'create' => Pages\CreatePermanence::route('/create'),
            'edit' => Pages\EditPermanence::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PermanenceList::class,
        ];
    }

    public static function canViewAny(): bool
    {

        return auth()->user()->hasAnyPermission([
            PermissionsClass::permanences_create()->value,
            PermissionsClass::permanences_read()->value,
            PermissionsClass::permanences_update()->value
        ]);
    }
}
