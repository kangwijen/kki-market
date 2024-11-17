<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreProductRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s\-\_\.]+$/u'],
            'product_type_id' => ['required', 'integer', 'exists:product_types,id'],
            'product_detail.description' => ['required', 'string', 'max:10000'],
            'product_detail.price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'product_detail.stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'img_path' => ['required', 'string', 'max:255', 'regex:/^[\w\-\/\.]+$/'],
        ];
    }
}
