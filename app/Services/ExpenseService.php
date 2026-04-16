<?php

namespace App\Services;

use App\Enums\ExpenseContext;
use App\Enums\ExpenseDocumentStatus;
use App\Enums\ExpenseOperationalStatus;
use App\Enums\ExpenseType;
use App\Enums\ExpenseValidationStatus;
use App\Models\Expense;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExpenseService
{
    public function create(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            $payload = $this->preparePayload($data, true);

            $expense = new Expense($payload);
            $expense->save();

            return $expense->fresh(['supplier', 'allocations', 'payments']);
        });
    }

    public function update(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data) {
            $payload = $this->preparePayload($data, false, $expense);

            $expense->fill($payload);
            $expense->save();

            return $expense->fresh(['supplier', 'allocations', 'payments']);
        });
    }

    public function submitForApproval(Expense $expense, int $userId): Expense
    {
        if (!$expense->requires_approval) {
            throw new InvalidArgumentException('Cette dépense ne nécessite pas de validation.');
        }

        if (!$expense->canBeSubmittedForApproval()) {
            throw new InvalidArgumentException('Cette dépense ne peut pas être soumise en validation.');
        }

        $expense->validation_status = ExpenseValidationStatus::PENDING;
        $expense->status = ExpenseOperationalStatus::IN_VALIDATION;
        $expense->submitted_for_approval_at = now();
        $expense->submitted_for_approval_by = $userId;
        $expense->save();

        return $expense->fresh();
    }

    public function approve(Expense $expense, int $validatorId): Expense
    {
        if ($expense->validation_status !== ExpenseValidationStatus::PENDING) {
            throw new InvalidArgumentException('La dépense n’est pas en attente de validation.');
        }

        $expense->validation_status = ExpenseValidationStatus::APPROVED;
        $expense->validated_by = $validatorId;
        $expense->validated_at = now();
        $expense->status = ExpenseOperationalStatus::WAITING_PAYMENT;
        $expense->save();

        return $expense->fresh();
    }

    public function reject(Expense $expense, int $validatorId, ?string $comment = null): Expense
    {
        if ($expense->validation_status !== ExpenseValidationStatus::PENDING) {
            throw new InvalidArgumentException('La dépense n’est pas en attente de validation.');
        }

        $existingComments = trim((string) $expense->comments);
        $rejectionNote = trim((string) $comment);

        $expense->validation_status = ExpenseValidationStatus::REJECTED;
        $expense->validated_by = $validatorId;
        $expense->validated_at = now();
        $expense->status = ExpenseOperationalStatus::REJECTED;
        $expense->comments = $rejectionNote !== ''
            ? trim($existingComments . "\n[REJET] " . $rejectionNote)
            : $existingComments;

        $expense->save();

        return $expense->fresh();
    }

    protected function preparePayload(array $data, bool $isCreation = true, ?Expense $expense = null): array
    {
        $payload = Arr::only($data, [
            'reference',
            'supplier_id',
            'third_party_name',
            'invoice_number',
            'label',
            'invoice_date',
            'receipt_date',
            'service_start_date',
            'service_end_date',
            'due_date',
            'planned_payment_date',
            'payment_date',
            'amount_ht',
            'vat_amount',
            'amount_ttc',
            'amount_paid',
            'payment_mode',
            'third_party_type',
            'expense_type',
            'expense_context',
            'budget_category',
            'budget_version',
            'budget_origin',
            'is_forecast',
            'is_allocated',
            'allocation_mode',
            'is_locked',
            'cash_impact',
            'is_regularizable',
            'requires_approval',
            'comments',
            'requested_by',
        ]);

        $payload['expense_type'] = $payload['expense_type'] ?? ExpenseType::PURCHASE->value;
        $payload['expense_context'] = $payload['expense_context']
            ?? $this->resolveContext($payload['expense_type']);

        $payload['document_status'] = $payload['document_status']
            ?? $this->resolveDocumentStatus($payload);

        $payload['validation_status'] = $payload['requires_approval'] ?? false
            ? ExpenseValidationStatus::NOT_SUBMITTED->value
            : ExpenseValidationStatus::NOT_SUBMITTED->value;

        if ($isCreation && !isset($payload['status'])) {
            $payload['status'] = ExpenseOperationalStatus::DRAFT->value;
        }

        if (!isset($payload['amount_paid'])) {
            $payload['amount_paid'] = $expense?->amount_paid ?? 0;
        }

        return $payload;
    }

    protected function resolveContext(string $expenseType): string
    {
        return match ($expenseType) {
            ExpenseType::PURCHASE->value => ExpenseContext::PURCHASE_SUPPLIER->value,
            ExpenseType::BANK->value => ExpenseContext::BANK_CHARGE->value,
            ExpenseType::SOCIAL->value => ExpenseContext::SOCIAL_CONTRIBUTION->value,
            ExpenseType::SALARY->value => ExpenseContext::SALARY_PAYMENT->value,
            ExpenseType::EXPENSE_REPORT->value => ExpenseContext::EXPENSE_REIMBURSEMENT->value,
            ExpenseType::ADMINISTRATION->value => ExpenseContext::TAX_OR_ADMIN->value,
            default => ExpenseContext::EXCEPTIONAL->value,
        };
    }

    protected function resolveDocumentStatus(array $payload): string
    {
        if (($payload['is_forecast'] ?? false) === true) {
            return ExpenseDocumentStatus::PREVISIONAL->value;
        }

        if (($payload['expense_type'] ?? null) === ExpenseType::BANK->value) {
            return ExpenseDocumentStatus::NO_INVOICE->value;
        }

        if (empty($payload['invoice_number']) && empty($payload['receipt_date'])) {
            return ExpenseDocumentStatus::MISSING_DOCUMENT->value;
        }

        return ExpenseDocumentStatus::RECEIVED->value;
    }
}