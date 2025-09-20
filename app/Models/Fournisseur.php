<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom',
        'raison_sociale',
        'siret',
        'adresse',
        'code_postal',
        'ville',
        'telephone',
        'email',
        'contact_commercial',
        'email_commercial',
        'telephone_commercial',
        'notes',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function getAdresseCompleteAttribute(): string
    {
        $adresse = $this->adresse;
        if ($this->code_postal && $this->ville) {
            $adresse .= ', ' . $this->code_postal . ' ' . $this->ville;
        }
        return $adresse;
    }

    public function getContactCompletAttribute(): string
    {
        if ($this->contact_commercial) {
            $contact = $this->contact_commercial;
            if ($this->email_commercial) {
                $contact .= ' (' . $this->email_commercial . ')';
            }
            return $contact;
        }
        return $this->email ?? 'Aucun contact';
    }

    // Accesseurs pour éviter les erreurs format() sur null
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y à H:i') : '-';
    }

    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y à H:i') : '-';
    }
}