<?php

namespace App\Models;

use App\Models\User;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
