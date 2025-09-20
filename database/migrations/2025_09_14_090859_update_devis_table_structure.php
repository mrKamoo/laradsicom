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
        Schema::table('devis', function (Blueprint $table) {
            $table->unsignedBigInteger('commande_id')->index();
            $table->unsignedBigInteger('fournisseur_id')->index();
            $table->string('numero_devis');
            $table->date('date_devis');
            $table->date('date_validite')->nullable();
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->integer('delai_livraison')->nullable();
            $table->text('description')->nullable();
            $table->text('conditions_particulieres')->nullable();
            $table->boolean('garanti')->default(false);
            $table->boolean('installation_incluse')->default(false);
            $table->boolean('selectionne')->default(false);
            $table->timestamp('date_selection')->nullable();
            $table->string('fichier_devis')->nullable();
            $table->string('nom_fichier_original')->nullable();

            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('restrict');

            $table->index(['commande_id', 'selectionne']);
            $table->index('date_validite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->dropForeign(['fournisseur_id']);
            $table->dropIndex(['commande_id', 'selectionne']);
            $table->dropIndex(['date_validite']);

            $table->dropColumn([
                'commande_id',
                'fournisseur_id',
                'numero_devis',
                'date_devis',
                'date_validite',
                'montant_ht',
                'taux_tva',
                'montant_tva',
                'montant_ttc',
                'delai_livraison',
                'description',
                'conditions_particulieres',
                'garanti',
                'installation_incluse',
                'selectionne',
                'date_selection',
                'fichier_devis',
                'nom_fichier_original'
            ]);
        });
    }
};
