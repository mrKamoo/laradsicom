<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeCommande;

class TypeCommandeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nom' => 'Matériel informatique',
                'description' => 'Ordinateurs, serveurs, périphériques, composants',
                'couleur' => '#007bff'
            ],
            [
                'nom' => 'Logiciels',
                'description' => 'Licences logicielles, applications, antivirus',
                'couleur' => '#28a745'
            ],
            [
                'nom' => 'Services',
                'description' => 'Maintenance, formation, conseil, développement',
                'couleur' => '#17a2b8'
            ],
            [
                'nom' => 'Abonnements',
                'description' => 'Office 365, cloud, SaaS, télécommunications',
                'couleur' => '#ffc107'
            ],
            [
                'nom' => 'Consommables',
                'description' => 'Cartouches, papier, supports de stockage',
                'couleur' => '#6c757d'
            ],
            [
                'nom' => 'Infrastructure',
                'description' => 'Réseau, téléphonie, sécurité, datacenter',
                'couleur' => '#fd7e14'
            ]
        ];

        foreach ($types as $type) {
            TypeCommande::updateOrCreate(
                ['nom' => $type['nom']],
                $type
            );
        }
    }
}