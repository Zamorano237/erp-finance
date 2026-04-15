@extends('layouts.app')

@php($title = 'Trésorerie')
@php($subtitle = 'Simulation premium des flux et projection de solde')

@section('content')
<div class="page-head">
    <h1 class="page-title">Simulation de trésorerie</h1>
    <p class="page-subtitle">Lecture premium des paramètres de trésorerie et de l’impact projeté sur le solde.</p>
</div>

<div class="kpi-strip">
    <div class="kpi-card">
        <h4>Solde actuel</h4>
        <div class="value">{{ isset($currentBalance) ? number_format($currentBalance, 0, ',', ' ') : '0' }}</div>
        <div class="meta">Base de simulation</div>
    </div>

    <div class="kpi-card">
        <h4>Décaissements</h4>
        <div class="value">{{ isset($totalOutflows) ? number_format($totalOutflows, 0, ',', ' ') : '0' }}</div>
        <div class="meta">Flux sortants projetés</div>
    </div>

    <div class="kpi-card">
        <h4>Encaissements</h4>
        <div class="value">{{ isset($totalInflows) ? number_format($totalInflows, 0, ',', ' ') : '0' }}</div>
        <div class="meta">Flux entrants projetés</div>
    </div>

    <div class="kpi-card">
        <h4>Solde projeté</h4>
        <div class="value">{{ isset($projectedBalance) ? number_format($projectedBalance, 0, ',', ' ') : '0' }}</div>
        <div class="meta">Projection nette</div>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="table-shell">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Libellé</th>
                        <th>Date prévue</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows ?? [] as $row)
                        <tr>
                            <td>{{ $row['type'] ?? '-' }}</td>
                            <td>{{ $row['label'] ?? '-' }}</td>
                            <td>{{ $row['date'] ?? '-' }}</td>
                            <td>{{ isset($row['amount']) ? number_format((float) $row['amount'], 2, ',', ' ') : '-' }}</td>
                            <td><span class="badge">{{ $row['status'] ?? '-' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Aucune ligne de simulation disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection