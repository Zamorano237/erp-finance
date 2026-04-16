<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OptionListController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\ExpenseGenerationController;
use App\Http\Controllers\ExpenseAllocationController;
use App\Http\Controllers\ExpensePaymentController;
use App\Http\Controllers\ExpenseDashboardController;
use App\Http\Controllers\ExpenseValidationCenterController;
use App\Http\Controllers\SavedViewController;
use App\Http\Controllers\UserActionCenterController;
use App\Http\Controllers\UserNotificationController;

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

Route::post('expenses/{expense}/submit-for-approval', [ExpenseController::class, 'submitForApproval'])
    ->name('expenses.submit-for-approval');

Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])
    ->name('expenses.approve');

Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])
    ->name('expenses.reject');

Route::post('expenses/{expense}/comments', [ExpenseController::class, 'addComment'])
    ->name('expenses.comments.store');

Route::post('expenses/{expense}/attachments', [ExpenseController::class, 'uploadAttachment'])
    ->name('expenses.attachments.store');

Route::post('expense-generation-templates/{template}/generate', [ExpenseGenerationController::class, 'generate'])
    ->name('expense-generation-templates.generate');

Route::post('expenses/{expense}/realize', [ExpenseGenerationController::class, 'realize'])
    ->name('expenses.realize');

Route::post('expenses/{expense}/allocations/monthly-equal', [ExpenseAllocationController::class, 'monthlyEqual'])
    ->name('expenses.allocations.monthly-equal');

Route::post('expenses/{expense}/allocations/manual', [ExpenseAllocationController::class, 'manual'])
    ->name('expenses.allocations.manual');

Route::delete('expenses/{expense}/allocations', [ExpenseAllocationController::class, 'remove'])
    ->name('expenses.allocations.remove');

Route::post('expenses/{expense}/payments', [ExpensePaymentController::class, 'payExpense'])
    ->name('expenses.payments.store');

Route::post('expense-allocations/{allocation}/payments', [ExpensePaymentController::class, 'payAllocation'])
    ->name('expense-allocations.payments.store');

Route::delete('payments/{payment}', [ExpensePaymentController::class, 'destroy'])
    ->name('payments.destroy');
Route::get('expenses-dashboard', [ExpenseDashboardController::class, 'index'])
    ->name('expenses.dashboard');

Route::get('expenses-validation-center', [ExpenseValidationCenterController::class, 'index'])
    ->name('expenses.validation-center');

Route::post('saved-views', [SavedViewController::class, 'store'])
    ->name('saved-views.store');

Route::delete('saved-views/{savedView}', [SavedViewController::class, 'destroy'])
    ->name('saved-views.destroy');


Route::get('action-center', [UserActionCenterController::class, 'index'])
    ->name('action-center.index');

Route::get('notifications', [UserNotificationController::class, 'index'])
    ->name('notifications.index');

Route::post('notifications/{notificationId}/read', [UserNotificationController::class, 'markAsRead'])
    ->name('notifications.read');

Route::post('notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])
    ->name('notifications.read-all');