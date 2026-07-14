<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    public function definition(): array
    {
        return [
            // user_id sera passé par le Seeder, mais on met un défaut ici
            'user_id'          => User::factory()->candidate(),
            'bio'              => fake()->paragraph(3),
            // paragraph(3) génère 3 phrases aléatoires réalistes
            'github_url'       => 'https://github.com/' . fake()->userName(),
            'portfolio_url'    => fake()->boolean(60)
                                    ? 'https://' . fake()->domainName()
                                    : null,
            // boolean(60) → 60% de chance d'avoir un portfolio, 40% null
            'years_experience' => fake()->numberBetween(0, 15),
        ];
    }
}
