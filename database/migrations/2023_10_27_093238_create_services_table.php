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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nom_service');
            $table->foreignId('departement_id')->constrained();
            $table->timestamps();
            $table->enum('statut',[
                StatesClass::Inactive()->value,
                StatesClass::Active()->value]
            )->default(StatesClass::Active()->value);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
