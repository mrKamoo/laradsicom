<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    protected $fillable = [
        'nom',
        'code_postal',
        'code_insee',
        'adresse',
        'telephone',
        'email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function prescripteurs(): HasMany
    {
        return $this->hasMany(Prescripteur::class);
    }

    public function prescripteursActifs(): HasMany
    {
        return $this->hasMany(Prescripteur::class)->where('active', true);
    }

    public function scopeActives($query)
    {
        return $query->where('active', true);
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