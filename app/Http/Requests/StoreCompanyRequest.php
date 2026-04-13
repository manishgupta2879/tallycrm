<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('company.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pid' => 'required|string|max:50|unique:companies,pid',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'd_types' => 'nullable|array',
            'd_parameter' => 'nullable|array',
            'c_urls' => 'nullable|array',
            'no_of_urls' => 'nullable|integer|min:0|max:10',
            'urls_loaded' => 'nullable|string',
        ];
    }
}
