<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\OptionList;
use App\Models\Supplier;
use App\Models\UserTablePreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplierController extends Controller
{
    private const TABLE_KEY = 'suppliers.index';

    private function availableColumns(): array
    {
        return [
            'code' => 'Code',
            'name' => 'Raison sociale',
            'category' => 'Catégorie',
            'third_party_type' => 'Type tiers',
            'budget_category' => 'Catégorie budgétaire',
            'auxiliary_account' => 'Auxiliaire',
            'frequency' => 'Fréquence',
            'receipt_mode' => 'Mode de réception',
            'payment_mode' => 'Mode de règlement',
            'forecast_amount' => 'Montant prévisionnel',
            'default_label' => 'Libellé par défaut',
            'payment_delay_days' => 'Délai paiement',
            'vat_rate_default' => 'TVA défaut',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'is_active' => 'Actif',
        ];
    }

    private function defaultActiveColumns(): array
    {
        return [
            'code',
            'name',
            'category',
            'third_party_type',
            'budget_category',
            'payment_mode',
            'payment_delay_days',
            'is_active',
        ];
    }

    private function getConfigLists(): array
    {
        return [
            'supplier_categories' => OptionList::valuesFor('supplier_categories'),
            'supplier_frequencies' => OptionList::valuesFor('supplier_frequencies'),
            'supplier_receipt_modes' => OptionList::valuesFor('supplier_receipt_modes'),
            'supplier_payment_modes' => OptionList::valuesFor('supplier_payment_modes'),
            'tier_types' => OptionList::valuesFor('tier_types'),
            'budget_categories' => OptionList::valuesFor('budget_categories'),
        ];
    }

    private function getUserPreference(): ?UserTablePreference
    {
        return UserTablePreference::query()
            ->where('user_id', auth()->id())
            ->where('table_key', self::TABLE_KEY)
            ->first();
    }

    private function resolveActiveColumns(Request $request, array $columns): array
    {
        $requestedColumns = $request->input('columns');

        if (is_array($requestedColumns) && !empty($requestedColumns)) {
            $activeColumns = array_values(array_intersect($requestedColumns, array_keys($columns)));
            return empty($activeColumns) ? $this->defaultActiveColumns() : $activeColumns;
        }

        $preference = $this->getUserPreference();

        if ($preference && is_array($preference->columns) && !empty($preference->columns)) {
            $activeColumns = array_values(array_intersect($preference->columns, array_keys($columns)));

            if (!empty($activeColumns)) {
                return $activeColumns;
            }
        }

        return $this->defaultActiveColumns();
    }

    private function resolveSort(Request $request, array $columns): array
    {
        $allowedSorts = array_keys($columns);

        $sortBy = (string) $request->input('sort_by', 'name');
        $sortDirection = strtolower((string) $request->input('sort_direction', 'asc'));

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'name';
        }

        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'asc';
        }

        return [$sortBy, $sortDirection];
    }

    private function resolveFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->input('q', '')),
            'category' => (string) $request->input('category', ''),
            'third_party_type' => (string) $request->input('third_party_type', ''),
            'budget_category' => (string) $request->input('budget_category', ''),
            'payment_mode' => (string) $request->input('payment_mode', ''),
            'receipt_mode' => (string) $request->input('receipt_mode', ''),
            'frequency' => (string) $request->input('frequency', ''),
            'is_active' => (string) $request->input('is_active', ''),
        ];
    }

    private function getFormCustomFields()
    {
        return CustomField::query()
            ->forModule('suppliers')
            ->active()
            ->showInForm()
            ->with('optionList.items')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();
    }

    private function saveCustomFieldValues(Supplier $supplier, Request $request): void
    {
        $fields = CustomField::query()
            ->forModule('suppliers')
            ->active()
            ->showInForm()
            ->get()
            ->keyBy('id');

        $payload = $request->input('custom_fields', []);

        foreach ($fields as $fieldId => $field) {
            $rawValue = $payload[$fieldId] ?? null;

            $record = CustomFieldValue::firstOrNew([
                'custom_field_id' => $field->id,
                'entity_type' => 'suppliers',
                'entity_id' => $supplier->id,
            ]);

            $record->value_text = null;
            $record->value_number = null;
            $record->value_date = null;
            $record->value_boolean = null;

            if ($rawValue === null || $rawValue === '') {
                if ($record->exists) {
                    $record->delete();
                }
                continue;
            }

            switch ($field->field_type) {
                case 'number':
                    $record->value_number = (float) $rawValue;
                    break;

                case 'date':
                    $record->value_date = $rawValue;
                    break;

                case 'boolean':
                    $record->value_boolean = filter_var($rawValue, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false;
                    break;

                default:
                    $record->value_text = (string) $rawValue;
                    break;
            }

            $record->save();
        }
    }

    public function index(Request $request): View
    {
        $columns = $this->availableColumns();
        $columnDefaults = $this->defaultActiveColumns();
        $activeColumns = $this->resolveActiveColumns($request, $columns);
        $filters = $this->resolveFilters($request);
        $configLists = $this->getConfigLists();

        [$sortBy, $sortDirection] = $this->resolveSort($request, $columns);

        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 15;
        }

        $query = Supplier::query();

        if ($filters['q'] !== '') {
            $query->search($filters['q']);
        }

        if ($filters['category'] !== '') {
            $query->where('category', $filters['category']);
        }

        if ($filters['third_party_type'] !== '') {
            $query->where('third_party_type', $filters['third_party_type']);
        }

        if ($filters['budget_category'] !== '') {
            $query->where('budget_category', $filters['budget_category']);
        }

        if ($filters['payment_mode'] !== '') {
            $query->where('payment_mode', $filters['payment_mode']);
        }

        if ($filters['receipt_mode'] !== '') {
            $query->where('receipt_mode', $filters['receipt_mode']);
        }

        if ($filters['frequency'] !== '') {
            $query->where('frequency', $filters['frequency']);
        }

        if ($filters['is_active'] === '1' || $filters['is_active'] === '0') {
            $query->where('is_active', $filters['is_active'] === '1');
        }

        $suppliers = $query
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        $visibleSuppliers = collect($suppliers->items());

        $dashboard = [
            'total_suppliers' => $suppliers->total(),
            'visible_active_count' => $visibleSuppliers->where('is_active', true)->count(),
            'visible_inactive_count' => $visibleSuppliers->where('is_active', false)->count(),
            'categories_count' => count($configLists['supplier_categories'] ?? []),
            'payment_modes_count' => count($configLists['supplier_payment_modes'] ?? []),
            'top_categories' => $visibleSuppliers
                ->groupBy(fn ($item) => $item->category ?: 'Non renseignée')
                ->map(fn ($group) => $group->count())
                ->sortDesc()
                ->take(5)
                ->all(),
            'tier_breakdown' => $visibleSuppliers
                ->groupBy(fn ($item) => $item->third_party_type ?: 'Non renseigné')
                ->map(fn ($group) => $group->count())
                ->sortDesc()
                ->all(),
        ];

        return view('suppliers.index', [
            'suppliers' => $suppliers,
            'columns' => $columns,
            'columnDefaults' => $columnDefaults,
            'activeColumns' => $activeColumns,
            'filters' => $filters,
            'configLists' => $configLists,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'dashboard' => $dashboard,
        ]);
    }

    public function create(): View
    {
        return view('suppliers.form', [
            'supplier' => new Supplier(),
            'configLists' => $this->getConfigLists(),
            'customFormFields' => $this->getFormCustomFields(),
        ]);
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $supplier = null;

        DB::transaction(function () use ($request, &$supplier) {
            $supplier = Supplier::create($request->validated());
            $this->saveCustomFieldValues($supplier, $request);
        });

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    public function edit(Supplier $supplier): View
    {
        $supplier->load('customFieldValues');

        return view('suppliers.form', [
            'supplier' => $supplier,
            'configLists' => $this->getConfigLists(),
            'customFormFields' => $this->getFormCustomFields(),
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        DB::transaction(function () use ($request, $supplier) {
            $supplier->update($request->validated());
            $this->saveCustomFieldValues($supplier, $request);
        });

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function inlineUpdate(Request $request, Supplier $supplier): JsonResponse
    {
        $allowedFields = [
            'code',
            'name',
            'category',
            'third_party_type',
            'budget_category',
            'auxiliary_account',
            'frequency',
            'receipt_mode',
            'payment_mode',
            'forecast_amount',
            'default_label',
            'payment_delay_days',
            'vat_rate_default',
            'email',
            'phone',
            'is_active',
        ];

        $field = (string) $request->input('field');

        if (!in_array($field, $allowedFields, true)) {
            return response()->json([
                'message' => 'Champ non autorisé.',
            ], 422);
        }

        $value = $request->input('value');

        if (in_array($field, ['forecast_amount', 'vat_rate_default'], true)) {
            $value = ($value === '' || $value === null) ? null : (float) $value;
        }

        if ($field === 'payment_delay_days') {
            $value = ($value === '' || $value === null) ? null : (int) $value;
        }

        if ($field === 'is_active') {
            $value = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            $value = $value ?? false;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        $supplier->{$field} = $value;
        $supplier->save();

        return response()->json([
            'message' => 'Mise à jour enregistrée.',
        ]);
    }

    public function saveColumns(Request $request): JsonResponse
    {
        $columns = $request->input('columns', []);
        $availableColumns = array_keys($this->availableColumns());

        if (!is_array($columns)) {
            return response()->json([
                'message' => 'Format de colonnes invalide.',
            ], 422);
        }

        $columns = array_values(array_intersect($columns, $availableColumns));

        if (empty($columns)) {
            $columns = $this->defaultActiveColumns();
        }

        UserTablePreference::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'table_key' => self::TABLE_KEY,
            ],
            [
                'columns' => $columns,
            ]
        );

        return response()->json([
            'message' => 'Préférences de colonnes enregistrées.',
            'columns' => $columns,
        ]);
    }
}