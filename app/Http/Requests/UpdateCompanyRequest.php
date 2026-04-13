<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('company.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyId = $this->route('company')->id;

        return [
            'pid' => 'required|string|max:50|unique:companies,pid,' . $companyId,
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'territory' => 'required|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'd_types' => 'nullable|array',
            'd_parameter' => 'nullable|array',
            'c_urls' => 'nullable|array',
            'no_of_urls' => 'nullable|integer|min:0|max:10',
            'urls_loaded' => 'nullable|string',
        ];
    }
}
