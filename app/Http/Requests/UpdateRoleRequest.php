<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('roles.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->route('role');
        $usersCount = User::where('role_id', $role->id)->count();

        $rules = [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ];

        // Only allow slug update if no users are assigned
        if ($usersCount === 0) {
            $rules['slug'] = 'required|string|max:255|unique:roles,slug,' . $role->id;
        } else {
            $rules['slug'] = 'required|string|max:255|in:' . $role->slug;
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'slug.required' => 'Role slug is required.',
            'slug.unique' => 'This role slug already exists.',
            'slug.in' => 'You cannot change the slug when users are assigned to this role.',
        ];
    }
}
