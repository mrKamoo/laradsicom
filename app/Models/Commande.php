<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Commande extends Model
{
    protected $fillable = [
        'numero_commande',
        'prescripteur_id',
        'type_commande_id',
        'user_id',
        'objet',
        'description',
        'justification',
        'date_demande',
        'date_souhaitee',
        'urgent',
        'statut',
        'montant_estime',
        'montant_final',
        'imputation_budgetaire',
        'date_validation',
        'date_commande',
        'date_livraison',
        'date_cloture',
        'notes_internes',
        'notes_prescripteur',
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_souhaitee' => 'date',
        'date_validation' => 'date',
        'date_commande' => 'date',
        'date_livraison' => 'date',
        'date_cloture' => 'date',
        'urgent' => 'boolean',
        'montant_estime' => 'decimal:2',
        'montant_final' => 'decimal:2',
    ];

    public function prescripteur(): BelongsTo
    {
        return $this->belongsTo(Prescripteur::class);
    }

    public function typeCommande(): BelongsTo
    {
        return $this->belongsTo(TypeCommande::class);
    }

    public function gestionnaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    public function devisRetenu()
    {
        return $this->hasOne(Devis::class)->where('selectionne', true);
    }

    public function devisSelectionne()
    {
        return $this->hasOne(Devis::class)->where('selectionne', true);
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->whereNotIn('statut', ['cloture']);
    }

    public function scopeUrgentes($query)
    {
        return $query->where('urgent', true);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Accesseurs
    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'demande' => 'Demande reçue',
            'devis' => 'En attente de devis',
            'validation' => 'En attente de validation',
            'commande' => 'Bon de commande émis',
            'livraison' => 'En cours de livraison',
            'cloture' => 'Clôturée',
            default => 'Inconnu'
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        $class = match($this->statut) {
            'demande' => 'bg-secondary',
            'devis' => 'bg-warning',
            'validation' => 'bg-info',
            'commande' => 'bg-primary',
            'livraison' => 'bg-success',
            'cloture' => 'bg-dark',
            default => 'bg-secondary'
        };

        return '<span class="badge ' . $class . '">' . $this->statut_label . '</span>';
    }

    public function getDureeTraitementAttribute(): ?int
    {
        if ($this->statut === 'cloture' && $this->date_cloture) {
            return $this->date_demande->diffInDays($this->date_cloture);
        }
        return $this->date_demande->diffInDays(now());
    }

    // Méthodes de workflow
    public function peutPasserAuStatut($nouveauStatut): bool
    {
        $transitions = [
            'demande' => ['devis'],
            'devis' => ['validation', 'demande'],
            'validation' => ['commande', 'devis'],
            'commande' => ['livraison'],
            'livraison' => ['cloture'],
            'cloture' => []
        ];

        return in_array($nouveauStatut, $transitions[$this->statut] ?? []);
    }

    public function changerStatut($nouveauStatut, $notes = null): bool
    {
        if (!$this->peutPasserAuStatut($nouveauStatut)) {
            return false;
        }

        $this->statut = $nouveauStatut;

        // Mise à jour automatique des dates
        switch($nouveauStatut) {
            case 'validation':
                if (!$this->date_validation) {
                    $this->date_validation = now();
                }
                break;
            case 'commande':
                if (!$this->date_commande) {
                    $this->date_commande = now();
                }
                break;
            case 'livraison':
                if (!$this->date_livraison) {
                    $this->date_livraison = now();
                }
                break;
            case 'cloture':
                if (!$this->date_cloture) {
                    $this->date_cloture = now();
                }
                break;
        }

        if ($notes) {
            $this->notes_internes = ($this->notes_internes ? $this->notes_internes . "\n\n" : '') .
                                   '[' . now()->format('d/m/Y H:i') . '] ' . $notes;
        }

        return $this->save();
    }

    protected static function genererNumeroCommande(): string
    {
        return DB::transaction(function () {
            $annee = date('Y');
            $dernierNumero = static::whereYear('created_at', $annee)
                ->lockForUpdate()
                ->max('numero_commande');

            if ($dernierNumero) {
                // Extraire le numéro de la dernière commande (ex: CMD-2025-0004 -> 4)
                $dernierNumeroInt = (int) substr($dernierNumero, -4);
                $nouveauNumero = $dernierNumeroInt + 1;
            } else {
                $nouveauNumero = 1;
            }

            return 'CMD-' . $annee . '-' . str_pad($nouveauNumero, 4, '0', STR_PAD_LEFT);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commande) {
            if (!$commande->numero_commande) {
                $commande->numero_commande = static::genererNumeroCommande();
            }
        });
    }
}