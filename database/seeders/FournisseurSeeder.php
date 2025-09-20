<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = [
            [
                'nom' => 'Dell Technologies France',
                'adresse' => '1 Rond Point Benjamin Franklin',
                'code_postal' => '34000',
                'ville' => 'Montpellier',
                'telephone' => '0800 956 444',
                'email' => 'contact@dell.fr',
                'contact_commercial' => 'Jean Dupont',
                'telephone_commercial' => '06 12 34 56 78',
                'email_commercial' => 'j.dupont@dell.fr'
            ],
            [
                'nom' => 'Microsoft France',
                'adresse' => '18 Avenue du Québec',
                'code_postal' => '91140',
                'ville' => 'Villebon-sur-Yvette',
                'telephone' => '0825 827 829',
                'email' => 'contact@microsoft.fr',
                'contact_commercial' => 'Marie Martin',
                'telephone_commercial' => '06 98 76 54 32',
                'email_commercial' => 'marie.martin@microsoft.fr'
            ],
            [
                'nom' => 'Bechtle France',
                'adresse' => '200 Avenue Aristide Briand',
                'code_postal' => '92220',
                'ville' => 'Bagneux',
                'telephone' => '01 49 65 80 00',
                'email' => 'info@bechtle.fr',
                'contact_commercial' => 'Pierre Leblanc',
                'telephone_commercial' => '06 11 22 33 44',
                'email_commercial' => 'pierre.leblanc@bechtle.fr'
            ],
            [
                'nom' => 'Orange Business Services',
                'adresse' => '78 Rue Olivier de Serres',
                'code_postal' => '75015',
                'ville' => 'Paris',
                'telephone' => '0800 054 055',
                'email' => 'contact@orange-business.com',
                'contact_commercial' => 'Sophie Durand',
                'telephone_commercial' => '06 55 44 33 22',
                'email_commercial' => 'sophie.durand@orange-business.com'
            ]
        ];

        foreach ($fournisseurs as $fournisseur) {
            Fournisseur::updateOrCreate(
                ['nom' => $fournisseur['nom']],
                $fournisseur
            );
        }
    }
}