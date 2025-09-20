<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('types_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Matériel, Logiciel, Prestation de service, Abonnement
            $table->string('description')->nullable();
            $table->string('couleur')->default('#007bff'); // Couleur pour l'affichage
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_commandes');
    }
};
