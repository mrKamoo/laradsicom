<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeCommande extends Model
{
    protected $table = 'types_commandes';

    protected $fillable = [
        'nom',
        'description',
        'couleur',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function getBadgeHtmlAttribute(): string
    {
        return '<span class="badge" style="background-color: ' . $this->couleur . '">' . $this->nom . '</span>';
    }
}