@extends('layouts.app')

@php($title = $expense->exists ? 'Modifier une dépense' : 'Créer une dépense')
@php($subtitle = 'Écran métier premium avec statuts, budget, paiement et ventilation.')

@section('content')
    <form method="POST" action="{{ $expense->exists ? route('expenses.update', $expense) : route('expenses.store') }}" class="card-premium p-6">
        @csrf
        @if($expense->exists) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <select name="supplier_id" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                <option value="">Fournisseur</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $expense->supplier_id) === (string) $supplier->id)>{{ $supplier->name }}</option>
                @endforeach
            </select>
            <input name="reference" value="{{ old('reference', $expense->reference) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Référence">
            <input name="invoice_number" value="{{ old('invoice_number', $expense->invoice_number) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="N° facture">
            <input name="label" value="{{ old('label', $expense->label) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Libellé">
            <input type="date" name="invoice_date" value="{{ old('invoice_date', optional($expense->invoice_date)->format('Y-m-d')) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
            <input type="date" name="service_start_date" value="{{ old('service_start_date', optional($expense->service_start_date)->format('Y-m-d')) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
            <input type="date" name="service_end_date" value="{{ old('service_end_date', optional($expense->service_end_date)->format('Y-m-d')) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
            <input type="date" name="planned_payment_date" value="{{ old('planned_payment_date', optional($expense->planned_payment_date)->format('Y-m-d')) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
            <input type="number" step="0.01" name="amount_ht" value="{{ old('amount_ht', $expense->amount_ht) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Montant HT">
            <input type="number" step="0.01" name="vat_amount" value="{{ old('vat_amount', $expense->vat_amount) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="TVA">
            <input type="number" step="0.01" name="amount_ttc" value="{{ old('amount_ttc', $expense->amount_ttc) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Montant TTC">
            <input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid', $expense->amount_paid) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Montant payé">
            <select name="status" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $expense->status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="validation_status" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                @foreach($validationStatuses as $status)
                    <option value="{{ $status }}" @selected(old('validation_status', $expense->validation_status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="third_party_type" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                <option value="">Type tiers</option>
                @foreach($tiersTypes as $type)
                    <option value="{{ $type }}" @selected(old('third_party_type', $expense->third_party_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
            <select name="budget_category" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                <option value="">Catégorie budget</option>
                @foreach($budgetCategories as $category)
                    <option value="{{ $category }}" @selected(old('budget_category', $expense->budget_category) === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <textarea name="comments" rows="4" class="mt-4 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Commentaires">{{ old('comments', $expense->comments) }}</textarea>

        <div class="mt-6 flex flex-wrap justify-end gap-3">
            @if($expense->exists)
                <form method="POST" action="{{ route('expenses.generate-allocation', $expense) }}">
                    @csrf
                    <button class="rounded-2xl border border-amber-400/40 px-5 py-3 text-amber-200">Générer la ventilation</button>
                </form>
            @endif
            <a href="{{ route('expenses.index') }}" class="rounded-2xl border border-white/10 px-5 py-3">Annuler</a>
            <button class="rounded-2xl bg-sky-500 px-5 py-3 font-semibold text-white">Enregistrer</button>
        </div>
    </form>

    @if($expense->exists && $expense->allocations->count())
        <div class="mt-6 card-premium p-6">
            <h2 class="text-xl font-semibold">Ventilation générée</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm table-dark">
                    <thead class="text-slate-400">
                        <tr>
                            <th>Ligne</th>
                            <th>Période</th>
                            <th>Montant</th>
                            <th>Payé</th>
                            <th>Solde</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expense->allocations as $allocation)
                            <tr class="border-t border-white/5">
                                <td>{{ $allocation->line_number }}</td>
                                <td>{{ $allocation->period_label }}</td>
                                <td>{{ number_format($allocation->allocated_amount, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($allocation->paid_amount, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($allocation->balance_due, 2, ',', ' ') }} €</td>
                                <td>{{ $allocation->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
