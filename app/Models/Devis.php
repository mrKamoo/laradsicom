<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Devis extends Model
{
    protected $fillable = [
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
        'nom_fichier_original',
    ];

    protected $casts = [
        'date_devis' => 'date',
        'date_validite' => 'date',
        'date_selection' => 'datetime',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'selectionne' => 'boolean',
        'garanti' => 'boolean',
        'installation_incluse' => 'boolean',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    // Scopes
    public function scopeSelectionnes($query)
    {
        return $query->where('selectionne', true);
    }

    public function scopeValides($query)
    {
        return $query->where('date_validite', '>=', now());
    }

    // Accesseurs
    public function getEstValideAttribute(): bool
    {
        return $this->date_validite ? $this->date_validite->isFuture() : true;
    }

    public function getDelaiLivraisonFormatteAttribute(): string
    {
        if (!$this->delai_livraison) {
            return 'Non spécifié';
        }

        if ($this->delai_livraison == 1) {
            return '1 jour';
        }

        if ($this->delai_livraison <= 7) {
            return $this->delai_livraison . ' jours';
        }

        $semaines = intval($this->delai_livraison / 7);
        $joursRestants = $this->delai_livraison % 7;

        $resultat = $semaines . ' semaine' . ($semaines > 1 ? 's' : '');
        if ($joursRestants > 0) {
            $resultat .= ' et ' . $joursRestants . ' jour' . ($joursRestants > 1 ? 's' : '');
        }

        return $resultat;
    }

    public function getTauxTvaAttribute(): float
    {
        if ($this->montant_ht > 0) {
            return ($this->montant_tva / $this->montant_ht) * 100;
        }
        return 0;
    }

    public function getStatutBadgeAttribute(): string
    {
        if ($this->selectionne) {
            return '<span class="badge bg-success">Sélectionné</span>';
        }

        if (!$this->est_valide) {
            return '<span class="badge bg-danger">Expiré</span>';
        }

        return '<span class="badge bg-secondary">En attente</span>';
    }

    // Méthodes
    public function selectionner(): bool
    {
        // Déselectionner tous les autres devis de cette commande
        static::where('commande_id', $this->commande_id)
              ->where('id', '!=', $this->id)
              ->update(['selectionne' => false, 'date_selection' => null]);

        // Sélectionner ce devis
        $this->selectionne = true;
        $this->date_selection = now();
        $resultat = $this->save();

        // Mettre à jour le montant final de la commande
        if ($resultat) {
            $this->commande()->update(['montant_final' => $this->montant_ttc]);
        }

        return $resultat;
    }

    // Méthode pour compatibilité
    public function retenir(): bool
    {
        return $this->selectionner();
    }
}