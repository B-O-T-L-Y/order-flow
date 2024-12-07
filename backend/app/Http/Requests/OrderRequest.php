<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.amount' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'At least one product must be specified.',
            'products.*.product_id.required' => 'The product ID is required.',
            'products.*.amount.min' => 'The product quantity must be at least 1.',
        ];
    }
}
