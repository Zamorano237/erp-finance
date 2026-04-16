<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class ExpenseFiltersData
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $expenseType,
        public readonly ?string $thirdPartyType,
        public readonly ?string $documentStatus,
        public readonly ?string $operationalStatus,
        public readonly ?string $validationStatus,
        public readonly ?string $paymentMode,
        public readonly ?string $budgetCategory,
        public readonly ?int $supplierId,
        public readonly ?bool $isForecast,
        public readonly ?bool $requiresApproval,
        public readonly ?bool $isAllocated,
        public readonly ?string $dateType,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly ?string $sortBy,
        public readonly ?string $sortDirection,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->string('search')->toString() ?: null,
            expenseType: $request->string('expense_type')->toString() ?: null,
            thirdPartyType: $request->string('third_party_type')->toString() ?: null,
            documentStatus: $request->string('document_status')->toString() ?: null,
            operationalStatus: $request->string('status')->toString() ?: null,
            validationStatus: $request->string('validation_status')->toString() ?: null,
            paymentMode: $request->string('payment_mode')->toString() ?: null,
            budgetCategory: $request->string('budget_category')->toString() ?: null,
            supplierId: $request->integer('supplier_id') ?: null,
            isForecast: $request->has('is_forecast') ? $request->boolean('is_forecast') : null,
            requiresApproval: $request->has('requires_approval') ? $request->boolean('requires_approval') : null,
            isAllocated: $request->has('is_allocated') ? $request->boolean('is_allocated') : null,
            dateType: $request->string('date_type')->toString() ?: 'planned_payment_date',
            dateFrom: $request->string('date_from')->toString() ?: null,
            dateTo: $request->string('date_to')->toString() ?: null,
            sortBy: $request->string('sort_by')->toString() ?: 'created_at',
            sortDirection: $request->string('sort_direction')->toString() ?: 'desc',
        );
    }

    public function toArray(): array
    {
        return [
            'search' => $this->search,
            'expense_type' => $this->expenseType,
            'third_party_type' => $this->thirdPartyType,
            'document_status' => $this->documentStatus,
            'status' => $this->operationalStatus,
            'validation_status' => $this->validationStatus,
            'payment_mode' => $this->paymentMode,
            'budget_category' => $this->budgetCategory,
            'supplier_id' => $this->supplierId,
            'is_forecast' => $this->isForecast,
            'requires_approval' => $this->requiresApproval,
            'is_allocated' => $this->isAllocated,
            'date_type' => $this->dateType,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ];
    }
}