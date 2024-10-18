<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'product_type_id' => 'sometimes|exists:product_types,id',
            'product_detail.description' => 'sometimes|string',
            'product_detail.price' => 'sometimes|numeric|min:0',
            'product_detail.stock' => 'sometimes|integer|min:0',
            'product_detail.img_path' => 'sometimes|string',
        ];
    }
}
