<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $skills = Skill::all();

        // ─── 1. ADMIN ────────────────────────────────────────
        User::factory()->admin()->create([
            'name'  => 'Admin DevJobs',
            'email' => 'admin@devjobs.com',
        ]);
        // On crée un admin avec des données fixes pour pouvoir se connecter facilement en test

        // ─── 2. CANDIDATS ─────────────────────────────────────
        User::factory()->candidate()->count(10)->create()->each(function ($user) use ($skills) {
            // Pour chaque user candidat, on crée son profil Candidate
            $candidate = Candidate::factory()->create(['user_id' => $user->id]);

            // On lui attache 2 à 5 compétences aléatoires (Many-to-Many)
            $candidate->skills()->attach(
                $skills->random(fake()->numberBetween(2, 5))->pluck('id')->toArray()
            );
        });

        // ─── 3. ENTREPRISES ───────────────────────────────────
        User::factory()->company()->count(5)->create()->each(function ($user) use ($skills) {
            // Pour chaque user company, on crée son profil Company
            $company = Company::factory()->create(['user_id' => $user->id]);

            // Chaque entreprise publie 3 à 6 offres
            JobOffer::factory()->count(fake()->numberBetween(3, 6))->create([
                'company_id' => $company->id,
            ])->each(function ($offer) use ($skills) {
                // Chaque offre requiert 2 à 4 compétences
                $offer->skills()->attach(
                    $skills->random(fake()->numberBetween(2, 4))->pluck('id')->toArray()
                );
            });
        });

        // ─── 4. CANDIDATURES ──────────────────────────────────
        $candidates = Candidate::all();
        $jobOffers  = JobOffer::all();

        // Chaque candidat postule à 1 à 3 offres différentes
        $candidates->each(function ($candidate) use ($jobOffers) {
            // On prend des offres aléatoires uniques pour ce candidat
            $offersToApply = $jobOffers->random(min(3, $jobOffers->count()));

            foreach ($offersToApply as $offer) {
                // firstOrCreate respecte la contrainte UNIQUE (candidate_id, job_offer_id)
                Application::firstOrCreate(
                    [
                        'candidate_id' => $candidate->id,
                        'job_offer_id' => $offer->id,
                    ],
                    [
                        'cover_letter' => fake()->paragraph(),
                        'status'       => 'pending',
                    ]
                );
            }
        });
    }
}
