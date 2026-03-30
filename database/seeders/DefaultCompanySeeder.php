<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class DefaultCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::updateOrCreate(
            ['pid' => 'UNCATEGORIZED'],
            [
                'name' => 'Uncategorized Principals',
                'contact_name' => 'System Administrator',
                'designation' => 'System',
                'email' => 'admin@tallychamps.com',
                'mobile' => '0000000000',
                'territory' => 'System Default',
                'status' => 'Active',
                'd_types' => ['GENERAL'],
                'd_parameter' => ['Remarks'],
                'no_of_urls' => 0,
            ]
        );
    }
}
