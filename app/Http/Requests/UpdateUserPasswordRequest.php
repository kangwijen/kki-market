<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // return Auth::check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['sometimes', 'string', 'max:255'],
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'sometimes', 'string', 'min:8'],
            'newPassword_confirmation' => ['required', 'sometimes', 'string', 'min:8']
        ];
    }
}
