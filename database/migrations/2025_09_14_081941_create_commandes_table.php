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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->foreignId('prescripteur_id')->constrained('prescripteurs')->onDelete('cascade');
            $table->foreignId('type_commande_id')->constrained('types_commandes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Gestionnaire

            // Informations de la demande
            $table->string('objet');
            $table->text('description');
            $table->text('justification')->nullable();
            $table->date('date_demande');
            $table->date('date_souhaitee')->nullable();
            $table->boolean('urgent')->default(false);

            // Statut de la commande
            $table->enum('statut', [
                'demande',      // Demande reçue
                'devis',        // En attente de devis
                'validation',   // En attente validation prescripteur
                'commande',     // Bon de commande émis
                'livraison',    // En cours de livraison/exécution
                'cloture'       // Commande clôturée
            ])->default('demande');

            // Informations financières
            $table->decimal('montant_estime', 10, 2)->nullable();
            $table->decimal('montant_final', 10, 2)->nullable();
            $table->string('imputation_budgetaire')->nullable();

            // Dates de suivi
            $table->date('date_validation')->nullable();
            $table->date('date_commande')->nullable();
            $table->date('date_livraison')->nullable();
            $table->date('date_cloture')->nullable();

            // Notes et observations
            $table->text('notes_internes')->nullable();
            $table->text('notes_prescripteur')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
