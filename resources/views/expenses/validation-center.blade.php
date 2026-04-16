@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Centre de validation</h1>
        <p class="text-muted mb-0">Pilotage des dépenses en attente de décision.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">En attente</div>
                <div class="fs-4 fw-semibold">{{ $counts['pending'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Validées</div>
                <div class="fs-4 fw-semibold">{{ $counts['approved'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Rejetées</div>
                <div class="fs-4 fw-semibold">{{ $counts['rejected'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Annulées</div>
                <div class="fs-4 fw-semibold">{{ $counts['cancelled'] }}</div>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Dépense</th>
                        <th>Demandeur</th>
                        <th>Tiers</th>
                        <th class="text-end">Montant</th>
                        <th>Demandée le</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingApprovals as $approval)
                        <tr>
                            <td>{{ $approval->expense->label }}</td>
                            <td>{{ $approval->expense->requester?->name ?? '-' }}</td>
                            <td>{{ $approval->expense->third_party_name ?? $approval->expense->supplier?->name }}</td>
                            <td class="text-end">{{ number_format((float) $approval->expense->amount_ttc, 2, ',', ' ') }}</td>
                            <td>{{ optional($approval->requested_at)?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('expenses.show', $approval->expense) }}" class="btn btn-sm btn-outline-primary">Traiter</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucune validation en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $pendingApprovals->links() }}
        </div>
    </div>
</div>
@endsection