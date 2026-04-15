@extends('layouts.app')

@php($title = 'Dépenses')
@php($subtitle = 'Pilotage premium des dépenses et statuts opérationnels')

@section('content')
<div class="page-head">
    <h1 class="page-title">Gestion des dépenses</h1>
    <p class="page-subtitle">Lecture premium des factures, montants, soldes et statuts.</p>
</div>

<div class="panel">
    <div class="panel-toolbar">
        <div class="toolbar-row">
            <div class="toolbar-right">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Nouvelle dépense
                </a>
            </div>
            <div class="muted">Vue premium des dépenses</div>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-shell">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Libellé</th>
                        <th>Fournisseur</th>
                        <th>Mode règlement</th>
                        <th>Montant TTC</th>
                        <th>Montant payé</th>
                        <th>Solde</th>
                        <th>Statut</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->reference }}</td>
                            <td>{{ $expense->label }}</td>
                            <td>{{ $expense->supplier->name ?? '-' }}</td>
                            <td>{{ $expense->payment_mode }}</td>
                            <td>{{ number_format((float) $expense->amount_ttc, 2, ',', ' ') }}</td>
                            <td>{{ number_format((float) $expense->amount_paid, 2, ',', ' ') }}</td>
                            <td>{{ number_format((float) $expense->balance_due, 2, ',', ' ') }}</td>
                            <td><span class="badge">{{ $expense->status }}</span></td>
                            <td class="actions-col">
                                <div class="table-actions">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-light btn-sm">
                                        <i data-lucide="pencil"></i>
                                        Modifier
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">Aucune dépense trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($expenses, 'links'))
        <div class="table-footer">
            {{ $expenses->links() }}
        </div>
    @endif
</div>
@endsection