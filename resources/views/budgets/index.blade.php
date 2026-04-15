@extends('layouts.app')

@php($title = 'Budgets')
@php($subtitle = 'Pilotage premium des lignes budgétaires')

@section('content')
<div class="page-head">
    <h1 class="page-title">Gestion des budgets</h1>
    <p class="page-subtitle">Lecture claire des lignes budgétaires, catégories, versions et statuts d’activation.</p>
</div>

<div class="panel">
    <div class="panel-toolbar">
        <div class="toolbar-row">
            <div class="toolbar-right">
                <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Nouvelle ligne budget
                </a>
            </div>
            <div class="muted">Vue premium budgétaire</div>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-shell">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Année</th>
                        <th>Mois</th>
                        <th>Type tiers</th>
                        <th>Catégorie budget</th>
                        <th>Raison sociale</th>
                        <th>Montant budget</th>
                        <th>Version</th>
                        <th>Actif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets as $budget)
                        <tr>
                            <td>{{ $budget->year }}</td>
                            <td>{{ $budget->month }}</td>
                            <td>{{ $budget->third_party_type }}</td>
                            <td>{{ $budget->budget_category }}</td>
                            <td>{{ $budget->supplier_name }}</td>
                            <td>{{ number_format((float) $budget->budget_amount, 2, ',', ' ') }}</td>
                            <td>{{ $budget->budget_version }}</td>
                            <td>
                                <span class="badge {{ $budget->is_active ? 'success' : 'danger' }}">
                                    {{ $budget->is_active ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">Aucune ligne budgétaire trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($budgets, 'links'))
        <div class="table-footer">
            {{ $budgets->links() }}
        </div>
    @endif
</div>
@endsection