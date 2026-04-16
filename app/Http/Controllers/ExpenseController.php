<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\ExpenseFiltersData;
use App\Http\Requests\ExpenseStoreRequest;
use App\Models\Expense;
use App\Models\SavedView;
use App\Models\Supplier;
use App\Services\ExpenseApprovalService;
use App\Services\ExpenseAttachmentService;
use App\Services\ExpenseCommentService;
use App\Services\ExpenseListService;
use App\Services\ExpenseService;
use App\Services\SavedViewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ExpenseDetailViewService;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly ExpenseService $expenseService,
        private readonly ExpenseApprovalService $approvalService,
        private readonly ExpenseCommentService $commentService,
        private readonly ExpenseAttachmentService $attachmentService,
        private readonly ExpenseListService $expenseListService,
        private readonly SavedViewService $savedViewService,
        private readonly ExpenseDetailViewService $expenseDetailViewService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Expense::class);
        $filters = ExpenseFiltersData::fromRequest($request);

        $expenses = $this->expenseListService->paginate($filters, 25);

        $savedViews = $this->savedViewService->listForUser(auth()->id(), 'expenses');
        $defaultView = $this->savedViewService->getDefaultForUser(auth()->id(), 'expenses');

        $suppliers = Supplier::query()->orderBy('name')->get(['id', 'name']);

        return view('expenses.index', [
            'expenses' => $expenses,
            'filters' => $filters,
            'savedViews' => $savedViews,
            'defaultView' => $defaultView,
            'suppliers' => $suppliers,
            'availableColumns' => $this->availableColumns(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Expense::class);

        return view('expenses.create', [
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
        ]);
    } 

    public function store(ExpenseStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Expense::class);

        $expense = $this->expenseService->create($request->validated());

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Dépense créée avec succès.');
    }

    public function show(Expense $expense): View
    {
        $this->authorize('view', $expense);

        $data = $this->expenseDetailViewService->build($expense);

        return view('expenses.show', $data);
    }

    public function edit(Expense $expense): View
    {
        $this->authorize('update', $expense);
        return view('expenses.edit', [
            'expense' => $expense,
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(ExpenseStoreRequest $request, Expense $expense): RedirectResponse
    {
        $this->authorize('update', $expense);
        $expense = $this->expenseService->update($expense, $request->validated());

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Dépense mise à jour avec succès.');
    }

    public function submitForApproval(Expense $expense): RedirectResponse
    {
        $this->authorize('submitForApproval', $expense);
        $this->approvalService->submit($expense, auth()->id());

        return back()->with('success', 'Dépense soumise en validation.');
    }

    public function approve(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('approve', $expense);
        $this->approvalService->approve(
            $expense,
            auth()->id(),
            $request->string('comment')->toString()
        );

        return back()->with('success', 'Dépense validée.');
    }

    public function reject(Request $request, Expense $expense): RedirectResponse
    {
        $this->approvalService->reject(
            $expense,
            auth()->id(),
            $request->string('comment')->toString()
        );

        return back()->with('success', 'Dépense rejetée.');
    }

    public function addComment(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('comment', $expense);

        $validated = $request->validate([
            'content' => ['required', 'string'],
            'comment_type' => ['nullable', 'string', 'max:50'],
            'is_internal' => ['nullable', 'boolean'],
        ]);

        $this->commentService->add(
            expense: $expense,
            userId: auth()->id(),
            content: $validated['content'],
            type: $validated['comment_type'] ?? 'general',
            isInternal: (bool) ($validated['is_internal'] ?? false),
        );

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function uploadAttachment(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('uploadAttachment', $expense);
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $this->attachmentService->upload(
            expense: $expense,
            file: $validated['file'],
            uploadedBy: auth()->id(),
            isPrimary: (bool) ($validated['is_primary'] ?? false),
        );

        return back()->with('success', 'Pièce jointe ajoutée.');
    }

    protected function availableColumns(): array
    {
        return [
            'reference' => 'Référence',
            'label' => 'Libellé',
            'third_party_name' => 'Tiers',
            'expense_type' => 'Type',
            'document_status' => 'Statut documentaire',
            'status' => 'Statut opérationnel',
            'validation_status' => 'Statut validation',
            'payment_mode' => 'Mode de règlement',
            'amount_ttc' => 'Montant TTC',
            'amount_paid' => 'Montant payé',
            'balance_due' => 'Solde',
            'invoice_date' => 'Date facture',
            'planned_payment_date' => 'Date prévue paiement',
            'due_date' => 'Échéance',
            'created_at' => 'Créée le',
        ];
    }
}
