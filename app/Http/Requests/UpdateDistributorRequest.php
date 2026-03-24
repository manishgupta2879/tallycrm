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
            'code' => 'required|string|max:32|unique:distributors,code,' . $id,
            'name' => 'required|string|max:255',
            'company_code' => 'required|string|max:32',
            'type' => 'required|string|max:32',
            'address' => 'required|string',
            'country' => 'required|string|max:20',
            'region' => 'required|string|max:20',
            'state' => 'required|string|max:20',
            'city' => 'required|string|max:20',
            'pincode' => 'required|string|max:10',
            'gst_number' => ['required', 'string'],
            'pan_number' => ['required', 'string'   ],
            'status' => 'required|string|in:Active,Inactive',

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

            // Contacts
            'contact_name' => 'required|array|min:1',
            'contact_name.*' => 'required|string|max:128',
            'designation' => 'required|array|min:1',
            'designation.*' => 'required|string|max:128',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:128',
            'mobile' => 'required|array|min:1',
            'mobile.*' => 'required|string|max:15',
            'location' => 'required|array|min:1',
            'location.*' => 'required|string|max:128',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'contact_name.0.required' => 'The contact name field is required.',
            'designation.0.required' => 'The designation field is required.',
            'email.0.required' => 'The email field is required.',
            'mobile.0.required' => 'The mobile field is required.',
            'location.0.required' => 'The location field is required.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'contact_name.0' => 'contact name',
            'designation.0' => 'designation',
            'email.0' => 'email',
            'mobile.0' => 'mobile',
            'location.0' => 'location',
        ];
    }
}
