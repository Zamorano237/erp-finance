@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Dashboard Dépenses</h1>
        <p class="text-muted mb-0">Lecture opérationnelle et analytique du module.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Nombre total</div>
            <div class="fs-4 fw-semibold">{{ $summary['total_count'] }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Montant engagé</div>
            <div class="fs-4 fw-semibold">{{ number_format($summary['total_amount_ttc'], 2, ',', ' ') }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Montant payé</div>
            <div class="fs-4 fw-semibold">{{ number_format($summary['total_amount_paid'], 2, ',', ' ') }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Solde global</div>
            <div class="fs-4 fw-semibold">{{ number_format($summary['total_balance_due'], 2, ',', ' ') }}</div>
        </div></div></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Prévisionnelles</div>
            <div class="fs-5">{{ $summary['forecast_count'] }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">Ventilées</div>
            <div class="fs-5">{{ $summary['allocated_count'] }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">En validation</div>
            <div class="fs-5">{{ $summary['approval_pending_count'] }}</div>
        </div></div></div>

        <div class="col-md-3"><div class="card"><div class="card-body">
            <div class="text-muted small">En retard</div>
            <div class="fs-5">{{ $summary['overdue_count'] }}</div>
        </div></div></div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Top tiers / fournisseurs</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Tiers</th>
                        <th class="text-end">Documents</th>
                        <th class="text-end">Montant TTC</th>
                        <th class="text-end">Payé</th>
                        <th class="text-end">Solde</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSuppliers as $item)
                        <tr>
                            <td>{{ $item['supplier_name'] }}</td>
                            <td class="text-end">{{ $item['documents_count'] }}</td>
                            <td class="text-end">{{ number_format($item['total_amount_ttc'], 2, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format($item['total_amount_paid'], 2, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format($item['total_balance_due'], 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection