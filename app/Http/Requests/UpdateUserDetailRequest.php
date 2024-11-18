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
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if (!$this->route('id')) {
            return $user->id === $this->user()->id;
        }

        return $user->role_id === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->user()->role_id === 1){
            return [
                'username' => ['sometimes', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/', 'not_regex:/[<>{}[\]\/]/'],
                'email' => ['sometimes', 'string', 'email:rfc,dns', 'max:255'],
                'newPassword' => ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'],
                'newPasswordConfirm' => ['nullable', 'required_with:newPassword', 'string', 'same:newPassword'],
            ];
        }

        return [
            'username' => ['sometimes', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/', 'not_regex:/[<>{}[\]\/]/'],
            'email' => ['sometimes', 'string', 'email:rfc,dns', 'max:255'],
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['sometimes', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'],
            'newPasswordConfirm' => ['sometimes', 'required_with:newPassword', 'string', 'same:newPassword'],
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Username can only contain letters, numbers, underscores, and hyphens.',
            'username.not_regex' => 'Username contains invalid characters.',
            'newPassword.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];
    }
}
