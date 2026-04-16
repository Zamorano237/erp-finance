@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Nouvelle dépense</h1>
        <p class="text-muted mb-0">Création d’une dépense.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Référence</label>
                        <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Libellé</label>
                        <input type="text" name="label" class="form-control" value="{{ old('label') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fournisseur</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Sélectionner</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Montant TTC</label>
                        <input type="number" step="0.01" name="amount_ttc" class="form-control" value="{{ old('amount_ttc') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type de dépense</label>
                        <input type="text" name="expense_type" class="form-control" value="{{ old('expense_type') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mode de règlement</label>
                        <input type="text" name="payment_mode" class="form-control" value="{{ old('payment_mode') }}">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection