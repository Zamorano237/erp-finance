<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'reference' => ['nullable', 'string', 'max:50'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'label' => ['required', 'string', 'max:255'],
            'invoice_date' => ['nullable', 'date'],
            'receipt_date' => ['nullable', 'date'],
            'service_start_date' => ['nullable', 'date'],
            'service_end_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'planned_payment_date' => ['nullable', 'date'],
            'payment_mode' => ['nullable', 'string', 'max:100'],
            'third_party_type' => ['nullable', 'string', 'max:50'],
            'budget_category' => ['nullable', 'string', 'max:100'],
            'budget_version' => ['nullable', 'string', 'max:50'],
            'amount_ht' => ['required', 'numeric', 'min:0'],
            'vat_amount' => ['required', 'numeric', 'min:0'],
            'amount_ttc' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'validation_status' => ['required', 'string', 'max:50'],
            'comments' => ['nullable', 'string'],
            'is_forecast' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
        ];
    }
}
