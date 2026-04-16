<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseDashboardService
{
    public function getOperationalSummary(array $filters = []): array
    {
        $query = Expense::query();
        $this->applyBasicFilters($query, $filters);

        return [
            'total_count' => (clone $query)->count(),
            'total_amount_ttc' => round((float) (clone $query)->sum('amount_ttc'), 2),
            'total_amount_paid' => round((float) (clone $query)->sum('amount_paid'), 2),
            'total_balance_due' => round((float) (clone $query)->sum('balance_due'), 2),

            'forecast_count' => (clone $query)->where('is_forecast', true)->count(),
            'allocated_count' => (clone $query)->where('is_allocated', true)->count(),
            'approval_pending_count' => (clone $query)->where('validation_status', 'pending')->count(),
            'overdue_count' => (clone $query)->where('status', 'overdue')->count(),
            'unpaid_count' => (clone $query)->whereIn('status', ['open', 'waiting_payment', 'partially_paid', 'overdue'])->count(),
        ];
    }

    public function getStatusBreakdowns(array $filters = []): array
    {
        return [
            'document_statuses' => $this->groupBy('document_status', $filters),
            'operational_statuses' => $this->groupBy('status', $filters),
            'validation_statuses' => $this->groupBy('validation_status', $filters),
            'payment_modes' => $this->groupBy('payment_mode', $filters),
            'expense_types' => $this->groupBy('expense_type', $filters),
            'budget_categories' => $this->groupBy('budget_category', $filters),
        ];
    }

    public function getMonthlyTrend(array $filters = []): array
    {
        $query = Expense::query();
        $this->applyBasicFilters($query, $filters);

        return $query
            ->selectRaw("DATE_FORMAT(COALESCE(invoice_date, planned_payment_date, created_at), '%Y-%m') as period")
            ->selectRaw('SUM(amount_ttc) as total_amount_ttc')
            ->selectRaw('SUM(amount_paid) as total_amount_paid')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($row) => [
                'period' => $row->period,
                'total_amount_ttc' => round((float) $row->total_amount_ttc, 2),
                'total_amount_paid' => round((float) $row->total_amount_paid, 2),
            ])
            ->toArray();
    }

    public function getTopSuppliers(array $filters = [], int $limit = 10): array
    {
        $query = Expense::query()
            ->leftJoin('suppliers', 'expenses.supplier_id', '=', 'suppliers.id');

        $this->applyBasicFilters($query, $filters, 'expenses');

        return $query
            ->selectRaw('expenses.supplier_id')
            ->selectRaw('COALESCE(expenses.third_party_name, suppliers.name) as supplier_name')
            ->selectRaw('COUNT(*) as documents_count')
            ->selectRaw('SUM(expenses.amount_ttc) as total_amount_ttc')
            ->selectRaw('SUM(expenses.amount_paid) as total_amount_paid')
            ->selectRaw('SUM(expenses.balance_due) as total_balance_due')
            ->groupBy('expenses.supplier_id', 'supplier_name')
            ->orderByDesc('total_amount_ttc')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'supplier_id' => $row->supplier_id,
                'supplier_name' => $row->supplier_name,
                'documents_count' => (int) $row->documents_count,
                'total_amount_ttc' => round((float) $row->total_amount_ttc, 2),
                'total_amount_paid' => round((float) $row->total_amount_paid, 2),
                'total_balance_due' => round((float) $row->total_balance_due, 2),
            ])
            ->toArray();
    }

    protected function groupBy(string $column, array $filters = []): array
    {
        $query = Expense::query();
        $this->applyBasicFilters($query, $filters);

        return $query
            ->select($column)
            ->selectRaw('COUNT(*) as total_count')
            ->selectRaw('SUM(amount_ttc) as total_amount_ttc')
            ->groupBy($column)
            ->orderByDesc('total_count')
            ->get()
            ->map(fn ($row) => [
                'key' => $row->{$column},
                'count' => (int) $row->total_count,
                'amount_ttc' => round((float) $row->total_amount_ttc, 2),
            ])
            ->toArray();
    }

    protected function applyBasicFilters($query, array $filters = [], string $prefix = null): void
    {
        $col = fn (string $name) => $prefix ? "{$prefix}.{$name}" : $name;

        if (!empty($filters['date_from'])) {
            $query->whereDate($col('created_at'), '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate($col('created_at'), '<=', $filters['date_to']);
        }

        if (!empty($filters['expense_type'])) {
            $query->where($col('expense_type'), $filters['expense_type']);
        }

        if (!empty($filters['supplier_id'])) {
            $query->where($col('supplier_id'), $filters['supplier_id']);
        }
    }
}