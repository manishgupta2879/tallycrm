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
        $distributor = \App\Models\Distributor::first();

        $logs = [
            [
                'pid' => 'SONNY001',
                'distributor_id' => '',
                'tally_serial_no' => '746470602',
                'tally_version' => 'TallyPrime Series A',
                'tally_release' => '5.1',
                'tally_edition' => 'Silver',
                'account_id' => 'vidya21bhushan@gmail.com',
                'tss_expiry_date' => '28-Feb-2025',
                'created_at' => now(),
            ],
            [
                'pid' => $distributor ? $distributor->company_code : 'SONNY001',
                'distributor_id' => $distributor ? $distributor->code : '1010974',
                'tally_serial_no' => '987654321',
                'tally_version' => 'TallyPrime Series A',
                'tally_release' => '5.0',
                'tally_edition' => 'Gold',
                'account_id' => 'test@example.com',
                'tss_expiry_date' => '31-Mar-2025',
                'created_at' => now(),
            ]
        ];

        foreach ($logs as $log) {
            \App\Models\TallyLog::create($log);
        }
    }
}
