<?php

namespace App\Services;

use App\DataTransferObjects\ExpenseFiltersData;
use App\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ExpenseListService
{
    public function paginate(ExpenseFiltersData $filters, int $perPage = 25): LengthAwarePaginator
    {
        $query = Expense::query()
            ->with([
                'supplier',
                'validator',
                'submitter',
            ])
            ->withCount([
                'allocations',
                'attachments',
                'commentsThread',
            ]);

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function applyFilters(Builder $query, ExpenseFiltersData $filters): void
    {
        $query->when($filters->search, function (Builder $q, string $search) {
            $q->where(function (Builder $sub) use ($search) {
                $sub->where('reference', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('third_party_name', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function (Builder $supplierQuery) use ($search) {
                        $supplierQuery->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $query->when($filters->expenseType, fn (Builder $q, string $value) => $q->where('expense_type', $value));
        $query->when($filters->thirdPartyType, fn (Builder $q, string $value) => $q->where('third_party_type', $value));
        $query->when($filters->documentStatus, fn (Builder $q, string $value) => $q->where('document_status', $value));
        $query->when($filters->operationalStatus, fn (Builder $q, string $value) => $q->where('status', $value));
        $query->when($filters->validationStatus, fn (Builder $q, string $value) => $q->where('validation_status', $value));
        $query->when($filters->paymentMode, fn (Builder $q, string $value) => $q->where('payment_mode', $value));
        $query->when($filters->budgetCategory, fn (Builder $q, string $value) => $q->where('budget_category', $value));
        $query->when($filters->supplierId, fn (Builder $q, int $value) => $q->where('supplier_id', $value));

        if ($filters->isForecast !== null) {
            $query->where('is_forecast', $filters->isForecast);
        }

        if ($filters->requiresApproval !== null) {
            $query->where('requires_approval', $filters->requiresApproval);
        }

        if ($filters->isAllocated !== null) {
            $query->where('is_allocated', $filters->isAllocated);
        }

        $dateColumn = $this->resolveDateColumn($filters->dateType);

        if ($filters->dateFrom) {
            $query->whereDate($dateColumn, '>=', $filters->dateFrom);
        }

        if ($filters->dateTo) {
            $query->whereDate($dateColumn, '<=', $filters->dateTo);
        }
    }

    public function applySorting(Builder $query, ExpenseFiltersData $filters): void
    {
        $sortBy = $filters->sortBy ?: 'created_at';
        $direction = strtolower($filters->sortDirection ?: 'desc') === 'asc' ? 'asc' : 'desc';

        $allowed = [
            'created_at',
            'reference',
            'label',
            'amount_ttc',
            'amount_paid',
            'balance_due',
            'invoice_date',
            'planned_payment_date',
            'due_date',
            'status',
            'document_status',
            'validation_status',
        ];

        if (!in_array($sortBy, $allowed, true)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $direction);
    }

    protected function resolveDateColumn(?string $dateType): string
    {
        return match ($dateType) {
            'invoice_date' => 'invoice_date',
            'receipt_date' => 'receipt_date',
            'due_date' => 'due_date',
            'payment_date' => 'payment_date',
            default => 'planned_payment_date',
        };
    }
}