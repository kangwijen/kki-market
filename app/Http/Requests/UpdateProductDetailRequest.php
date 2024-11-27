<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role_id === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'string', 'max:10000'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['sometimes', 'integer', 'min:0', 'max:999999'],
            'url' => ['sometimes', 'string', 'max:255', 'regex:/^[\w\-\/\.]+$/'],
        ];
    }
}
