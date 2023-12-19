<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Departement extends Model
{
    use HasFactory;

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Service::class);
    }
}
