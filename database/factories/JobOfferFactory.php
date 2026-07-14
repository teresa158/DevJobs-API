<?php

namespace Database\Factories;

use App\Enums\ContractType;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    // Titres de postes réalistes pour une plateforme tech
    private array $titles = [
        'Développeur PHP / Laravel',
        'Développeur Full Stack React/Node.js',
        'Développeur Backend Python',
        'Développeur Frontend Vue.js',
        'Ingénieur DevOps',
        'Développeur Mobile Flutter',
        'Architecte Logiciel',
        'Tech Lead Backend',
        'Développeur API REST',
        'Développeur WordPress',
    ];

    public function definition(): array
    {
        $salaryMin = fake()->numberBetween(30, 60) * 1000; // ex: 42000
        $salaryMax = $salaryMin + fake()->numberBetween(5, 20) * 1000; // toujours > min

        return [
            'company_id'    => Company::factory(),
            'title'         => fake()->randomElement($this->titles),
            'description'   => fake()->paragraphs(4, true),
            // paragraphs(4, true) → 4 paragraphes concaténés en une string
            'salary_min'    => $salaryMin,
            'salary_max'    => $salaryMax,
            'location'      => fake()->randomElement([
                                'Paris', 'Lyon', 'Marseille',
                                'Bordeaux', 'Toulouse', 'Remote',
                               ]),
            'contract_type' => fake()->randomElement(
                                array_column(ContractType::cases(), 'value')
                               ),
            // array_column(ContractType::cases(), 'value') → ['CDI','CDD','freelance','internship']
            'status'        => fake()->randomElement(['open', 'closed']),
        ];
    }

    // État : seulement les offres ouvertes
    public function open(): static
    {
        return $this->state(['status' => 'open']);
    }
}
