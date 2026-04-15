<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id;

        return [
            'code' => ['nullable', 'string', 'max:50', Rule::unique('suppliers', 'code')->ignore($supplierId)],
            'name' => ['required', 'string', 'max:255', Rule::unique('suppliers', 'name')->ignore($supplierId)],
            'category' => ['nullable', 'string', 'max:100'],
            'auxiliary_account' => ['nullable', 'string', 'max:50'],
            'frequency' => ['nullable', 'string', 'max:50'],
            'receipt_mode' => ['nullable', 'string', 'max:100'],
            'payment_mode' => ['nullable', 'string', 'max:100'],
            'forecast_amount' => ['nullable', 'numeric', 'min:0'],
            'default_label' => ['nullable', 'string', 'max:255'],
            'payment_delay_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'vat_rate_default' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'third_party_type' => ['nullable', 'string', 'max:50'],
            'budget_category' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false,
            'forecast_amount' => $this->input('forecast_amount') === '' ? null : $this->input('forecast_amount'),
            'vat_rate_default' => $this->input('vat_rate_default') === '' ? null : $this->input('vat_rate_default'),
            'payment_delay_days' => $this->input('payment_delay_days') === '' ? null : $this->input('payment_delay_days'),
        ]);
    }
}
