<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'         => strtoupper(fake()->unique()->lexify('DT-####')),
            'name'         => fake()->company() . ' Distributors',
            'company_code' => 'CO-TEST01',
            'type'         => fake()->randomElement(['Platinum', 'Gold', 'Silver']),
            'address'      => fake()->address(),
            'country'      => '1',
            'region'       => '1',
            'state'        => '1',
            'city'         => '1',
            'pincode'      => fake()->numerify('######'),
            'gst_number'   => '27AAPFU0939F1ZV',  // Valid format for tests
            'pan_number'   => 'AAPFU0939F',        // Valid format for tests
            'status'       => 'Active',
            'params'       => [],
        ];
    }
}
