<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => 'required|string|in:csv,xlsx',
            'selected_orders' => 'nullable|array',
            'selected_orders.*' => 'integer|exists:orders,id',
        ];
    }

    public function messages(): array
    {
        return [
            'format.required' => 'Export format is required.',
            'format.in' => 'Invalid export format. Allowed: csv, xlsx.',
            'selected_orders.*.integer' => 'Selected order ID must be an integer.',
            'selected_orders.*.exists' => 'Selected order does not exist.',
        ];
    }
}
