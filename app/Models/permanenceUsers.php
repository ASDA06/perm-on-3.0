<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permanence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class permanenceUsers extends Pivot
{
    use HasFactory;
    public function permanence(): BelongsTo
    {
        return $this->belongsTo(Permanence::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     protected $casts = [
        'user_id' => 'array', 
     ];
}
