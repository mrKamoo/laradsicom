<?php

namespace Tests\Feature;

use App\Models\Commande;
use App\Models\Devis;
use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DevisTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_devis(): void
    {
        // Créer un utilisateur et se connecter
        $user = User::factory()->create();
        $this->actingAs($user);

        // Créer les données nécessaires
        $commande = Commande::factory()->create();
        $fournisseur = Fournisseur::factory()->create();

        // Données du devis
        $devisData = [
            'commande_id' => $commande->id,
            'fournisseur_id' => $fournisseur->id,
            'numero_devis' => 'DEVIS-TEST-001',
            'date_devis' => '2025-09-14',
            'date_validite' => '2025-12-31',
            'montant_ht' => 1000.00,
            'taux_tva' => 20.00,
            'montant_tva' => 200.00,
            'montant_ttc' => 1200.00,
            'delai_livraison' => 15,
            'description' => 'Description du devis de test',
            'conditions_particulieres' => 'Conditions particulières',
            'garanti' => true,
            'installation_incluse' => false,
        ];

        // Tester la création du devis
        $response = $this->post(route('devis.store', $commande), $devisData);

        // Vérifier que le devis a été créé
        $this->assertDatabaseHas('devis', [
            'numero_devis' => 'DEVIS-TEST-001',
            'commande_id' => $commande->id,
            'fournisseur_id' => $fournisseur->id,
        ]);

        // Vérifier la redirection
        $response->assertRedirect(route('commandes.show', $commande));
    }

    public function test_can_view_create_devis_form(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $commande = Commande::factory()->create();

        $response = $this->get(route('devis.create', $commande));

        $response->assertStatus(200);
        $response->assertSee('Nouveau devis pour la commande');
    }

    public function test_devis_calculates_amounts_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $commande = Commande::factory()->create();
        $fournisseur = Fournisseur::factory()->create();

        $devisData = [
            'commande_id' => $commande->id,
            'fournisseur_id' => $fournisseur->id,
            'numero_devis' => 'DEVIS-CALC-001',
            'date_devis' => '2025-09-14',
            'montant_ht' => 500.00,
            'taux_tva' => 20.00,
            'montant_tva' => 100.00,
            'montant_ttc' => 600.00,
        ];

        $this->post(route('devis.store', $commande), $devisData);

        $devis = Devis::where('numero_devis', 'DEVIS-CALC-001')->first();

        $this->assertEquals(500.00, $devis->montant_ht);
        $this->assertEquals(100.00, $devis->montant_tva);
        $this->assertEquals(600.00, $devis->montant_ttc);
        $this->assertEquals(20.00, $devis->taux_tva);
    }

    public function test_can_select_devis(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $devis = Devis::factory()->create();

        $response = $this->patch(route('devis.select', $devis));

        $response->assertRedirect(route('commandes.show', $devis->commande));

        $devis->refresh();
        $this->assertTrue($devis->selectionne);
        $this->assertNotNull($devis->date_selection);
    }
}
