<?php

namespace App\Services;

use App\Enums\ExpenseApprovalStatus;
use App\Enums\ExpenseOperationalStatus;
use App\Enums\ExpenseValidationStatus;
use App\Models\Expense;
use App\Models\ExpenseApproval;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExpenseApprovalService
{
    public function __construct(
        private readonly ExpenseStatusLogService $statusLogService,
        private readonly ExpenseNotificationService $notificationService
    ) {}

    public function assignApprover(Expense $expense, int $approverId, int $createdBy, int $order = 1): ExpenseApproval
    {
        return ExpenseApproval::create([
            'expense_id' => $expense->id,
            'approver_id' => $approverId,
            'status' => ExpenseApprovalStatus::PENDING,
            'approval_order' => $order,
            'created_by' => $createdBy,
        ]);
    }

    public function submit(Expense $expense, int $submittedBy): Expense
    {
        if (!$expense->requires_approval) {
            throw new InvalidArgumentException('Cette dépense n’exige pas de validation.');
        }

        if ($expense->approvals()->count() === 0) {
            throw new InvalidArgumentException('Aucun validateur n’est défini pour cette dépense.');
        }

        return DB::transaction(function () use ($expense, $submittedBy) {
            $oldValidationStatus = $expense->validation_status?->value;
            $oldOperationalStatus = $expense->status?->value;

            $expense->approvals()
                ->whereNull('requested_at')
                ->update(['requested_at' => now()]);

            $expense->validation_status = ExpenseValidationStatus::PENDING;
            $expense->status = ExpenseOperationalStatus::IN_VALIDATION;
            $expense->submitted_for_approval_at = now();
            $expense->submitted_for_approval_by = $submittedBy;
            $expense->save();


            $this->statusLogService->log(
                expense: $expense,
                axis: 'validation',
                oldStatus: $oldValidationStatus,
                newStatus: $expense->validation_status->value,
                userId: $submittedBy,
                action: 'submit_for_approval'
            );

            $this->statusLogService->log(
                expense: $expense,
                axis: 'operational',
                oldStatus: $oldOperationalStatus,
                newStatus: $expense->status->value,
                userId: $submittedBy,
                action: 'submit_for_approval'
            );
            $this->notificationService->notifySubmitted($expense);

            return $expense->fresh(['approvals']);
        });
    }

    public function approve(Expense $expense, int $approverId, ?string $comment = null): Expense
    {
        $approval = $expense->approvals()
            ->where('approver_id', $approverId)
            ->where('status', ExpenseApprovalStatus::PENDING->value)
            ->first();

        if (!$approval) {
            throw new InvalidArgumentException('Aucune validation en attente pour cet utilisateur.');
        }

        return DB::transaction(function () use ($expense, $approval, $approverId, $comment) {
            $approval->status = ExpenseApprovalStatus::APPROVED;
            $approval->decided_at = now();
            $approval->comment = $comment;
            $approval->save();

            $hasRejected = $expense->approvals()
                ->where('status', ExpenseApprovalStatus::REJECTED->value)
                ->exists();

            $pendingCount = $expense->approvals()
                ->where('status', ExpenseApprovalStatus::PENDING->value)
                ->count();

            if (!$hasRejected && $pendingCount === 0) {
                $oldValidationStatus = $expense->validation_status?->value;
                $oldOperationalStatus = $expense->status?->value;

                $expense->validation_status = ExpenseValidationStatus::APPROVED;
                $expense->validated_by = $approverId;
                $expense->validated_at = now();
                $expense->status = ExpenseOperationalStatus::WAITING_PAYMENT;
                $expense->save();

                $this->statusLogService->log(
                    expense: $expense,
                    axis: 'validation',
                    oldStatus: $oldValidationStatus,
                    newStatus: $expense->validation_status->value,
                    userId: $approverId,
                    action: 'approve',
                    comment: $comment
                );

                $this->statusLogService->log(
                    expense: $expense,
                    axis: 'operational',
                    oldStatus: $oldOperationalStatus,
                    newStatus: $expense->status->value,
                    userId: $approverId,
                    action: 'approve',
                    comment: $comment
                );
                $this->notificationService->notifyApproved($expense);
                $this->notificationService->notifyFinanceForApprovedExpense($expense);
            }

            return $expense->fresh(['approvals']);
        });
    }

    public function reject(Expense $expense, int $approverId, ?string $comment = null): Expense
    {
        $approval = $expense->approvals()
            ->where('approver_id', $approverId)
            ->where('status', ExpenseApprovalStatus::PENDING->value)
            ->first();

        if (!$approval) {
            throw new InvalidArgumentException('Aucune validation en attente pour cet utilisateur.');
        }

        return DB::transaction(function () use ($expense, $approval, $approverId, $comment) {
            $approval->status = ExpenseApprovalStatus::REJECTED;
            $approval->decided_at = now();
            $approval->comment = $comment;
            $approval->save();

            $expense->approvals()
                ->where('id', '!=', $approval->id)
                ->where('status', ExpenseApprovalStatus::PENDING->value)
                ->update([
                    'status' => ExpenseApprovalStatus::CANCELLED->value,
                    'decided_at' => now(),
                ]);

            $oldValidationStatus = $expense->validation_status?->value;
            $oldOperationalStatus = $expense->status?->value;

            $expense->validation_status = ExpenseValidationStatus::REJECTED;
            $expense->validated_by = $approverId;
            $expense->validated_at = now();
            $expense->status = ExpenseOperationalStatus::REJECTED;
            $expense->save();

            $this->statusLogService->log(
                expense: $expense,
                axis: 'validation',
                oldStatus: $oldValidationStatus,
                newStatus: $expense->validation_status->value,
                userId: $approverId,
                action: 'reject',
                comment: $comment
            );

            $this->statusLogService->log(
                expense: $expense,
                axis: 'operational',
                oldStatus: $oldOperationalStatus,
                newStatus: $expense->status->value,
                userId: $approverId,
                action: 'reject',
                comment: $comment
            );
            $this->notificationService->notifyRejected($expense);

            return $expense->fresh(['approvals']);
        });
    }
}
