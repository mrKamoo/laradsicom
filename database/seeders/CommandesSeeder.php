<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandesSeeder extends Seeder
{
    public function run(): void
    {
        // Types de commandes
        $typesCommandes = [
            ['nom' => 'Matériel', 'description' => 'Équipements informatiques, périphériques', 'couleur' => '#007bff'],
            ['nom' => 'Logiciel', 'description' => 'Licences logicielles, applications', 'couleur' => '#28a745'],
            ['nom' => 'Prestation de service', 'description' => 'Services externes, maintenance', 'couleur' => '#ffc107'],
            ['nom' => 'Abonnement', 'description' => 'Abonnements récurrents (O365, etc.)', 'couleur' => '#dc3545'],
        ];

        foreach ($typesCommandes as $type) {
            DB::table('types_commandes')->insert(array_merge($type, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Communes d'exemple
        $communes = [
            ['nom' => 'Béziers', 'code_postal' => '34500', 'code_insee' => '34032'],
            ['nom' => 'Villeneuve-lès-Béziers', 'code_postal' => '34420', 'code_insee' => '34327'],
            ['nom' => 'Sérignan', 'code_postal' => '34410', 'code_insee' => '34299'],
            ['nom' => 'Valras-Plage', 'code_postal' => '34350', 'code_insee' => '34324'],
            ['nom' => 'Portiragnes', 'code_postal' => '34420', 'code_insee' => '34213'],
        ];

        foreach ($communes as $commune) {
            DB::table('communes')->insert(array_merge($commune, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Fournisseurs d'exemple
        $fournisseurs = [
            [
                'nom' => 'Dell Technologies',
                'raison_sociale' => 'Dell Technologies France SAS',
                'adresse' => '1 Rond-Point Benjamin Franklin',
                'code_postal' => '34000',
                'ville' => 'Montpellier',
                'email' => 'contact@dell.fr',
                'contact_commercial' => 'Jean Dupont',
                'email_commercial' => 'jean.dupont@dell.fr',
            ],
            [
                'nom' => 'Microsoft France',
                'raison_sociale' => 'Microsoft France',
                'adresse' => '37 Quai du Président Roosevelt',
                'code_postal' => '92130',
                'ville' => 'Issy-les-Moulineaux',
                'email' => 'contact@microsoft.fr',
                'contact_commercial' => 'Marie Martin',
                'email_commercial' => 'marie.martin@microsoft.fr',
            ],
            [
                'nom' => 'Orange Business Services',
                'raison_sociale' => 'Orange Business Services',
                'adresse' => '1 Avenue du Président Wilson',
                'code_postal' => '34000',
                'ville' => 'Montpellier',
                'email' => 'contact@orange-business.com',
                'contact_commercial' => 'Pierre Durand',
                'email_commercial' => 'pierre.durand@orange-business.com',
            ],
        ];

        foreach ($fournisseurs as $fournisseur) {
            DB::table('fournisseurs')->insert(array_merge($fournisseur, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Prescripteurs d'exemple
        $prescripteurs = [
            [
                'commune_id' => 1, // Béziers
                'nom' => 'Dubois',
                'prenom' => 'Jean',
                'fonction' => 'Responsable informatique',
                'service' => 'Direction des Services Techniques',
                'email' => 'jean.dubois@ville-beziers.fr',
                'telephone' => '04.67.36.71.72',
            ],
            [
                'commune_id' => 2, // Villeneuve-lès-Béziers
                'nom' => 'Moreau',
                'prenom' => 'Sophie',
                'fonction' => 'Secrétaire générale',
                'service' => 'Direction générale',
                'email' => 'sophie.moreau@villeneuve-les-beziers.fr',
                'telephone' => '04.67.35.20.21',
            ],
            [
                'commune_id' => 3, // Sérignan
                'nom' => 'Lemaire',
                'prenom' => 'Paul',
                'fonction' => 'Adjoint technique',
                'service' => 'Services techniques',
                'email' => 'paul.lemaire@serignan.fr',
                'telephone' => '04.67.32.45.67',
            ],
        ];

        foreach ($prescripteurs as $prescripteur) {
            DB::table('prescripteurs')->insert(array_merge($prescripteur, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}