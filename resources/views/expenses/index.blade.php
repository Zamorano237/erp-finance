@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Dépenses</h1>
            <p class="text-muted mb-0">Pilotage, filtres, validation et suivi des paiements.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('expenses.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            <a href="{{ route('expenses.validation-center') }}" class="btn btn-outline-warning">Centre de validation</a>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">Nouvelle dépense</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('expenses.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="expense_type" class="form-select">
                        <option value="">Tous</option>
                        <option value="purchase" @selected(request('expense_type')==='purchase' )>Fournisseur</option>
                        <option value="bank" @selected(request('expense_type')==='bank' )>Banque</option>
                        <option value="social" @selected(request('expense_type')==='social' )>Organisme social</option>
                        <option value="salary" @selected(request('expense_type')==='salary' )>Salaire</option>
                        <option value="expense_report" @selected(request('expense_type')==='expense_report' )>Note de frais</option>
                        <option value="administration" @selected(request('expense_type')==='administration' )>Administration</option>
                        <option value="other" @selected(request('expense_type')==='other' )>Autre</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Statut opérationnel</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="draft" @selected(request('status')==='draft' )>Brouillon</option>
                        <option value="open" @selected(request('status')==='open' )>Ouverte</option>
                        <option value="in_validation" @selected(request('status')==='in_validation' )>En validation</option>
                        <option value="waiting_payment" @selected(request('status')==='waiting_payment' )>En attente paiement</option>
                        <option value="partially_paid" @selected(request('status')==='partially_paid' )>Partielle</option>
                        <option value="paid" @selected(request('status')==='paid' )>Payée</option>
                        <option value="overdue" @selected(request('status')==='overdue' )>En retard</option>
                        <option value="rejected" @selected(request('status')==='rejected' )>Rejetée</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Validation</label>
                    <select name="validation_status" class="form-select">
                        <option value="">Toutes</option>
                        <option value="not_submitted" @selected(request('validation_status')==='not_submitted' )>Non soumise</option>
                        <option value="pending" @selected(request('validation_status')==='pending' )>En attente</option>
                        <option value="approved" @selected(request('validation_status')==='approved' )>Validée</option>
                        <option value="rejected" @selected(request('validation_status')==='rejected' )>Rejetée</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fournisseur</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((string) request('supplier_id')===(string) $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type de date</label>
                    <select name="date_type" class="form-select">
                        <option value="planned_payment_date" @selected(request('date_type', 'planned_payment_date' )==='planned_payment_date' )>Paiement prévu</option>
                        <option value="invoice_date" @selected(request('date_type')==='invoice_date' )>Date facture</option>
                        <option value="receipt_date" @selected(request('date_type')==='receipt_date' )>Date réception</option>
                        <option value="due_date" @selected(request('date_type')==='due_date' )>Échéance</option>
                        <option value="payment_date" @selected(request('date_type')==='payment_date' )>Date paiement</option>
                    </select>
                </div>

                <div class="col-md-8 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="POST" action="{{ route('saved-views.store') }}" class="row g-2">
                @csrf

                <input type="hidden" name="module" value="expenses">
                <input type="hidden" name="filters_json" value='@json(request()->query())'>
                <input type="hidden" name="sort_json" value='@json([
        "sort_by" => request("sort_by"),
        "sort_direction" => request("sort_direction")
    ])'>
                <input type="hidden" name="columns_json" value='@json(array_keys($availableColumns))'>
                <input type="hidden" name="options_json" value='@json([])'>

                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Nom de la vue" required>
                </div>

                <div class="col-md-5">
                    <input type="text" name="description" class="form-control" placeholder="Description facultative">
                </div>

                <div class="col-md-2 form-check d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="is_default" value="1" id="is_default">
                    <label class="form-check-label" for="is_default">Vue par défaut</label>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100">Sauver</button>
                </div>
            </form>

            @if($savedViews->count())
            <hr>
            <div class="d-flex flex-wrap gap-2">
                @foreach($savedViews as $view)
                <span class="badge text-bg-light border">
                    {{ $view->name }}@if($view->is_default) (défaut) @endif
                </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Libellé</th>
                        <th>Tiers</th>
                        <th>Type</th>
                        <th>Documentaire</th>
                        <th>Opérationnel</th>
                        <th>Validation</th>
                        <th class="text-end">TTC</th>
                        <th class="text-end">Payé</th>
                        <th class="text-end">Solde</th>
                        <th>Date prévue</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->reference }}</td>
                        <td>{{ $expense->label }}</td>
                        <td>{{ $expense->third_party_name ?? $expense->supplier?->name }}</td>
                        <td>{{ $expense->expense_type?->label() ?? '-' }}</td>
                        <td><span class="badge text-bg-secondary">{{ $expense->document_status?->label() ?? '-' }}</span></td>
                        <td><span class="badge text-bg-primary">{{ $expense->status?->label() ?? '-' }}</span></td>
                        <td><span class="badge text-bg-warning">{{ $expense->validation_status?->label() ?? '-' }}</span></td>
                        <td class="text-end">{{ number_format((float) $expense->amount_ttc, 2, ',', ' ') }}</td>
                        <td class="text-end">{{ number_format((float) $expense->amount_paid, 2, ',', ' ') }}</td>
                        <td class="text-end">{{ number_format((float) $expense->balance_due, 2, ',', ' ') }}</td>
                        <td>{{ optional($expense->planned_payment_date)?->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-secondary">Ouvrir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Aucune dépense trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection