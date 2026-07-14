<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory()->company(),
            'company_name' => fake()->company(),
            // company() génère des noms réalistes : "Dupont & Associés SARL"
            'siret'        => fake()->numerify('##############'),
            // numerify('##') remplace # par un chiffre → 14 chiffres
            'website'      => 'https://www.' . fake()->domainName(),
            'description'  => fake()->paragraph(4),
            'address'      => fake()->streetAddress(),
            'city'         => fake()->city(),
        ];
    }
}
