<?php

namespace App\Http\Requests;

use App\Enums\ExpenseType;
use App\Enums\ThirdPartyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ExpenseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'max:100'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'third_party_name' => ['nullable', 'string', 'max:255'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'label' => ['required', 'string', 'max:255'],

            'invoice_date' => ['nullable', 'date'],
            'receipt_date' => ['nullable', 'date'],
            'service_start_date' => ['nullable', 'date'],
            'service_end_date' => ['nullable', 'date', 'after_or_equal:service_start_date'],
            'due_date' => ['nullable', 'date'],
            'planned_payment_date' => ['nullable', 'date'],
            'payment_date' => ['nullable', 'date'],

            'amount_ht' => ['nullable', 'numeric', 'min:0'],
            'vat_amount' => ['nullable', 'numeric', 'min:0'],
            'amount_ttc' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['nullable', 'numeric', 'min:0'],

            'payment_mode' => ['nullable', 'string', 'max:100'],
            'third_party_type' => ['required', new Enum(ThirdPartyType::class)],
            'expense_type' => ['required', new Enum(ExpenseType::class)],

            'budget_category' => ['nullable', 'string', 'max:255'],
            'budget_version' => ['nullable', 'string', 'max:100'],
            'budget_origin' => ['nullable', 'string', 'max:100'],

            'is_forecast' => ['sometimes', 'boolean'],
            'is_allocated' => ['sometimes', 'boolean'],
            'allocation_mode' => ['nullable', 'string', 'max:50'],
            'is_locked' => ['sometimes', 'boolean'],
            'cash_impact' => ['sometimes', 'boolean'],
            'is_regularizable' => ['sometimes', 'boolean'],
            'requires_approval' => ['sometimes', 'boolean'],

            'comments' => ['nullable', 'string'],
            'requested_by' => ['nullable', 'exists:users,id'],
        ];
    }
}