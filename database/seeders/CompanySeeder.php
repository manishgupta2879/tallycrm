<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                "company_pid" => "MSIL / SMIPL",
                "company_name" => "MARUTI / SUZUKI",
                "distributor_type" => ["DLR", "STK", "MASS"],
                "distributor_parameters" => [
                    "Dlr Type",
                    "Parent Group",
                    "MUL Dealer Code",
                    "FOR Code",
                    "Outlet Code",
                    "No. of Outlets"
                ],
                "url_parameters" => ["URL", "User Name", "Password"]
            ],
            [
                "company_pid" => "LGEIL",
                "company_name" => "LG Electronics India Pvt. Ltd.",
                "distributor_type" => ["LGETP"],
                "distributor_parameters" => [
                    "LGBillingCode",
                    "Password"
                ],
                "url_parameters" => ["URL", "User Name"]
            ],
            [
                "company_pid" => "Havells",
                "company_name" => "Havells India Ltd",
                "distributor_type" => ["DISTI", "STKST"],
                "distributor_parameters" => [
                    "Distributor Code",
                    "Password"
                ],
                "url_parameters" => ["URL", "User Name"]
            ],
            [
                "company_pid" => "SONY001",
                "company_name" => "Sony India Pvt Ltd",
                "distributor_type" => ["DISTI"],
                "distributor_parameters" => [
                    "Distributor Code",
                    "Password"
                ],
                "url_parameters" => ["URL", "User Name"]
            ],
            [
                "company_pid" => "UNCATEGORIZED",
                "company_name" => "Uncategorized Principals",
                "distributor_type" => ["GENERAL"],
                "distributor_parameters" => ["Remarks"],
                "url_parameters" => []
            ]
        ];

        foreach ($companies as $data) {
            // Prepare URLs based on parameters
            $urlData = [];
            if (!empty($data['url_parameters'])) {
                $mainUrl = 'https://' . strtolower(str_replace([' ', '/', '.'], '', $data['company_pid'])) . '.example.com';
                $fields = [];
                foreach ($data['url_parameters'] as $param) {
                    if ($param === 'URL') continue; // URL is the main field
                    $fields[] = [
                        'key' => $param,
                        'value' => 'test_' . strtolower(str_replace(' ', '_', $param))
                    ];
                }
                $urlData[] = [
                    'url' => $mainUrl,
                    'fields' => $fields
                ];
            }

            Company::create([
                'pid' => $data['company_pid'],
                'name' => $data['company_name'],
                'contact_name' => 'John Doe',
                'designation' => 'Manager',
                'email' => strtolower(str_replace(' ', '', $data['company_pid'])) . '@example.com',
                'mobile' => '9876543210',
                'territory' => 'Pan India',
                'status' => 'Active',
                'd_types' => $data['distributor_type'],
                'd_parameter' => $data['distributor_parameters'],
                'c_urls' => !empty($urlData) ? $urlData : null,
                'no_of_urls' => count($urlData),
            ]);
        }
    }
}
