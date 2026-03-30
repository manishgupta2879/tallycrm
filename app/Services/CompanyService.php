<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    /**
     * Create a new company.
     *
     * @param array $data
     * @return Company
     * @throws \Exception
     */
    public function createCompany(array $data): Company
    {
        return DB::transaction(function () use ($data) {
            $processedData = $this->prepareCompanyData($data);
            $company = Company::create($processedData);

            return $company;
        });
    }

    /**
     * Update an existing company.
     *
     * @param Company $company
     * @param array $data
     * @param bool $urlsLoaded
     * @return Company
     * @throws \Exception
     */
    public function updateCompany(Company $company, array $data, bool $urlsLoaded = false): Company
    {
        return DB::transaction(function () use ($company, $data, $urlsLoaded) {
            $processedData = $this->prepareCompanyData($data, $urlsLoaded);
            $company->update($processedData);

            return $company;
        });
    }

    /**
     * Delete a company.
     *
     * @param Company $company
     * @return bool|null
     * @throws \Exception
     */
    public function deleteCompany(Company $company)
    {
        return DB::transaction(function () use ($company) {
            return $company->delete();
        });
    }

    /**
     * Prepare data for company creation or update.
     *
     * @param array $data
     * @param bool $urlsLoaded Only used for updates
     * @return array
     */
    protected function prepareCompanyData(array $data, bool $urlsLoaded = true): array
    {
        // 1. Process Distributor Types (d_types)
        if (isset($data['d_types'])) {
            $data['d_types'] = array_values(array_filter($data['d_types']));
        }

        // 2. Process Distributor Parameters (d_parameter)
        if (isset($data['d_parameter'])) {
            $data['d_parameter'] = array_values(array_filter($data['d_parameter']));
        }

        // 3. Process URL Details (c_urls)
        // For updates, we only touch c_urls if urls_loaded was true
        if ($urlsLoaded) {
            if (isset($data['c_urls'])) {
                $filteredUrls = array_filter($data['c_urls'], function ($urlData) {
                    return !empty($urlData['url']);
                });
                
                if (!empty($filteredUrls)) {
                    $data['c_urls'] = $filteredUrls;
                } else {
                    $data['c_urls'] = null;
                }
            } else {
                $data['c_urls'] = null;
            }
        } else {
            // If it's an update and URLs weren't loaded, don't overwrite the existing ones
            unset($data['c_urls']);
        }

        // Clean up internal flags
        unset($data['urls_loaded']);

        return $data;
    }
}
