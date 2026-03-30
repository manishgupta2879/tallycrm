<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TallyLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            \App\Models\TallyLog::create([
                'tally_serial_no' => (string)rand(100000000, 999999999),
                'tally_version' => 'TallyPrime Series A',
                'tally_release' => '5.' . rand(0, 5),
                'tally_edition' => rand(0, 1) ? 'Silver' : 'Gold',
                'account_id' => 'user_' . rand(100, 999) . '@example.com',
                'tss_expiry_date' => now()->addMonths(rand(1, 12))->format('d-M-Y'),
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
