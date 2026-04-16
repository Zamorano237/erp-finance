<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Collection;

class ExpenseDetailViewService
{
    public function build(Expense $expense): array
    {
        $expense->loadMissing([
            'supplier',
            'requester',
            'validator',
            'submitter',
            'allocations.payments',
            'payments.payer',
            'approvals.approver',
            'attachments.uploader',
            'commentsThread.user',
            'statusLogs.user',
            'generationTemplate',
            'generationBatch',
        ]);

        return [
            'expense' => $expense,
            'summary' => $this->buildSummary($expense),
            'actions' => $this->buildActions($expense),
            'timeline' => $this->buildTimeline($expense),
            'tabs' => $this->buildTabs($expense),
        ];
    }

    protected function buildSummary(Expense $expense): array
    {
        return [
            'reference' => $expense->reference,
            'label' => $expense->label,
            'third_party' => $expense->third_party_name ?: $expense->supplier?->name,
            'expense_type' => $expense->expense_type?->label(),
            'document_status' => $expense->document_status?->label(),
            'operational_status' => $expense->status?->label(),
            'validation_status' => $expense->validation_status?->label(),
            'amount_ttc' => (float) $expense->amount_ttc,
            'amount_paid' => (float) $expense->amount_paid,
            'balance_due' => (float) $expense->balance_due,
            'invoice_date' => $expense->invoice_date,
            'planned_payment_date' => $expense->planned_payment_date,
            'payment_date' => $expense->payment_date,
            'due_date' => $expense->due_date,
            'payment_mode' => $expense->payment_mode,
            'budget_category' => $expense->budget_category,
            'is_forecast' => (bool) $expense->is_forecast,
            'is_allocated' => (bool) $expense->is_allocated,
            'allocation_mode' => $expense->allocation_mode,
            'requires_approval' => (bool) $expense->requires_approval,
        ];
    }

    protected function buildActions(Expense $expense): array
    {
        return [
            'can_edit' => auth()->user()?->can('update', $expense) ?? false,
            'can_submit' => auth()->user()?->can('submitForApproval', $expense) ?? false,
            'can_approve' => auth()->user()?->can('approve', $expense) ?? false,
            'can_reject' => auth()->user()?->can('reject', $expense) ?? false,
            'can_pay' => auth()->user()?->can('pay', $expense) ?? false,
            'can_manage_allocation' => auth()->user()?->can('manageAllocation', $expense) ?? false,
            'can_comment' => auth()->user()?->can('comment', $expense) ?? false,
            'can_upload_attachment' => auth()->user()?->can('uploadAttachment', $expense) ?? false,
            'can_realize' => (bool) $expense->is_forecast,
        ];
    }

    protected function buildTabs(Expense $expense): array
    {
        return [
            'overview' => true,
            'validation' => $expense->requires_approval || $expense->approvals->count() > 0,
            'payments' => true,
            'allocations' => $expense->is_allocated || $expense->allocations->count() > 0,
            'attachments' => true,
            'comments' => true,
            'history' => true,
        ];
    }

    protected function buildTimeline(Expense $expense): Collection
    {
        $events = collect();

        $events->push([
            'date' => $expense->created_at,
            'title' => 'Création',
            'description' => 'Dépense créée.',
            'type' => 'created',
            'user' => $expense->requester?->name,
        ]);

        if ($expense->submitted_for_approval_at) {
            $events->push([
                'date' => $expense->submitted_for_approval_at,
                'title' => 'Soumise en validation',
                'description' => 'La dépense a été envoyée dans le circuit de validation.',
                'type' => 'submitted',
                'user' => $expense->submitter?->name,
            ]);
        }

        foreach ($expense->approvals as $approval) {
            if ($approval->decided_at) {
                $events->push([
                    'date' => $approval->decided_at,
                    'title' => $approval->status?->value === 'approved' ? 'Validation' : 'Rejet',
                    'description' => $approval->comment ?: 'Décision prise sur la dépense.',
                    'type' => $approval->status?->value,
                    'user' => $approval->approver?->name,
                ]);
            }
        }

        foreach ($expense->payments as $payment) {
            $events->push([
                'date' => $payment->payment_date,
                'title' => 'Paiement enregistré',
                'description' => 'Paiement de ' . number_format((float) $payment->amount, 2, ',', ' '),
                'type' => 'payment',
                'user' => $payment->payer?->name,
            ]);
        }

        foreach ($expense->commentsThread as $comment) {
            $events->push([
                'date' => $comment->created_at,
                'title' => 'Commentaire',
                'description' => $comment->content,
                'type' => 'comment',
                'user' => $comment->user?->name,
            ]);
        }

        foreach ($expense->statusLogs as $log) {
            $events->push([
                'date' => $log->created_at,
                'title' => 'Changement de statut',
                'description' => ($log->status_axis ?? 'statut') . ' : ' . ($log->old_status ?: '-') . ' → ' . ($log->new_status ?: '-'),
                'type' => 'status_log',
                'user' => $log->user?->name,
            ]);
        }

        return $events
            ->filter(fn ($event) => !empty($event['date']))
            ->sortByDesc('date')
            ->values();
    }
}