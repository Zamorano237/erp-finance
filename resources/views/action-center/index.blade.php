@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Centre d’actions</h1>
        <p class="text-muted mb-0">Vue priorisée des éléments à traiter.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Validations en attente</div>
                <div class="fs-4 fw-semibold">{{ $counters['pending_approvals'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Dépenses en retard</div>
                <div class="fs-4 fw-semibold">{{ $counters['overdue_expenses'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Paiements à traiter</div>
                <div class="fs-4 fw-semibold">{{ $counters['waiting_payments'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Notifications non lues</div>
                <div class="fs-4 fw-semibold">{{ $counters['unread_notifications'] }}</div>
            </div></div>
        </div>
    </div>

    @if(!empty($actions['pending_approvals']))
        <div class="card mb-4">
            <div class="card-header">Validations en attente</div>
            <div class="list-group list-group-flush">
                @foreach($actions['pending_approvals'] as $approval)
                    <a href="{{ route('expenses.show', $approval->expense) }}" class="list-group-item list-group-item-action">
                        {{ $approval->expense->label }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($actions['overdue_expenses']))
        <div class="card mb-4">
            <div class="card-header">Dépenses en retard</div>
            <div class="list-group list-group-flush">
                @foreach($actions['overdue_expenses'] as $expense)
                    <a href="{{ route('expenses.show', $expense) }}" class="list-group-item list-group-item-action">
                        {{ $expense->label }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($actions['waiting_payments']))
        <div class="card mb-4">
            <div class="card-header">Paiements à traiter</div>
            <div class="list-group list-group-flush">
                @foreach($actions['waiting_payments'] as $expense)
                    <a href="{{ route('expenses.show', $expense) }}" class="list-group-item list-group-item-action">
                        {{ $expense->label }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection