<?php

namespace App\Services;

use App\Models\Distributor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistributorService
{
    /**
     * Create a new distributor.
     *
     * @param array $data
     * @return Distributor
     * @throws \Exception
     */
    public function createDistributor(array $data): Distributor
    {
        return DB::transaction(function () use ($data) {
            $processedData = $this->prepareDistributorData($data);
            $distributor = Distributor::create($processedData);

            $this->saveContacts($distributor, $data);

            return $distributor;
        });
    }

    /**
     * Update an existing distributor.
     *
     * @param Distributor $distributor
     * @param array $data
     * @return Distributor
     * @throws \Exception
     */
    public function updateDistributor(Distributor $distributor, array $data): Distributor
    {
        return DB::transaction(function () use ($distributor, $data) {
            $processedData = $this->prepareDistributorData($data);
            $distributor->update($processedData);

            // Simple update for contacts: Delete old and create new
            $distributor->contacts()->delete();
            $this->saveContacts($distributor, $data);

            return $distributor;
        });
    }

    /**
     * Delete a distributor and its contacts.
     *
     * @param Distributor $distributor
     * @return bool|null
     * @throws \Exception
     */
    public function deleteDistributor(Distributor $distributor)
    {
        return DB::transaction(function () use ($distributor) {
            $distributor->contacts()->delete();
            return $distributor->delete();
        });
    }

    /**
     * Prepare data for distributor creation or update.
     *
     * @param array $data
     * @return array
     */
    protected function prepareDistributorData(array $data): array
    {
        // Handle additional parameters (d_parameter_1 to d_parameter_10)
        $params = [];
        for ($i = 1; $i <= 10; $i++) {
            $key = "d_parameter_$i";
            if (isset($data[$key])) {
                $params[$i] = $data[$key];
            }
        }
        $data['params'] = $params;

        return $data;
    }

    /**
     * Save contacts for a distributor.
     *
     * @param Distributor $distributor
     * @param array $data
     * @return void
     */
    protected function saveContacts(Distributor $distributor, array $data): void
    {
        if (!isset($data['contact_name'])) {
            return;
        }

        $contactNames = $data['contact_name'];
        $designations = $data['designation'];
        $emails = $data['email'];
        $mobiles = $data['mobile'];
        $locations = $data['location'];

        for ($i = 0; $i < count($contactNames); $i++) {
            $distributor->contacts()->create([
                'name' => $contactNames[$i],
                'desig' => $designations[$i],
                'email' => $emails[$i],
                'mobile' => $mobiles[$i],
                'loc' => $locations[$i],
            ]);
        }
    }
}
