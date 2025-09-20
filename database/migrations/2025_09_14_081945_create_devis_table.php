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
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('restrict');

            $table->string('numero_devis');
            $table->date('date_devis');
            $table->date('date_validite')->nullable();
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2)->default(20.00);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->integer('delai_livraison')->nullable();
            $table->text('description')->nullable();
            $table->text('conditions_particulieres')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('garanti')->default(false);
            $table->boolean('installation_incluse')->default(false);
            $table->boolean('selectionne')->default(false);
            $table->timestamp('date_selection')->nullable();
            $table->string('fichier_devis')->nullable();
            $table->string('nom_fichier_original')->nullable();

            $table->timestamps();

            $table->index(['commande_id', 'selectionne']);
            $table->index('date_validite');
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
