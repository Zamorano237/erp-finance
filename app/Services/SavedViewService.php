<?php

namespace App\Services;

use App\Models\SavedView;
use Illuminate\Support\Facades\DB;

class SavedViewService
{
    public function saveForUser(
        int $userId,
        string $module,
        string $name,
        array $filters = [],
        array $columns = [],
        array $sort = [],
        array $options = [],
        ?string $description = null,
        bool $isDefault = false
    ): SavedView {
        return DB::transaction(function () use (
            $userId,
            $module,
            $name,
            $filters,
            $columns,
            $sort,
            $options,
            $description,
            $isDefault
        ) {
            if ($isDefault) {
                SavedView::query()
                    ->where('user_id', $userId)
                    ->where('module', $module)
                    ->update(['is_default' => false]);
            }

            return SavedView::create([
                'user_id' => $userId,
                'module' => $module,
                'name' => $name,
                'description' => $description,
                'filters' => $filters,
                'columns' => $columns,
                'sort' => $sort,
                'options' => $options,
                'is_default' => $isDefault,
                'is_shared' => false,
            ]);
        });
    }

    public function getDefaultForUser(int $userId, string $module): ?SavedView
    {
        return SavedView::query()
            ->where('user_id', $userId)
            ->where('module', $module)
            ->where('is_default', true)
            ->first();
    }

    public function listForUser(int $userId, string $module)
    {
        return SavedView::query()
            ->where('user_id', $userId)
            ->where('module', $module)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    public function delete(SavedView $savedView): void
    {
        $savedView->delete();
    }
}