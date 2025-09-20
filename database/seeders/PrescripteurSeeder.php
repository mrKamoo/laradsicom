<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescripteur;
use App\Models\Commune;

class PrescripteurSeeder extends Seeder
{
    public function run(): void
    {
        $communes = Commune::all()->keyBy('nom');

        $prescripteurs = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'fonction' => 'Directeur des Services Informatiques',
                'service' => 'Direction Générale',
                'telephone' => '04 67 36 71 75',
                'email' => 'j.dupont@ville-beziers.fr',
                'commune_id' => $communes['Béziers']->id ?? null,
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'fonction' => 'Responsable Informatique',
                'service' => 'Services Techniques',
                'telephone' => '04 67 39 61 15',
                'email' => 'm.martin@villeneuve-les-beziers.fr',
                'commune_id' => $communes['Villeneuve-lès-Béziers']->id ?? null,
            ],
            [
                'nom' => 'Leblanc',
                'prenom' => 'Pierre',
                'fonction' => 'Technicien Informatique',
                'service' => 'Mairie',
                'telephone' => '04 67 32 33 98',
                'email' => 'p.leblanc@ville-serignan.fr',
                'commune_id' => $communes['Sérignan']->id ?? null,
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Sophie',
                'fonction' => 'Secrétaire de Mairie',
                'service' => 'Administration',
                'telephone' => '04 67 32 36 08',
                'email' => 's.durand@valras-plage.fr',
                'commune_id' => $communes['Valras-Plage']->id ?? null,
            ],
            [
                'nom' => 'Moreau',
                'prenom' => 'Alain',
                'fonction' => 'Maire',
                'service' => 'Élus',
                'telephone' => '04 67 39 81 20',
                'email' => 'a.moreau@sauvian.fr',
                'commune_id' => $communes['Sauvian']->id ?? null,
            ],
        ];

        foreach ($prescripteurs as $prescripteur) {
            if ($prescripteur['commune_id']) {
                Prescripteur::updateOrCreate(
                    ['email' => $prescripteur['email']],
                    $prescripteur
                );
            }
        }
    }
}