<?php

namespace App\Filament\Resources\PermanenceResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PermanenceList extends Widget
{
    protected static string $view = 'filament.resources.permanence-resource.widgets.permanence-list';
    protected int | string | array $columnSpan = 'full';

    public ?Model $record = null;
}
