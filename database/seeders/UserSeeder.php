<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@agglobeziers.fr'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@agglobeziers.fr',
                'password' => \Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
