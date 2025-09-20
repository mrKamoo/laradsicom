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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');

            $table->string('numero_devis');
            $table->date('date_devis');
            $table->date('date_validite')->nullable();
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('montant_tva', 10, 2)->nullable();
            $table->decimal('montant_ttc', 10, 2);
            $table->integer('delai_livraison')->nullable(); // en jours
            $table->text('conditions_particulieres')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('retenu')->default(false); // Devis sélectionné
            $table->text('fichier_devis')->nullable(); // Chemin vers le fichier PDF

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
