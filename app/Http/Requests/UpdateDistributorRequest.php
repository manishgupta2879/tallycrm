<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\GstNumber; // Added
use App\Rules\PanNumber; // Added

use Illuminate\Support\Facades\Gate;

class UpdateDistributorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('distributor.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('distributor'); // Assuming 'distributor' as route param or 'id'

        return [
            'code' => 'required|string|max:50|unique:distributors,code,' . $id,
            'name' => 'required|string|max:255',
            'company_code' => 'required|string|max:50',
            'type' => 'required|string|max:32',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:20',
            'pincode' => 'nullable|string|max:10',
            'gst_number' => ['nullable', 'string'],
            'pan_number' => ['nullable', 'string'   ],
            'tan_no' => 'nullable|string|max:50',
            'msme_no' => 'nullable|string|max:50',
            'dist_perm_pass' => 'nullable|string|max:100',
            'last_sync_date' => 'nullable|string',
            'no_of_sync_urls' => 'nullable|string|max:10',
            'sync_urls' => 'nullable|array',
            'sync_urls.*' => 'nullable|url|max:255',
            'status' => 'nullable|string|in:Active,Inactive',

            // Tally details
            'tally_serial' => 'nullable|string|max:32',
            'tally_users' => 'nullable|integer',
            'tally_deployed' => 'nullable|string|in:cloud,local',
            'no_of_computers' => 'nullable|integer',
            'existing_provider' => 'nullable|string|max:128',
            'tally_data_volume' => 'nullable|string|max:64',
            'tally_cloud' => 'nullable|boolean',

            // Rollout details
            'rollout_request_date' => 'nullable|string',
            'tcp_generated_date' => 'nullable|string',
            'rollout_done_date' => 'nullable|string',
            'rollout_done_by' => 'nullable|string|max:64',
            'rollout_remarks' => 'nullable|string',
            'remarks_date' => 'nullable|string',

            // Contacts (Optional)
            'contact_name' => 'nullable|array',
            'contact_name.*' => 'nullable|string|max:128',
            'designation' => 'nullable|array',
            'designation.*' => 'nullable|string|max:128',
            'email' => 'nullable|array',
            'email.*' => 'nullable|email|max:128',
            'mobile' => 'nullable|array',
            'mobile.*' => 'nullable|string|max:15',
            'location' => 'nullable|array',
            'location.*' => 'nullable|string|max:128',
        ];
    }

}
