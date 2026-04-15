<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\OptionList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomFieldController extends Controller
{
    public function index(): View
    {
        $customFields = CustomField::query()
            ->with('optionList')
            ->where('module_code', 'suppliers')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        $optionLists = OptionList::query()
            ->orderBy('label')
            ->get();

        return view('custom-fields.index', [
            'customFields' => $customFields,
            'optionLists' => $optionLists,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_code' => ['required', 'string', 'max:50'],
            'field_code' => ['required', 'string', 'max:100'],
            'label' => ['required', 'string', 'max:150'],
            'field_type' => ['required', 'in:text,number,date,boolean,select'],
            'option_list_id' => ['nullable', 'integer', 'exists:option_lists,id'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'show_in_form' => ['nullable', 'boolean'],
            'show_in_table' => ['nullable', 'boolean'],
            'show_in_filters' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string'],
            'default_value' => ['nullable', 'string', 'max:255'],
        ]);

        CustomField::create([
            'module_code' => $validated['module_code'],
            'field_code' => trim($validated['field_code']),
            'label' => trim($validated['label']),
            'field_type' => $validated['field_type'],
            'option_list_id' => $validated['field_type'] === 'select' ? ($validated['option_list_id'] ?? null) : null,
            'is_required' => (bool) $request->boolean('is_required'),
            'is_active' => (bool) $request->boolean('is_active', true),
            'show_in_form' => (bool) $request->boolean('show_in_form', true),
            'show_in_table' => (bool) $request->boolean('show_in_table'),
            'show_in_filters' => (bool) $request->boolean('show_in_filters'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'placeholder' => $validated['placeholder'] ?? null,
            'help_text' => $validated['help_text'] ?? null,
            'default_value' => $validated['default_value'] ?? null,
        ]);

        return redirect()
            ->route('custom-fields.index')
            ->with('success', 'Champ dynamique créé avec succès.');
    }

    public function update(Request $request, CustomField $customField): JsonResponse
    {
        $validated = $request->validate([
            'field' => ['required', 'in:label,field_code,field_type,option_list_id,sort_order,placeholder,help_text,default_value,is_required,show_in_form,show_in_table,show_in_filters'],
            'value' => ['nullable'],
        ]);

        $field = $validated['field'];
        $value = $validated['value'];

        if (in_array($field, ['field_type'], true) && $customField->values()->exists()) {
            return response()->json([
                'message' => 'Impossible de modifier le type d’un champ déjà utilisé.',
            ], 422);
        }

        if ($field === 'field_code' && $customField->values()->exists()) {
            return response()->json([
                'message' => 'Impossible de modifier le code technique d’un champ déjà utilisé.',
            ], 422);
        }

        if (in_array($field, ['is_required', 'show_in_form', 'show_in_table', 'show_in_filters'], true)) {
            $customField->{$field} = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false;
        } elseif ($field === 'sort_order') {
            $customField->{$field} = ($value === null || $value === '') ? 0 : (int) $value;
        } elseif ($field === 'option_list_id') {
            $customField->{$field} = ($value === null || $value === '') ? null : (int) $value;
        } else {
            $customField->{$field} = is_string($value) ? trim($value) : $value;
        }

        if ($field === 'field_type' && $customField->field_type !== 'select') {
            $customField->option_list_id = null;
        }

        $customField->save();

        return response()->json([
            'message' => 'Champ dynamique mis à jour.',
        ]);
    }

    public function toggle(CustomField $customField): JsonResponse
    {
        if ($customField->is_active && $customField->values()->exists()) {
            return response()->json([
                'message' => 'Ce champ est déjà utilisé. Désactivation bloquée.',
            ], 422);
        }

        $customField->is_active = ! $customField->is_active;
        $customField->save();

        return response()->json([
            'message' => $customField->is_active ? 'Champ activé.' : 'Champ désactivé.',
            'is_active' => (bool) $customField->is_active,
        ]);
    }

    public function destroy(CustomField $customField): JsonResponse
    {
        if ($customField->values()->exists()) {
            return response()->json([
                'message' => 'Suppression impossible : ce champ est déjà utilisé.',
            ], 422);
        }

        $customField->delete();

        return response()->json([
            'message' => 'Champ dynamique supprimé.',
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer'],
        ]);

        $fields = CustomField::query()
            ->whereIn('id', $validated['ordered_ids'])
            ->get()
            ->keyBy('id');

        foreach ($validated['ordered_ids'] as $index => $id) {
            if (isset($fields[$id])) {
                $fields[$id]->sort_order = $index + 1;
                $fields[$id]->save();
            }
        }

        return response()->json([
            'message' => 'Ordre des champs mis à jour.',
        ]);
    }
}