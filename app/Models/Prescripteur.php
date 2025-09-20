<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescripteur extends Model
{
    protected $fillable = [
        'commune_id',
        'nom',
        'prenom',
        'fonction',
        'service',
        'email',
        'telephone',
        'telephone_mobile',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getIdentiteCompleteAttribute(): string
    {
        $identite = $this->nom_complet;
        if ($this->fonction) {
            $identite .= ' - ' . $this->fonction;
        }
        if ($this->service) {
            $identite .= ' (' . $this->service . ')';
        }
        return $identite;
    }

    // Accesseurs pour éviter les erreurs format() sur null
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y') : '-';
    }
}