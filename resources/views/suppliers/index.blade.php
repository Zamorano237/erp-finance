@extends('layouts.app')

@php($title = 'Référentiel fournisseurs premium')
@php($subtitle = 'Dashboard fournisseur avancé et table premium')

@section('content')
<style>
    .supplier-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .supplier-kpi-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-sm);
        padding: 18px 20px;
        min-height: 124px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .supplier-kpi-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
    }

    .supplier-kpi-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-soft);
        margin-bottom: 8px;
    }

    .supplier-kpi-value {
        font-size: 34px;
        line-height: 1;
        font-weight: 800;
        color: #17344b;
        letter-spacing: -.04em;
    }

    .supplier-kpi-meta {
        color: var(--text-soft);
        font-size: 12px;
    }

    .supplier-kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
    }

    .supplier-kpi-icon.red {
        background: #fff1f1;
        color: #ef4444;
    }

    .supplier-kpi-icon.orange {
        background: #fff7ed;
        color: #f59e0b;
    }

    .supplier-kpi-icon.green {
        background: #ecfdf3;
        color: #22c55e;
    }

    .supplier-kpi-icon.purple {
        background: #f3e8ff;
        color: #8b5cf6;
    }

    .supplier-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 20px;
    }

    .supplier-analytics-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-sm);
        padding: 20px;
    }

    .supplier-analytics-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .supplier-analytics-title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #17344b;
    }

    .supplier-bars {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .supplier-bar-row {
        display: grid;
        grid-template-columns: 180px 1fr 48px;
        gap: 12px;
        align-items: center;
    }

    .supplier-bar-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .supplier-bar-track {
        height: 10px;
        background: #edf2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .supplier-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #11a7a8 0%, #1089cf 100%);
    }

    .supplier-bar-value {
        font-size: 12px;
        font-weight: 800;
        color: var(--text-soft);
        text-align: right;
    }

    .supplier-table-tools {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .supplier-sort-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fff;
        font-weight: 800;
        text-decoration: none;
    }

    .supplier-sort-link.dim {
        opacity: .9;
    }

    .supplier-sort-link.active {
        opacity: 1;
    }

    .supplier-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 4px;
    }

    .supplier-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 32px;
        padding: 0 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--border);
        font-size: 12px;
        font-weight: 700;
        color: var(--text-soft);
    }

    .supplier-inline-input {
        width: 100%;
        min-height: 38px;
        border: 1px solid transparent;
        background: transparent;
        border-radius: 10px;
        padding: 6px 10px;
        outline: none;
        transition: .2s ease;
    }

    .supplier-inline-input:hover {
        background: #f7fbfe;
        border-color: #e3edf5;
    }

    .supplier-inline-input:focus {
        background: #fff;
        border-color: rgba(17, 167, 168, .5);
        box-shadow: 0 0 0 4px rgba(17, 167, 168, .08);
    }

    .supplier-inline-input.saving {
        background: #fffbe8;
    }

    .supplier-inline-input.saved {
        background: #ecfdf3;
        border-color: #bae6c6;
    }

    .supplier-inline-input.error {
        background: #fff1f2;
        border-color: #fecdd3;
    }

    @media (max-width: 1280px) {
        .supplier-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .supplier-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .supplier-kpi-grid {
            grid-template-columns: 1fr;
        }

        .supplier-bar-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }

    .toolbar-inline-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .supplier-table-shell-wide {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 6px;
    }

    .supplier-table-shell-wide .premium-table {
        width: max-content;
        min-width: 100%;
    }

    .supplier-table-shell-wide .premium-table th,
    .supplier-table-shell-wide .premium-table td {
        padding: 14px 14px;
        white-space: nowrap;
    }

    .supplier-table-shell-wide .premium-table th.actions-col,
    .supplier-table-shell-wide .premium-table td.actions-col {
        min-width: 180px;
        width: 180px;
        white-space: normal;
    }

    .supplier-table-shell-wide .table-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .supplier-table-shell-wide .btn.btn-sm {
        min-width: 44px;
        min-height: 44px;
        padding: 0;
        justify-content: center;
    }

    .supplier-modal-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .supplier-modal-grid-2 .list-row {
        min-height: 84px;
        align-items: flex-start;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .supplier-modal-grid-2 .list-row-main {
        width: 100%;
    }

    .supplier-modal-grid-2 .list-row-title {
        margin-bottom: 8px;
    }

    @media (max-width: 1100px) {
        .supplier-modal-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .supplier-filter-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }

    .supplier-filter-actions .btn {
        margin: 0;
    }

    /* Colonne Actions toujours visible à droite */
    #suppliersTable th.actions-col,
    #suppliersTable td.actions-col {
        position: sticky;
        right: 0;
        z-index: 3;
    }

    /* Fond propre pour éviter la transparence pendant le scroll */
    #suppliersTable th.actions-col {
        background: linear-gradient(180deg, #0b4d62 0%, #083f51 100%);
        box-shadow: -8px 0 12px rgba(8, 63, 81, 0.08);
    }


    /* Si tu as un zebra stripe, on garde un fond cohérent */
    #suppliersTable tbody tr:nth-child(even) td.actions-col {
        background: #fbfdff;
    }

    /* La cellule actions doit avoir une largeur stable */
    #suppliersTable th.actions-col,
    #suppliersTable td.actions-col {
        min-width: 170px;
        width: 170px;
    }
</style>

<div class="page-head">
    <h1 class="page-title">Référentiel fournisseurs</h1>
    <p class="page-subtitle">
        Vue premium avancée avec KPI métier, répartition, colonnes dynamiques, inline edit et détail fournisseur.
    </p>
</div>

<div class="supplier-kpi-grid">
    <div class="supplier-kpi-card">
        <div class="supplier-kpi-top">
            <div>
                <div class="supplier-kpi-label">Total fournisseurs</div>
                <div class="supplier-kpi-value">{{ $dashboard['total_suppliers'] ?? 0 }}</div>
            </div>
            <div class="supplier-kpi-icon red">
                <i data-lucide="building-2"></i>
            </div>
        </div>
        <div class="supplier-kpi-meta">Volume total du référentiel</div>
    </div>

    <div class="supplier-kpi-card">
        <div class="supplier-kpi-top">
            <div>
                <div class="supplier-kpi-label">Actifs visibles</div>
                <div class="supplier-kpi-value">{{ $dashboard['visible_active_count'] ?? 0 }}</div>
            </div>
            <div class="supplier-kpi-icon orange">
                <i data-lucide="badge-check"></i>
            </div>
        </div>
        <div class="supplier-kpi-meta">Fournisseurs actifs dans la page</div>
    </div>

    <div class="supplier-kpi-card">
        <div class="supplier-kpi-top">
            <div>
                <div class="supplier-kpi-label">Inactifs visibles</div>
                <div class="supplier-kpi-value">{{ $dashboard['visible_inactive_count'] ?? 0 }}</div>
            </div>
            <div class="supplier-kpi-icon green">
                <i data-lucide="badge-x"></i>
            </div>
        </div>
        <div class="supplier-kpi-meta">Fournisseurs inactifs dans la page</div>
    </div>

    <div class="supplier-kpi-card">
        <div class="supplier-kpi-top">
            <div>
                <div class="supplier-kpi-label">Catégories / modes</div>
                <div class="supplier-kpi-value">{{ $dashboard['categories_count'] ?? 0 }}/{{ $dashboard['payment_modes_count'] ?? 0 }}</div>
            </div>
            <div class="supplier-kpi-icon purple">
                <i data-lucide="layout-grid"></i>
            </div>
        </div>
        <div class="supplier-kpi-meta">Listes paramétrables actives</div>
    </div>
</div>

<div class="supplier-grid-2">
    <div class="supplier-analytics-card">
        <div class="supplier-analytics-head">
            <h3 class="supplier-analytics-title">Top catégories</h3>
            <span class="badge info">Page courante</span>
        </div>

        <div class="supplier-bars">
            @if(!empty($dashboard['top_categories'] ?? []))
            @foreach(($dashboard['top_categories'] ?? []) as $label => $value)
            <div class="supplier-bar-row">
                <div class="supplier-bar-label">{{ $label }}</div>
                <div class="supplier-bar-track">
                    <div
                        class="supplier-bar-fill"
                        style="width: {{
                                    max(($dashboard['top_categories'] ?? [1])) > 0
                                        ? (($value / max(($dashboard['top_categories'] ?? [1]))) * 100)
                                        : 0
                                }}%;"></div>
                </div>
                <div class="supplier-bar-value">{{ $value }}</div>
            </div>
            @endforeach
            @else
            <div class="muted">Aucune donnée disponible.</div>
            @endif
        </div>
    </div>

    <div class="supplier-analytics-card">
        <div class="supplier-analytics-head">
            <h3 class="supplier-analytics-title">Répartition type tiers</h3>
            <span class="badge info">Page courante</span>
        </div>

        <div class="supplier-bars">
            @if(!empty($dashboard['tier_breakdown'] ?? []))
            @foreach(($dashboard['tier_breakdown'] ?? []) as $label => $value)
            <div class="supplier-bar-row">
                <div class="supplier-bar-label">{{ $label }}</div>
                <div class="supplier-bar-track">
                    <div
                        class="supplier-bar-fill"
                        style="width: {{
                                    max(($dashboard['tier_breakdown'] ?? [1])) > 0
                                        ? (($value / max(($dashboard['tier_breakdown'] ?? [1]))) * 100)
                                        : 0
                                }}%;"></div>
                </div>
                <div class="supplier-bar-value">{{ $value }}</div>
            </div>
            @endforeach
            @else
            <div class="muted">Aucune donnée disponible.</div>
            @endif
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-toolbar">
        <form method="GET" class="toolbar-grid two-rows">
            <div class="search-wrap">
                <span class="search-icon"><i data-lucide="search"></i></span>
                <input
                    class="field"
                    type="text"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="Rechercher par code, nom, catégorie...">
            </div>

            <select class="field-select" name="category">
                <option value="">Toutes catégories</option>
                @foreach($configLists['supplier_categories'] ?? [] as $value)
                <option value="{{ $value }}" @selected(($filters['category'] ?? '' )===$value)>{{ $value }}</option>
                @endforeach
            </select>

            <select class="field-select" name="third_party_type">
                <option value="">Tous types tiers</option>
                @foreach($configLists['tier_types'] ?? [] as $value)
                <option value="{{ $value }}" @selected(($filters['third_party_type'] ?? '' )===$value)>{{ $value }}</option>
                @endforeach
            </select>

            <select class="field-select" name="payment_mode">
                <option value="">Tous modes règlement</option>
                @foreach($configLists['supplier_payment_modes'] ?? [] as $value)
                <option value="{{ $value }}" @selected(($filters['payment_mode'] ?? '' )===$value)>{{ $value }}</option>
                @endforeach
            </select>

            <select class="field-select" name="is_active">
                <option value="">Actifs + inactifs</option>
                <option value="1" @selected(($filters['is_active'] ?? '' )==='1' )>Actifs</option>
                <option value="0" @selected(($filters['is_active'] ?? '' )==='0' )>Inactifs</option>
            </select>

            <select class="field-select" name="per_page">
                @foreach([10,15,25,50,100] as $size)
                <option value="{{ $size }}" @selected(($perPage ?? 15)===$size)>{{ $size }} lignes</option>
                @endforeach
            </select>
            @foreach(($filterCustomFields ?? []) as $field)
            @if($field->field_type === 'boolean')
            <select class="field-select" name="cf_{{ $field->id }}">
                <option value="">{{ $field->label }}</option>
                <option value="1" @selected(($filters['cf_' . $field->id] ?? '') === '1')>Oui</option>
                <option value="0" @selected(($filters['cf_' . $field->id] ?? '') === '0')>Non</option>
            </select>

            @elseif($field->field_type === 'select')
            <select class="field-select" name="cf_{{ $field->id }}">
                <option value="">{{ $field->label }}</option>
                @foreach($field->optionValues() as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected(($filters['cf_' . $field->id] ?? '') === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
                @endforeach
            </select>

            @elseif($field->field_type === 'date')
            <input
                class="field"
                type="date"
                name="cf_{{ $field->id }}"
                value="{{ $filters['cf_' . $field->id] ?? '' }}">

            @elseif($field->field_type === 'number')
            <input
                class="field"
                type="number"
                step="0.01"
                name="cf_{{ $field->id }}"
                value="{{ $filters['cf_' . $field->id] ?? '' }}"
                placeholder="{{ $field->label }}">

            @else
            <input
                class="field"
                type="text"
                name="cf_{{ $field->id }}"
                value="{{ $filters['cf_' . $field->id] ?? '' }}"
                placeholder="{{ $field->label }}">
            @endif
            @endforeach
            <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'name' }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'asc' }}">

            <div class="toolbar-actions supplier-filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter"></i>
                    Filtrer
                </button>

                <a href="{{ route('suppliers.index') }}" class="btn btn-light">
                    <i data-lucide="rotate-ccw"></i>
                    Réinitialiser
                </a>
            </div>
        </form>

        <div class="supplier-table-tools">
            <div class="supplier-chip-row">
                @foreach(($filters ?? []) as $key => $value)
                @if($value !== '' && $value !== null)
                <span class="supplier-chip">
                    <i data-lucide="funnel"></i>
                    {{ $key }} : {{ $value }}
                </span>
                @endif
                @endforeach
            </div>

            <div class="toolbar-right">
                <button type="button" class="btn btn-light" data-open-drawer="columnsDrawer">
                    <i data-lucide="columns-3"></i>
                    Colonnes
                </button>

                <a href="{{ route('options.index') }}" class="btn btn-light">
                    <i data-lucide="sliders-horizontal"></i>
                    Options
                </a>

                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Nouveau fournisseur
                </a>
            </div>
        </div>
    </div>

    <div class="table-meta">
        <div>
            <strong>{{ $suppliers->firstItem() ?? 0 }}</strong>
            à
            <strong>{{ $suppliers->lastItem() ?? 0 }}</strong>
            sur
            <strong>{{ $suppliers->total() }}</strong>
            fournisseurs
        </div>

        <div>
            Colonnes visibles :
            <strong>{{ count($activeColumns ?? []) }}</strong>
        </div>
    </div>

    <div class="panel-body" style="padding-top:0;">
        <div class="table-shell supplier-table-shell-wide">
            <table class="premium-table" id="suppliersTable">
                <thead>
                    <tr>
                        @foreach($columns as $key => $label)
                        <th data-column="{{ $key }}">
                            <a href="{{ route('suppliers.index', array_merge(request()->query(), [
                    'sort_by' => $key,
                    'sort_direction' => (($sortBy === $key && $sortDirection === 'asc') ? 'desc' : 'asc')
                ])) }}"
                                class="supplier-sort-link {{ $sortBy === $key ? 'active' : 'dim' }}">
                                <span>{{ $label }}</span>

                                @if($sortBy === $key && $sortDirection === 'asc')
                                <i data-lucide="arrow-up"></i>
                                @elseif($sortBy === $key && $sortDirection === 'desc')
                                <i data-lucide="arrow-down"></i>
                                @else
                                <i data-lucide="chevrons-up-down"></i>
                                @endif
                            </a>
                        </th>
                        @endforeach
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        @foreach($columns as $key => $label)
                        <td data-column="{{ $key }}">
                            @if(str_starts_with($key, 'cf_'))
                            @php($dynamicValue = $supplier->getCustomFieldResolvedValue((int) str_replace('cf_', '', $key)))

                            @if($dynamicValue === null || $dynamicValue === '')
                            -
                            @elseif($dynamicValue === true || $dynamicValue === '1' || $dynamicValue === 1)
                            <span class="badge success">Oui</span>
                            @elseif($dynamicValue === false || $dynamicValue === '0' || $dynamicValue === 0)
                            <span class="badge danger">Non</span>
                            @elseif(preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $dynamicValue))
                            {{ \Carbon\Carbon::parse($dynamicValue)->format('d/m/Y') }}
                            @elseif(is_numeric($dynamicValue))
                            {{ number_format((float) $dynamicValue, 2, ',', ' ') }}
                            @else
                            {{ $dynamicValue }}
                            @endif
                            @else
                            @switch($key)
                            @case('code')
                            @case('name')
                            @case('auxiliary_account')
                            @case('default_label')
                            @case('email')
                            @case('phone')
                            <input
                                type="text"
                                class="supplier-inline-input js-inline-edit"
                                data-id="{{ $supplier->id }}"
                                data-field="{{ $key }}"
                                value="{{ $supplier->{$key} }}">
                            @break

                            @case('payment_delay_days')
                            @case('forecast_amount')
                            @case('vat_rate_default')
                            <input
                                type="number"
                                step="{{ in_array($key, ['forecast_amount', 'vat_rate_default'], true) ? '0.01' : '1' }}"
                                class="supplier-inline-input js-inline-edit"
                                data-id="{{ $supplier->id }}"
                                data-field="{{ $key }}"
                                value="{{ $supplier->{$key} }}">
                            @break

                            @case('category')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="category">
                                <option value="">-</option>
                                @foreach($configLists['supplier_categories'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->category === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('third_party_type')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="third_party_type">
                                <option value="">-</option>
                                @foreach($configLists['tier_types'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->third_party_type === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('budget_category')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="budget_category">
                                <option value="">-</option>
                                @foreach($configLists['budget_categories'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->budget_category === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('frequency')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="frequency">
                                <option value="">-</option>
                                @foreach($configLists['supplier_frequencies'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->frequency === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('receipt_mode')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="receipt_mode">
                                <option value="">-</option>
                                @foreach($configLists['supplier_receipt_modes'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->receipt_mode === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('payment_mode')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="payment_mode">
                                <option value="">-</option>
                                @foreach($configLists['supplier_payment_modes'] ?? [] as $value)
                                <option value="{{ $value }}" @selected($supplier->payment_mode === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('is_active')
                            <select class="supplier-inline-input js-inline-edit" data-id="{{ $supplier->id }}" data-field="is_active">
                                <option value="1" @selected($supplier->is_active)>Oui</option>
                                <option value="0" @selected(! $supplier->is_active)>Non</option>
                            </select>
                            @break

                            @default
                            {{ $supplier->{$key} }}
                            @endswitch
                            @endif
                        </td>
                        @endforeach

                        <td class="actions-col">
                            <div class="table-actions">
                                <button
                                    type="button"
                                    class="btn btn-light btn-sm js-open-supplier-detail"
                                    data-open-modal="supplierDetailModal"
                                    data-code="{{ $supplier->code }}"
                                    data-name="{{ $supplier->name }}"
                                    data-category="{{ $supplier->category }}"
                                    data-tier="{{ $supplier->third_party_type }}"
                                    data-budget="{{ $supplier->budget_category }}"
                                    data-payment="{{ $supplier->payment_mode }}"
                                    data-frequency="{{ $supplier->frequency }}"
                                    data-email="{{ $supplier->email }}"
                                    data-phone="{{ $supplier->phone }}"
                                    data-delay="{{ $supplier->payment_delay_days }}"
                                    data-active="{{ $supplier->is_active ? 'Oui' : 'Non' }}">
                                    <i data-lucide="eye"></i>
                                </button>

                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="pencil"></i>
                                </a>

                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Supprimer ce fournisseur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="empty-state">Aucun fournisseur trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-footer">
        {{ $suppliers->links() }}
    </div>
</div>

<div class="drawer" id="columnsDrawer">
    <div class="drawer-backdrop" data-close-drawer></div>
    <div class="drawer-panel">
        <div class="drawer-head">
            <h3>Colonnes visibles</h3>
            <button type="button" class="drawer-close" data-close-drawer>&times;</button>
        </div>

        <div class="check-grid">
            @foreach($columns as $key => $label)
            <label class="check-tile">
                <input type="checkbox" class="js-column-toggle" value="{{ $key }}" @checked(in_array($key, $activeColumns ?? [], true))>
                <span>{{ $label }}</span>
            </label>
            @endforeach
        </div>

        <div class="drawer-actions">
            <button type="button" class="btn btn-light" id="resetColumns">
                <i data-lucide="rotate-ccw"></i>
                Réinitialiser
            </button>
            <button type="button" class="btn btn-primary" id="saveColumns">
                <i data-lucide="save"></i>
                Enregistrer
            </button>
        </div>
    </div>
</div>

<div class="modal" id="supplierDetailModal">
    <div class="modal-backdrop" data-close-modal></div>
    <div class="modal-panel sm">
        <div class="modal-head">
            <h3>Détail fournisseur</h3>
            <button type="button" class="modal-close" data-close-modal>&times;</button>
        </div>

        <div class="list-card supplier-modal-grid-2">
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Code</div>
                    <div class="list-row-subtitle" id="detail-code">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Raison sociale</div>
                    <div class="list-row-subtitle" id="detail-name">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Catégorie</div>
                    <div class="list-row-subtitle" id="detail-category">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Type tiers</div>
                    <div class="list-row-subtitle" id="detail-tier">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Catégorie budgétaire</div>
                    <div class="list-row-subtitle" id="detail-budget">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Mode règlement</div>
                    <div class="list-row-subtitle" id="detail-payment">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Fréquence</div>
                    <div class="list-row-subtitle" id="detail-frequency">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Email</div>
                    <div class="list-row-subtitle" id="detail-email">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Téléphone</div>
                    <div class="list-row-subtitle" id="detail-phone">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Délai paiement</div>
                    <div class="list-row-subtitle" id="detail-delay">-</div>
                </div>
            </div>
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Actif</div>
                    <div class="list-row-subtitle" id="detail-active">-</div>
                </div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-light" data-close-modal>Fermer</button>
        </div>
    </div>
</div>

<script>
    const defaultColumns = @json($columnDefaults ?? []);
    const initialColumns = @json($activeColumns ?? []);
    const columnsStorageKey = 'erp.suppliers.columns';
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function getActiveColumns() {
        const stored = localStorage.getItem(columnsStorageKey);
        if (!stored) return initialColumns;

        try {
            const parsed = JSON.parse(stored);
            return Array.isArray(parsed) && parsed.length ? parsed : initialColumns;
        } catch (e) {
            return initialColumns;
        }
    }

    function applyColumns() {
        const active = getActiveColumns();
        document.querySelectorAll('#suppliersTable [data-column]').forEach(el => {
            el.style.display = active.includes(el.dataset.column) ? '' : 'none';
        });
    }

    localStorage.setItem(columnsStorageKey, JSON.stringify(getActiveColumns()));
    applyColumns();

    const resetColumnsBtn = document.getElementById('resetColumns');
    if (resetColumnsBtn) {
        resetColumnsBtn.addEventListener('click', () => {
            document.querySelectorAll('.js-column-toggle').forEach(input => {
                input.checked = defaultColumns.includes(input.value);
            });
        });
    }

    const saveColumnsBtn = document.getElementById('saveColumns');
    if (saveColumnsBtn) {
        saveColumnsBtn.addEventListener('click', async () => {
            const columns = Array.from(document.querySelectorAll('.js-column-toggle:checked')).map(i => i.value);
            localStorage.setItem(columnsStorageKey, JSON.stringify(columns));

            await fetch('{{ route('
                suppliers.save - columns ') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        columns
                    })
                });

            applyColumns();
            document.getElementById('columnsDrawer').classList.remove('open');
        });
    }

    document.querySelectorAll('.js-inline-edit').forEach(input => {
        const trigger = async () => {
            input.classList.add('saving');

            const id = input.dataset.id;
            const field = input.dataset.field;
            const value = input.value;

            const response = await fetch(`/suppliers/${id}/inline`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    field,
                    value
                })
            });

            input.classList.remove('saving');
            input.classList.add(response.ok ? 'saved' : 'error');

            setTimeout(() => {
                input.classList.remove('saved', 'error');
            }, 1200);
        };

        if (input.tagName === 'SELECT') {
            input.addEventListener('change', trigger);
        } else {
            input.addEventListener('blur', trigger);
        }
    });

    document.querySelectorAll('.js-open-supplier-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('detail-code').textContent = btn.dataset.code || '-';
            document.getElementById('detail-name').textContent = btn.dataset.name || '-';
            document.getElementById('detail-category').textContent = btn.dataset.category || '-';
            document.getElementById('detail-tier').textContent = btn.dataset.tier || '-';
            document.getElementById('detail-budget').textContent = btn.dataset.budget || '-';
            document.getElementById('detail-payment').textContent = btn.dataset.payment || '-';
            document.getElementById('detail-frequency').textContent = btn.dataset.frequency || '-';
            document.getElementById('detail-email').textContent = btn.dataset.email || '-';
            document.getElementById('detail-phone').textContent = btn.dataset.phone || '-';
            document.getElementById('detail-delay').textContent = btn.dataset.delay || '-';
            document.getElementById('detail-active').textContent = btn.dataset.active || '-';
        });
    });
</script>
@endsection