<?php

namespace App\Services;

use App\Enums\ExpenseDocumentStatus;
use App\Enums\ExpenseOperationalStatus;
use App\Enums\ExpenseValidationStatus;
use App\Models\Expense;
use App\Models\ExpenseGenerationBatch;
use App\Models\ExpenseGenerationTemplate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ExpenseGenerationService
{
    public function generate(
        ExpenseGenerationTemplate $template,
        string $fromDate,
        string $toDate,
        ?int $userId = null
    ): ExpenseGenerationBatch {
        return DB::transaction(function () use ($template, $fromDate, $toDate, $userId) {
            $batch = ExpenseGenerationBatch::create([
                'template_id' => $template->id,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'status' => 'running',
                'created_by' => $userId,
                'meta' => [],
            ]);

            $generated = 0;
            $skipped = 0;

            foreach ($this->resolveOccurrences($template, $fromDate, $toDate) as $occurrenceDate) {
                $alreadyExists = Expense::query()
                    ->where('generation_template_id', $template->id)
                    ->whereDate('planned_payment_date', $occurrenceDate->toDateString())
                    ->exists();

                if ($alreadyExists) {
                    $skipped++;
                    continue;
                }

                Expense::create([
                    'reference' => $this->buildReference($template, $occurrenceDate),
                    'supplier_id' => $template->supplier_id,
                    'third_party_name' => $template->third_party_name,
                    'label' => $template->label,
                    'invoice_number' => $this->buildInvoiceNumber($template, $occurrenceDate),
                    'planned_payment_date' => $occurrenceDate->toDateString(),
                    'invoice_date' => $occurrenceDate->toDateString(),
                    'due_date' => $occurrenceDate->toDateString(),

                    'amount_ht' => $template->amount_ht,
                    'vat_amount' => $template->vat_amount,
                    'amount_ttc' => $template->amount_ttc,
                    'amount_paid' => 0,
                    'balance_due' => $template->amount_ttc,

                    'third_party_type' => $template->third_party_type,
                    'expense_type' => $template->expense_type,
                    'expense_context' => $template->expense_context,
                    'payment_mode' => $template->payment_mode,
                    'budget_category' => $template->budget_category,
                    'budget_version' => $template->budget_version,

                    'document_status' => ExpenseDocumentStatus::PREVISIONAL,
                    'status' => ExpenseOperationalStatus::OPEN,
                    'validation_status' => ExpenseValidationStatus::NOT_SUBMITTED,

                    'is_forecast' => true,
                    'is_allocated' => $template->auto_allocate,
                    'allocation_mode' => $template->allocation_mode,
                    'requires_approval' => $template->auto_requires_approval,

                    'generation_template_id' => $template->id,
                    'generation_batch_id' => $batch->id,
                    'requested_by' => $userId,
                ]);

                $generated++;
            }

            $batch->generated_count = $generated;
            $batch->skipped_count = $skipped;
            $batch->status = 'completed';
            $batch->meta = [
                'template_name' => $template->name,
                'frequency' => $template->frequency,
            ];
            $batch->save();

            return $batch->fresh();
        });
    }

    public function realize(Expense $expense, ?array $overrides = []): Expense
    {
        if (!$expense->is_forecast) {
            return $expense;
        }

        $expense->fill($overrides);
        $expense->is_forecast = false;
        $expense->document_status = ExpenseDocumentStatus::RECEIVED;
        $expense->realized_at = now();
        $expense->save();

        return $expense->fresh();
    }

    protected function resolveOccurrences(
        ExpenseGenerationTemplate $template,
        string $fromDate,
        string $toDate
    ): array {
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        $period = CarbonPeriod::create($from, '1 month', $to);

        if ($template->frequency === 'quarterly') {
            $period = CarbonPeriod::create($from, '3 months', $to);
        } elseif ($template->frequency === 'yearly') {
            $period = CarbonPeriod::create($from, '1 year', $to);
        }

        $dates = [];

        foreach ($period as $date) {
            $occurrence = $date->copy();
            if ($template->generation_day !== null) {
                $occurrence->day(min($template->generation_day, $occurrence->daysInMonth));
            }

            if ($occurrence->lt($template->generation_start_date)) {
                continue;
            }

            if ($template->generation_end_date && $occurrence->gt($template->generation_end_date)) {
                continue;
            }

            $dates[] = $occurrence;
        }

        return $dates;
    }

    protected function buildReference(ExpenseGenerationTemplate $template, Carbon $date): string
    {
        $prefix = $template->reference_prefix ?: 'EXP';
        return sprintf('%s-%s-%s', $prefix, $template->id, $date->format('Ym'));
    }

    protected function buildInvoiceNumber(ExpenseGenerationTemplate $template, Carbon $date): ?string
    {
        if (!$template->invoice_number_pattern) {
            return null;
        }

        return str_replace('{YYYYMM}', $date->format('Ym'), $template->invoice_number_pattern);
    }
}