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
            // Ajouter les colonnes manquantes
            $table->decimal('taux_tva', 5, 2)->after('montant_ht');
            $table->text('description')->nullable()->after('delai_livraison');
            $table->boolean('garanti')->default(false)->after('description');
            $table->boolean('installation_incluse')->default(false)->after('garanti');
            $table->boolean('selectionne')->default(false)->after('installation_incluse');
            $table->timestamp('date_selection')->nullable()->after('selectionne');

            // Modifier la colonne retenu pour qu'elle soit nullable et renommée
            $table->dropColumn('retenu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'taux_tva',
                'description',
                'garanti',
                'installation_incluse',
                'selectionne',
                'date_selection'
            ]);

            // Remettre la colonne retenu
            $table->boolean('retenu')->default(false);
        });
    }
};
