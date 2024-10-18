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
        return $this->user()->privilege_level === 10;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

     public function rules(): array
     {
         return [
             'description' => 'sometimes|string',
             'price' => 'sometimes|numeric|min:0',
             'stock' => 'sometimes|integer|min:0',
             'img_path' => 'sometimes|string',
         ];
     }
}
