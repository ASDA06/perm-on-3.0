<?php

use App\Enums\StatesClass;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('user_id')->constrained();
            $table->time('heure_arrivee');
            $table->time('heure_depart')->nullable();
            $table->timestamps();
            $table->enum('statut',[
                StatesClass::Inactive()->value,
                StatesClass::Active()->value]
            );

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
