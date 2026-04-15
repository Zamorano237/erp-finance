@extends('layouts.app')

@php($title = $supplier->exists ? 'Modifier un fournisseur' : 'Créer un fournisseur')
@php($subtitle = 'Saisie premium du référentiel fournisseurs')

@section('content')
<div class="page-head">
    <h1 class="page-title">{{ $supplier->exists ? 'Modification fournisseur' : 'Création fournisseur' }}</h1>
    <p class="page-subtitle">
        Formulaire premium de gestion du référentiel fournisseurs avec structure métier claire.
    </p>
</div>

<form method="POST" action="{{ $supplier->exists ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
    @csrf
    @if($supplier->exists)
        @method('PUT')
    @endif

    <div class="grid-2">
        <div class="form-card">
            <h3 class="section-title">Identité fournisseur</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Code</label>
                    <input class="field" type="text" name="code" value="{{ old('code', $supplier->code) }}">
                    @error('code')<div class="muted">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label>Raison sociale</label>
                    <input class="field" type="text" name="name" value="{{ old('name', $supplier->name) }}">
                    @error('name')<div class="muted">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label>Catégorie</label>
                    <select class="field-select" name="category">
                        <option value="">Choisir</option>
                        @foreach(($configLists['supplier_categories'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('category', $supplier->category) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Type tiers</label>
                    <select class="field-select" name="third_party_type">
                        <option value="">Choisir</option>
                        @foreach(($configLists['tier_types'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('third_party_type', $supplier->third_party_type) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Catégorie budgétaire</label>
                    <select class="field-select" name="budget_category">
                        <option value="">Choisir</option>
                        @foreach(($configLists['budget_categories'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('budget_category', $supplier->budget_category) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Auxiliaire</label>
                    <input class="field" type="text" name="auxiliary_account" value="{{ old('auxiliary_account', $supplier->auxiliary_account) }}">
                </div>

                <div class="form-field">
                    <label>Email</label>
                    <input class="field" type="email" name="email" value="{{ old('email', $supplier->email) }}">
                </div>

                <div class="form-field">
                    <label>Téléphone</label>
                    <input class="field" type="text" name="phone" value="{{ old('phone', $supplier->phone) }}">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3 class="section-title">Paramètres de gestion</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Fréquence</label>
                    <select class="field-select" name="frequency">
                        <option value="">Choisir</option>
                        @foreach(($configLists['supplier_frequencies'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('frequency', $supplier->frequency) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Mode de réception</label>
                    <select class="field-select" name="receipt_mode">
                        <option value="">Choisir</option>
                        @foreach(($configLists['supplier_receipt_modes'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('receipt_mode', $supplier->receipt_mode) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Mode de règlement</label>
                    <select class="field-select" name="payment_mode">
                        <option value="">Choisir</option>
                        @foreach(($configLists['supplier_payment_modes'] ?? []) as $value)
                            <option value="{{ $value }}" @selected(old('payment_mode', $supplier->payment_mode) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Délai paiement (jours)</label>
                    <input class="field" type="number" name="payment_delay_days" value="{{ old('payment_delay_days', $supplier->payment_delay_days) }}">
                </div>

                <div class="form-field">
                    <label>Montant prévisionnel</label>
                    <input class="field" type="number" step="0.01" name="forecast_amount" value="{{ old('forecast_amount', $supplier->forecast_amount) }}">
                </div>

                <div class="form-field">
                    <label>TVA défaut</label>
                    <input class="field" type="number" step="0.01" name="vat_rate_default" value="{{ old('vat_rate_default', $supplier->vat_rate_default) }}">
                </div>

                <div class="form-field" style="grid-column:1 / -1;">
                    <label>Libellé par défaut</label>
                    <input class="field" type="text" name="default_label" value="{{ old('default_label', $supplier->default_label) }}">
                </div>

                <div class="form-field" style="grid-column:1 / -1;">
                    <label>Notes</label>
                    <textarea class="field-textarea" name="notes">{{ old('notes', $supplier->notes) }}</textarea>
                </div>

                <div class="form-field">
                    <label>Actif</label>
                    <select class="field-select" name="is_active">
                        <option value="1" @selected((string) old('is_active', $supplier->is_active ?? 1) === '1')>Oui</option>
                        <option value="0" @selected((string) old('is_active', $supplier->is_active ?? 1) === '0')>Non</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('suppliers.index') }}" class="btn btn-light">
                    <i data-lucide="arrow-left"></i>
                    Retour
                </a>

                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    {{ $supplier->exists ? 'Mettre à jour' : 'Créer le fournisseur' }}
                </button>
            </div>
        </div>
    </div>
@if(isset($customFormFields) && $customFormFields->count() > 0)
    <div class="form-card" style="margin-top: 18px;">
        <h3 class="section-title">Champs dynamiques</h3>

        <div class="form-grid">
            @foreach($customFormFields as $field)
                <div class="form-field">
                    <label>{{ $field->label }}</label>

                    @if($field->field_type === 'number')
                        <input
                            class="field"
                            type="number"
                            step="0.01"
                            name="custom_fields[{{ $field->id }}]"
                            value="{{ old('custom_fields.' . $field->id, '') }}"
                        >

                    @elseif($field->field_type === 'date')
                        <input
                            class="field"
                            type="date"
                            name="custom_fields[{{ $field->id }}]"
                            value="{{ old('custom_fields.' . $field->id, '') }}"
                        >

                    @elseif($field->field_type === 'boolean')
                        <select class="field-select" name="custom_fields[{{ $field->id }}]">
                            <option value="">Choisir</option>
                            <option value="1" @selected(old('custom_fields.' . $field->id) == '1')>Oui</option>
                            <option value="0" @selected(old('custom_fields.' . $field->id) == '0')>Non</option>
                        </select>

                    @elseif($field->field_type === 'select')
                        <select class="field-select" name="custom_fields[{{ $field->id }}]">
                            <option value="">Choisir</option>
                        </select>

                    @else
                        <input
                            class="field"
                            type="text"
                            name="custom_fields[{{ $field->id }}]"
                            value="{{ old('custom_fields.' . $field->id, '') }}"
                        >
                    @endif

                    @if(!empty($field->help_text))
                        <div class="muted">{{ $field->help_text }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
</form>
@endsection