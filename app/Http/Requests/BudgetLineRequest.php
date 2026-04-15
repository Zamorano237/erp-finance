<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetLineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'third_party_type' => ['required', 'string', 'max:50'],
            'budget_category' => ['required', 'string', 'max:100'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'budget_amount' => ['required', 'numeric', 'min:0'],
            'comments' => ['nullable', 'string'],
            'budget_version' => ['nullable', 'string', 'max:50'],
        ];
    }
}
