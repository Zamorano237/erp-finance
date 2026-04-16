@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">{{ $summary['reference'] }}</div>
            <h1 class="h3 mb-1">{{ $summary['label'] }}</h1>
            <div class="text-muted">
                {{ $summary['third_party'] ?? 'Tiers non renseigné' }}
                @if($summary['expense_type'])
                • {{ $summary['expense_type'] }}
                @endif
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-end">
            @can('update', $expense)
            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-primary">
                Modifier
            </a>
            @endcan

            @can('submitForApproval', $expense)
            <form method="POST" action="{{ route('expenses.submit-for-approval', $expense) }}">
                @csrf
                <button type="submit" class="btn btn-warning">
                    Soumettre
                </button>
            </form>
            @endcan

            @can('approve', $expense)
            <form method="POST" action="{{ route('expenses.approve', $expense) }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    Valider
                </button>
            </form>
            @endcan

            @can('reject', $expense)
            <form method="POST" action="{{ route('expenses.reject', $expense) }}">
                @csrf
                <button type="submit" class="btn btn-danger">
                    Rejeter
                </button>
            </form>
            @endcan

            @can('pay', $expense)
            <button type="button" class="btn btn-outline-dark">
                Enregistrer un paiement
            </button>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Montant TTC</div>
                    <div class="fs-5 fw-semibold">{{ number_format($summary['amount_ttc'], 2, ',', ' ') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Montant payé</div>
                    <div class="fs-5 fw-semibold">{{ number_format($summary['amount_paid'], 2, ',', ' ') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Solde</div>
                    <div class="fs-5 fw-semibold">{{ number_format($summary['balance_due'], 2, ',', ' ') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Mode de règlement</div>
                    <div class="fs-6 fw-semibold">{{ $summary['payment_mode'] ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Statuts</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge text-bg-secondary">{{ $summary['document_status'] ?? '-' }}</span>
                    <span class="badge text-bg-primary">{{ $summary['operational_status'] ?? '-' }}</span>
                    <span class="badge text-bg-warning">{{ $summary['validation_status'] ?? '-' }}</span>

                    @if($summary['is_forecast'])
                    <span class="badge text-bg-dark">Prévisionnelle</span>
                    @endif

                    @if($summary['is_allocated'])
                    <span class="badge text-bg-info">Ventilée</span>
                    @endif

                    @if($summary['requires_approval'])
                    <span class="badge text-bg-light border">Validation requise</span>
                    @endif
                </div>
            </div>

            <ul class="nav nav-tabs mb-3" id="expenseTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Vue d’ensemble</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#validation">Validation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">Paiements</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#allocations">Ventilation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#attachments">Pièces jointes</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#comments">Commentaires</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#history">Historique</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <div class="card">
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Référence</dt>
                                <dd class="col-sm-8">{{ $summary['reference'] ?: '-' }}</dd>

                                <dt class="col-sm-4">Libellé</dt>
                                <dd class="col-sm-8">{{ $summary['label'] }}</dd>

                                <dt class="col-sm-4">Tiers</dt>
                                <dd class="col-sm-8">{{ $summary['third_party'] ?: '-' }}</dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ $summary['expense_type'] ?: '-' }}</dd>

                                <dt class="col-sm-4">Date facture</dt>
                                <dd class="col-sm-8">{{ optional($summary['invoice_date'])->format('d/m/Y') }}</dd>

                                <dt class="col-sm-4">Date prévue paiement</dt>
                                <dd class="col-sm-8">{{ optional($summary['planned_payment_date'])->format('d/m/Y') }}</dd>

                                <dt class="col-sm-4">Date paiement</dt>
                                <dd class="col-sm-8">{{ optional($summary['payment_date'])->format('d/m/Y') }}</dd>

                                <dt class="col-sm-4">Échéance</dt>
                                <dd class="col-sm-8">{{ optional($summary['due_date'])->format('d/m/Y') }}</dd>

                                <dt class="col-sm-4">Catégorie budgétaire</dt>
                                <dd class="col-sm-8">{{ $summary['budget_category'] ?: '-' }}</dd>

                                <dt class="col-sm-4">Mode ventilation</dt>
                                <dd class="col-sm-8">{{ $summary['allocation_mode'] ?: '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="validation">
                    <div class="card">
                        <div class="card-body">
                            @forelse($expense->approvals as $approval)
                            <div class="border rounded p-3 mb-3">
                                <div class="fw-semibold">{{ $approval->approver?->name ?? 'Validateur' }}</div>
                                <div class="text-muted small mb-2">
                                    Statut : {{ $approval->status?->label() ?? '-' }}
                                </div>
                                <div>{{ $approval->comment ?: 'Aucun commentaire.' }}</div>
                            </div>
                            @empty
                            <div class="text-muted">Aucune étape de validation enregistrée.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="payments">
                    <div class="card">
                        <div class="card-body">
                            @if($actions['can_pay'])
                            <form method="POST" action="{{ route('expenses.payments.store', $expense) }}" class="row g-2 mb-4">
                                @csrf
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="Montant">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="payment_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="payment_method" class="form-control" placeholder="Mode de paiement">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-dark w-100">Enregistrer</button>
                                </div>
                            </form>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Mode</th>
                                            <th>Référence</th>
                                            <th>Payé par</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expense->payments as $payment)
                                        <tr>
                                            <td>{{ optional($payment->payment_date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format((float) $payment->amount, 2, ',', ' ') }}</td>
                                            <td>{{ $payment->payment_method ?: '-' }}</td>
                                            <td>{{ $payment->reference ?: '-' }}</td>
                                            <td>{{ $payment->payer?->name ?: '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-muted">Aucun paiement enregistré.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="allocations">
                    <div class="card">
                        <div class="card-body">
                            @if($actions['can_manage_allocation'])
                            <form method="POST" action="{{ route('expenses.allocations.monthly-equal', $expense) }}" class="row g-2 mb-4">
                                @csrf
                                <div class="col-md-4">
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary w-100">Générer ventilation mensuelle</button>
                                </div>
                            </form>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Libellé</th>
                                            <th>Date prévue</th>
                                            <th>Montant</th>
                                            <th>Payé</th>
                                            <th>Solde</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expense->allocations as $allocation)
                                        <tr>
                                            <td>{{ $allocation->allocation_number }}</td>
                                            <td>{{ $allocation->label }}</td>
                                            <td>{{ optional($allocation->planned_payment_date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format((float) $allocation->amount, 2, ',', ' ') }}</td>
                                            <td>{{ number_format((float) $allocation->amount_paid, 2, ',', ' ') }}</td>
                                            <td>{{ number_format((float) $allocation->balance_due, 2, ',', ' ') }}</td>
                                            <td>{{ $allocation->status?->label() ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-muted">Aucune ventilation.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="attachments">
                    <div class="card">
                        <div class="card-body">
                            @if($actions['can_upload_attachment'])
                            <form method="POST" action="{{ route('expenses.attachments.store', $expense) }}" enctype="multipart/form-data" class="row g-2 mb-4">
                                @csrf
                                <div class="col-md-9">
                                    <input type="file" name="file" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-secondary w-100">Ajouter</button>
                                </div>
                            </form>
                            @endif

                            @forelse($expense->attachments as $attachment)
                            <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $attachment->original_name }}</div>
                                    <div class="text-muted small">
                                        {{ $attachment->mime_type }} • {{ number_format($attachment->size / 1024, 1, ',', ' ') }} Ko
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-muted">Aucune pièce jointe.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="comments">
                    <div class="card">
                        <div class="card-body">
                            @if($actions['can_comment'])
                            <form method="POST" action="{{ route('expenses.comments.store', $expense) }}" class="mb-4">
                                @csrf
                                <textarea name="content" class="form-control mb-2" rows="3" placeholder="Ajouter un commentaire"></textarea>
                                <button class="btn btn-outline-secondary">Publier</button>
                            </form>
                            @endif

                            @forelse($expense->commentsThread as $comment)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">{{ $comment->user?->name ?? 'Utilisateur' }}</div>
                                    <div class="text-muted small">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="mt-2">{{ $comment->content }}</div>
                            </div>
                            @empty
                            <div class="text-muted">Aucun commentaire.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="history">
                    <div class="card">
                        <div class="card-body">
                            @forelse($expense->statusLogs as $log)
                            <div class="border-start border-3 ps-3 mb-3">
                                <div class="fw-semibold">{{ $log->action ?: 'Historique' }}</div>
                                <div class="text-muted small">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                    @if($log->user)
                                    • {{ $log->user->name }}
                                    @endif
                                </div>
                                <div class="mt-1">
                                    {{ $log->status_axis }} : {{ $log->old_status ?: '-' }} → {{ $log->new_status ?: '-' }}
                                </div>
                                @if($log->comment)
                                <div class="mt-1">{{ $log->comment }}</div>
                                @endif
                            </div>
                            @empty
                            <div class="text-muted">Aucun historique enregistré.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Timeline</div>
                <div class="card-body">
                    @forelse($timeline as $event)
                    <div class="border-start border-3 ps-3 mb-4">
                        <div class="fw-semibold">{{ $event['title'] }}</div>
                        <div class="text-muted small">
                            {{ \Illuminate\Support\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                            @if(!empty($event['user']))
                            • {{ $event['user'] }}
                            @endif
                        </div>
                        <div class="mt-1">{{ $event['description'] }}</div>
                    </div>
                    @empty
                    <div class="text-muted">Aucun événement à afficher.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection