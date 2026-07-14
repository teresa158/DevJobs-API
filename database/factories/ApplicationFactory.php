<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Candidate;
use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::factory(),
            'job_offer_id' => JobOffer::factory(),
            'cover_letter' => fake()->boolean(75)
                                ? fake()->paragraphs(2, true)
                                : null,
            // 75% des candidatures ont une lettre de motivation
            'status'       => fake()->randomElement(
                                array_column(ApplicationStatus::cases(), 'value')
                              ),
            // ['pending', 'accepted', 'rejected']
        ];
    }
}
