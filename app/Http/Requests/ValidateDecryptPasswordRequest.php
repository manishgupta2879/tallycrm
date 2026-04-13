<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ValidateDecryptPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
            'type' => ['required', 'string', 'in:company,distributor'],
        ];
    }


    public function validatePassword(): bool
    {
        $now = Carbon::now();
        $submittedPassword = $this->input('password');
        $type = $this->input('type', 'company');

        switch ($type) {
            case 'distributor':
                $expectedPassword = '12345678';
                break;

            case 'company':
                // $expectedPassword = 'DecUrl@' . $now->format('Y') . '!';
                $expectedPassword = '12345678';
                break;

            default:
                $expectedPassword = ''; // fallback
                break;
        }

        return hash_equals($expectedPassword, $submittedPassword);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'type.required' => 'Type is required.',
            'type.in' => 'Invalid type.',
        ];
    }
}
