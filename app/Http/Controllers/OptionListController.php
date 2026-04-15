<?php

namespace App\Http\Controllers;

use App\Models\OptionList;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OptionListController extends Controller
{
    public function index(): View
    {
        $optionLists = OptionList::query()
            ->with(['items' => function ($query) {
                $query->orderBy('sort_order')->orderBy('label');
            }])
            ->orderBy('label')
            ->get();

        $dashboard = [
            'lists_count' => $optionLists->count(),
            'items_count' => $optionLists->sum(fn ($list) => $list->items->count()),
            'active_items_count' => $optionLists->sum(fn ($list) => $list->items->where('is_active', true)->count()),
            'inactive_items_count' => $optionLists->sum(fn ($list) => $list->items->where('is_active', false)->count()),
        ];

        return view('options.index', [
            'optionLists' => $optionLists,
            'dashboard' => $dashboard,
        ]);
    }

    public function storeItem(Request $request, OptionList $optionList): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $nextSortOrder = ((int) $optionList->items()->max('sort_order')) + 1;

        $optionList->items()->create([
            'label' => trim($validated['label']),
            'value' => trim($validated['value']),
            'is_active' => true,
            'sort_order' => $nextSortOrder,
        ]);

        return redirect()
            ->route('options.index')
            ->with('success', 'Valeur ajoutée avec succès.');
    }

    public function updateItem(Request $request, OptionList $optionList, int $itemId): JsonResponse
    {
        $item = $optionList->items()->findOrFail($itemId);

        $validated = $request->validate([
            'field' => ['required', 'in:label,value'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $field = $validated['field'];
        $newValue = trim($validated['value']);
        $oldValue = (string) $item->{$field};

        if ($field === 'value' && $this->isItemUsed($optionList->code, $oldValue)) {
            return response()->json([
                'message' => 'Cette valeur est utilisée dans le module fournisseurs. Modification bloquée.',
            ], 422);
        }

        $item->{$field} = $newValue;
        $item->save();

        return response()->json([
            'message' => 'Valeur mise à jour.',
        ]);
    }

    public function toggleItem(OptionList $optionList, int $itemId): JsonResponse
    {
        $item = $optionList->items()->findOrFail($itemId);

        if ($item->is_active && $this->isItemUsed($optionList->code, (string) $item->value)) {
            return response()->json([
                'message' => 'Cette valeur est utilisée dans le module fournisseurs. Désactivation bloquée.',
            ], 422);
        }

        $item->is_active = ! $item->is_active;
        $item->save();

        return response()->json([
            'message' => $item->is_active ? 'Valeur activée.' : 'Valeur désactivée.',
            'is_active' => (bool) $item->is_active,
        ]);
    }

    public function deleteItem(OptionList $optionList, int $itemId): JsonResponse
    {
        $item = $optionList->items()->findOrFail($itemId);

        if ($this->isItemUsed($optionList->code, (string) $item->value)) {
            return response()->json([
                'message' => 'Suppression impossible : cette valeur est utilisée dans le module fournisseurs.',
            ], 422);
        }

        $item->delete();

        return response()->json([
            'message' => 'Valeur supprimée.',
        ]);
    }

    public function reorderItems(Request $request, OptionList $optionList): JsonResponse
    {
        $validated = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer'],
        ]);

        $ids = $validated['ordered_ids'];
        $items = $optionList->items()->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($ids as $index => $id) {
            if (isset($items[$id])) {
                $items[$id]->sort_order = $index + 1;
                $items[$id]->save();
            }
        }

        return response()->json([
            'message' => 'Ordre mis à jour.',
        ]);
    }

    private function isItemUsed(string $optionListCode, string $value): bool
    {
        $mapping = [
            'supplier_categories' => 'category',
            'supplier_frequencies' => 'frequency',
            'supplier_receipt_modes' => 'receipt_mode',
            'supplier_payment_modes' => 'payment_mode',
            'tier_types' => 'third_party_type',
            'budget_categories' => 'budget_category',
        ];

        if (!isset($mapping[$optionListCode])) {
            return false;
        }

        return Supplier::query()
            ->where($mapping[$optionListCode], $value)
            ->exists();
    }
}