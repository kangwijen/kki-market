<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $result = $this->user()->id === $this->request->get('id') || $this->user()->role_id === 1;
        return $result;
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
            'email' => ['sometimes', 'string', 'email', 'max:255'],
            'newPassword' => ['nullable', 'string', 'min:8'],
            'newPasswordConfirm' => ['nullable', 'required_with:newPassword', 'string', 'same:newPassword'],
            'currentPassword' => ['required', 'string'],
        ];
    }
}
