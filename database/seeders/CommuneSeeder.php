<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        $communes = [
            ['nom' => 'Béziers', 'code_postal' => '34500', 'adresse' => '1 Avenue Saint-Saëns', 'telephone' => '04 67 36 71 71', 'email' => 'contact@ville-beziers.fr'],
            ['nom' => 'Villeneuve-lès-Béziers', 'code_postal' => '34420', 'adresse' => 'Place de la Mairie', 'telephone' => '04 67 39 61 10', 'email' => 'mairie@villeneuve-les-beziers.fr'],
            ['nom' => 'Sérignan', 'code_postal' => '34410', 'adresse' => '37 Avenue de la République', 'telephone' => '04 67 32 33 95', 'email' => 'mairie@ville-serignan.fr'],
            ['nom' => 'Valras-Plage', 'code_postal' => '34350', 'adresse' => 'Avenue des Elysées', 'telephone' => '04 67 32 36 04', 'email' => 'contact@valras-plage.fr'],
            ['nom' => 'Sauvian', 'code_postal' => '34410', 'adresse' => 'Place Jean Jaurès', 'telephone' => '04 67 39 81 17', 'email' => 'mairie@sauvian.fr'],
        ];

        foreach ($communes as $commune) {
            Commune::updateOrCreate(
                ['nom' => $commune['nom']],
                $commune
            );
        }
    }
}