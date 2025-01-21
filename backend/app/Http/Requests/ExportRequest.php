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
            'excluded_orders' => 'nullable|array',
            'excluded_orders.*' => 'integer|exists:orders,id',
            'select_all' => 'boolean',
            'data_selected' => 'required|boolean|in:1',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'data_selected' => $this->hasValidationForExport(),
        ]);
    }

    private function hasValidationForExport(): bool
    {
        return $this->boolean('select_all') || !empty($this->input('selected_orders'));
    }

    public function messages(): array
    {
        return [
            'format.required' => 'Export format is required.',
            'format.in' => 'Invalid export format. Allowed: csv, xlsx.',
            'selected_orders.*.integer' => 'Each selected order ID must be an integer.',
            'selected_orders.*.exists' => 'One or more selected orders do not exist.',
            'excluded_orders.*.integer' => 'Each excluded order ID must be an integer.',
            'excluded_orders.*.exists' => 'One or more excluded orders do not exist.',
            'select_all.boolean' => 'The select_all field must be true or false.',
            'data_selected.in' => 'Please select orders.',
        ];
    }
}
