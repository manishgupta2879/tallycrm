<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pid'          => strtoupper(fake()->unique()->lexify('CO-####')),
            'name'         => fake()->company(),
            'contact_name' => fake()->name(),
            'designation'  => fake()->jobTitle(),
            'email'        => fake()->unique()->companyEmail(),
            'mobile'       => fake()->numerify('9#########'),
            'territory'    => fake()->state(),
            'status'       => 'Active',
            'd_types'      => ['Distributor'],
            'd_parameter'  => [],
            'c_urls'       => null,
        ];
    }
}
