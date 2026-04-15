@extends('layouts.app')

@php($title = $budgetLine->exists ? 'Modifier une ligne budgétaire' : 'Créer une ligne budgétaire')
@php($subtitle = 'Socle budgétaire modulaire pour analyses et écarts.')

@section('content')
    <form method="POST" action="{{ $budgetLine->exists ? route('budgets.update', $budgetLine) : route('budgets.store') }}" class="card-premium p-6">
        @csrf
        @if($budgetLine->exists) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <input type="number" name="year" value="{{ old('year', $budgetLine->year) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Année">
            <input type="number" name="month" value="{{ old('month', $budgetLine->month) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Mois">
            <select name="third_party_type" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                @foreach($tiersTypes as $type)
                    <option value="{{ $type }}" @selected(old('third_party_type', $budgetLine->third_party_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
            <select name="budget_category" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                @foreach($budgetCategories as $category)
                    <option value="{{ $category }}" @selected(old('budget_category', $budgetLine->budget_category) === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <input name="supplier_name" value="{{ old('supplier_name', $budgetLine->supplier_name) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Tiers ou fournisseur">
            <input type="number" step="0.01" name="budget_amount" value="{{ old('budget_amount', $budgetLine->budget_amount) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Montant">
            <input name="budget_version" value="{{ old('budget_version', $budgetLine->budget_version ?? 'V1') }}" class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Version budget">
        </div>

        <textarea name="comments" rows="4" class="mt-4 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3" placeholder="Commentaires">{{ old('comments', $budgetLine->comments) }}</textarea>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('budgets.index') }}" class="rounded-2xl border border-white/10 px-5 py-3">Annuler</a>
            <button class="rounded-2xl bg-sky-500 px-5 py-3 font-semibold text-white">Enregistrer</button>
        </div>
    </form>
@endsection
