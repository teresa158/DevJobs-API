<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Point d'entrée de tous les seeders.
     * L'ORDRE est important :
     *   1. SkillSeeder  → les skills doivent exister avant les users/offres
     *   2. UserSeeder   → crée users, candidates, companies, offers, applications
     *   3. JobOfferSeeder → vide, conservé pour structure
     */
    public function run(): void
    {
        $this->call([
            SkillSeeder::class,
            UserSeeder::class,
            JobOfferSeeder::class,
        ]);
    }
}
