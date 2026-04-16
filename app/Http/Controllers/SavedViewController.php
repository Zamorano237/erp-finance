<?php

namespace App\Http\Controllers;

use App\Services\SavedViewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedViewController extends Controller
{
    public function __construct(
        private readonly SavedViewService $savedViewService
    ) {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'filters_json' => ['nullable', 'string'],
            'columns_json' => ['nullable', 'string'],
            'sort_json' => ['nullable', 'string'],
            'options_json' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $filters = json_decode($validated['filters_json'] ?? '{}', true);
        $columns = json_decode($validated['columns_json'] ?? '[]', true);
        $sort = json_decode($validated['sort_json'] ?? '{}', true);
        $options = json_decode($validated['options_json'] ?? '{}', true);

        $this->savedViewService->saveForUser(
            userId: auth()->id(),
            module: $validated['module'],
            name: $validated['name'],
            filters: is_array($filters) ? $filters : [],
            columns: is_array($columns) ? $columns : [],
            sort: is_array($sort) ? $sort : [],
            options: is_array($options) ? $options : [],
            description: $validated['description'] ?? null,
            isDefault: (bool) ($validated['is_default'] ?? false),
        );

        return back()->with('success', 'Vue sauvegardée.');
    }
}