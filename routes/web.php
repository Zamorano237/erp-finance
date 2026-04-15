<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OptionListController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TreasuryController;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::patch('suppliers/{supplier}/inline', [SupplierController::class, 'inlineUpdate'])
    ->name('suppliers.inline-update');
Route::post('suppliers/save-columns', [SupplierController::class, 'saveColumns'])
    ->name('suppliers.save-columns');

Route::get('options', [OptionListController::class, 'index'])->name('options.index');
Route::post('options/{optionList}/items', [OptionListController::class, 'storeItem'])
    ->name('options.items.store');
Route::patch('options/{optionList}/items/{itemId}', [OptionListController::class, 'updateItem'])
    ->name('options.items.update');
Route::patch('options/{optionList}/items/{itemId}/toggle', [OptionListController::class, 'toggleItem'])
    ->name('options.items.toggle');
Route::delete('options/{optionList}/items/{itemId}', [OptionListController::class, 'deleteItem'])
    ->name('options.items.delete');
Route::post('options/{optionList}/items/reorder', [OptionListController::class, 'reorderItems'])
    ->name('options.items.reorder');

Route::get('custom-fields', [CustomFieldController::class, 'index'])->name('custom-fields.index');
Route::post('custom-fields', [CustomFieldController::class, 'store'])->name('custom-fields.store');
Route::patch('custom-fields/{customField}', [CustomFieldController::class, 'update'])
    ->name('custom-fields.update');
Route::patch('custom-fields/{customField}/toggle', [CustomFieldController::class, 'toggle'])
    ->name('custom-fields.toggle');
Route::delete('custom-fields/{customField}', [CustomFieldController::class, 'destroy'])
    ->name('custom-fields.destroy');
Route::post('custom-fields/reorder', [CustomFieldController::class, 'reorder'])
    ->name('custom-fields.reorder');

Route::resource('expenses', ExpenseController::class);
Route::post('expenses/{expense}/generate-allocation', [ExpenseController::class, 'generateAllocation'])
    ->name('expenses.generate-allocation');

Route::resource('budgets', BudgetController::class)->parameters([
    'budgets' => 'budget',
]);

Route::get('treasury', [TreasuryController::class, 'index'])->name('treasury.index');