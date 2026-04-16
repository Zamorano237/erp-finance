<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Dépenses</h1>
            <p class="text-muted mb-0">Pilotage, filtres, validation et suivi des paiements.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('expenses.dashboard')); ?>" class="btn btn-outline-secondary">Dashboard</a>
            <a href="<?php echo e(route('expenses.validation-center')); ?>" class="btn btn-outline-warning">Centre de validation</a>
            <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-primary">Nouvelle dépense</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('expenses.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" value="<?php echo e(request('search')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="expense_type" class="form-select">
                        <option value="">Tous</option>
                        <option value="purchase" <?php if(request('expense_type')==='purchase' ): echo 'selected'; endif; ?>>Fournisseur</option>
                        <option value="bank" <?php if(request('expense_type')==='bank' ): echo 'selected'; endif; ?>>Banque</option>
                        <option value="social" <?php if(request('expense_type')==='social' ): echo 'selected'; endif; ?>>Organisme social</option>
                        <option value="salary" <?php if(request('expense_type')==='salary' ): echo 'selected'; endif; ?>>Salaire</option>
                        <option value="expense_report" <?php if(request('expense_type')==='expense_report' ): echo 'selected'; endif; ?>>Note de frais</option>
                        <option value="administration" <?php if(request('expense_type')==='administration' ): echo 'selected'; endif; ?>>Administration</option>
                        <option value="other" <?php if(request('expense_type')==='other' ): echo 'selected'; endif; ?>>Autre</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Statut opérationnel</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="draft" <?php if(request('status')==='draft' ): echo 'selected'; endif; ?>>Brouillon</option>
                        <option value="open" <?php if(request('status')==='open' ): echo 'selected'; endif; ?>>Ouverte</option>
                        <option value="in_validation" <?php if(request('status')==='in_validation' ): echo 'selected'; endif; ?>>En validation</option>
                        <option value="waiting_payment" <?php if(request('status')==='waiting_payment' ): echo 'selected'; endif; ?>>En attente paiement</option>
                        <option value="partially_paid" <?php if(request('status')==='partially_paid' ): echo 'selected'; endif; ?>>Partielle</option>
                        <option value="paid" <?php if(request('status')==='paid' ): echo 'selected'; endif; ?>>Payée</option>
                        <option value="overdue" <?php if(request('status')==='overdue' ): echo 'selected'; endif; ?>>En retard</option>
                        <option value="rejected" <?php if(request('status')==='rejected' ): echo 'selected'; endif; ?>>Rejetée</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Validation</label>
                    <select name="validation_status" class="form-select">
                        <option value="">Toutes</option>
                        <option value="not_submitted" <?php if(request('validation_status')==='not_submitted' ): echo 'selected'; endif; ?>>Non soumise</option>
                        <option value="pending" <?php if(request('validation_status')==='pending' ): echo 'selected'; endif; ?>>En attente</option>
                        <option value="approved" <?php if(request('validation_status')==='approved' ): echo 'selected'; endif; ?>>Validée</option>
                        <option value="rejected" <?php if(request('validation_status')==='rejected' ): echo 'selected'; endif; ?>>Rejetée</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fournisseur</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($supplier->id); ?>" <?php if((string) request('supplier_id')===(string) $supplier->id): echo 'selected'; endif; ?>>
                            <?php echo e($supplier->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type de date</label>
                    <select name="date_type" class="form-select">
                        <option value="planned_payment_date" <?php if(request('date_type', 'planned_payment_date' )==='planned_payment_date' ): echo 'selected'; endif; ?>>Paiement prévu</option>
                        <option value="invoice_date" <?php if(request('date_type')==='invoice_date' ): echo 'selected'; endif; ?>>Date facture</option>
                        <option value="receipt_date" <?php if(request('date_type')==='receipt_date' ): echo 'selected'; endif; ?>>Date réception</option>
                        <option value="due_date" <?php if(request('date_type')==='due_date' ): echo 'selected'; endif; ?>>Échéance</option>
                        <option value="payment_date" <?php if(request('date_type')==='payment_date' ): echo 'selected'; endif; ?>>Date paiement</option>
                    </select>
                </div>

                <div class="col-md-8 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="<?php echo e(route('expenses.index')); ?>" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('saved-views.store')); ?>" class="row g-2">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="module" value="expenses">
                <input type="hidden" name="filters_json" value='<?php echo json_encode(request()->query(), 15, 512) ?>'>
                <input type="hidden" name="sort_json" value='<?php echo json_encode([
        "sort_by" => request("sort_by"), "sort_direction" => request("sort_direction")
    ], 512) ?>'>
                <input type="hidden" name="columns_json" value='<?php echo json_encode(array_keys($availableColumns), 15, 512) ?>'>
                <input type="hidden" name="options_json" value='<?php echo json_encode([], 15, 512) ?>'>

                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Nom de la vue" required>
                </div>

                <div class="col-md-5">
                    <input type="text" name="description" class="form-control" placeholder="Description facultative">
                </div>

                <div class="col-md-2 form-check d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="is_default" value="1" id="is_default">
                    <label class="form-check-label" for="is_default">Vue par défaut</label>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100">Sauver</button>
                </div>
            </form>

            <?php if($savedViews->count()): ?>
            <hr>
            <div class="d-flex flex-wrap gap-2">
                <?php $__currentLoopData = $savedViews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge text-bg-light border">
                    <?php echo e($view->name); ?><?php if($view->is_default): ?> (défaut) <?php endif; ?>
                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Libellé</th>
                        <th>Tiers</th>
                        <th>Type</th>
                        <th>Documentaire</th>
                        <th>Opérationnel</th>
                        <th>Validation</th>
                        <th class="text-end">TTC</th>
                        <th class="text-end">Payé</th>
                        <th class="text-end">Solde</th>
                        <th>Date prévue</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($expense->reference); ?></td>
                        <td><?php echo e($expense->label); ?></td>
                        <td><?php echo e($expense->third_party_name ?? $expense->supplier?->name); ?></td>
                        <td><?php echo e($expense->expense_type?->label() ?? '-'); ?></td>
                        <td><span class="badge text-bg-secondary"><?php echo e($expense->document_status?->label() ?? '-'); ?></span></td>
                        <td><span class="badge text-bg-primary"><?php echo e($expense->status?->label() ?? '-'); ?></span></td>
                        <td><span class="badge text-bg-warning"><?php echo e($expense->validation_status?->label() ?? '-'); ?></span></td>
                        <td class="text-end"><?php echo e(number_format((float) $expense->amount_ttc, 2, ',', ' ')); ?></td>
                        <td class="text-end"><?php echo e(number_format((float) $expense->amount_paid, 2, ',', ' ')); ?></td>
                        <td class="text-end"><?php echo e(number_format((float) $expense->balance_due, 2, ',', ' ')); ?></td>
                        <td><?php echo e(optional($expense->planned_payment_date)?->format('d/m/Y')); ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('expenses.show', $expense)); ?>" class="btn btn-sm btn-outline-secondary">Ouvrir</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Aucune dépense trouvée.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-body">
            <?php echo e($expenses->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/expenses/index.blade.php ENDPATH**/ ?>