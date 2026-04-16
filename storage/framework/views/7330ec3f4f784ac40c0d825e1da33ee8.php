

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1"><?php echo e($summary['reference']); ?></div>
            <h1 class="h3 mb-1"><?php echo e($summary['label']); ?></h1>
            <div class="text-muted">
                <?php echo e($summary['third_party'] ?? 'Tiers non renseigné'); ?>

                <?php if($summary['expense_type']): ?>
                • <?php echo e($summary['expense_type']); ?>

                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-end">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $expense)): ?>
            <a href="<?php echo e(route('expenses.edit', $expense)); ?>" class="btn btn-outline-primary">
                Modifier
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('submitForApproval', $expense)): ?>
            <form method="POST" action="<?php echo e(route('expenses.submit-for-approval', $expense)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-warning">
                    Soumettre
                </button>
            </form>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $expense)): ?>
            <form method="POST" action="<?php echo e(route('expenses.approve', $expense)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success">
                    Valider
                </button>
            </form>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reject', $expense)): ?>
            <form method="POST" action="<?php echo e(route('expenses.reject', $expense)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-danger">
                    Rejeter
                </button>
            </form>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pay', $expense)): ?>
            <button type="button" class="btn btn-outline-dark">
                Enregistrer un paiement
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Montant TTC</div>
                    <div class="fs-5 fw-semibold"><?php echo e(number_format($summary['amount_ttc'], 2, ',', ' ')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Montant payé</div>
                    <div class="fs-5 fw-semibold"><?php echo e(number_format($summary['amount_paid'], 2, ',', ' ')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Solde</div>
                    <div class="fs-5 fw-semibold"><?php echo e(number_format($summary['balance_due'], 2, ',', ' ')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Mode de règlement</div>
                    <div class="fs-6 fw-semibold"><?php echo e($summary['payment_mode'] ?: '-'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Statuts</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge text-bg-secondary"><?php echo e($summary['document_status'] ?? '-'); ?></span>
                    <span class="badge text-bg-primary"><?php echo e($summary['operational_status'] ?? '-'); ?></span>
                    <span class="badge text-bg-warning"><?php echo e($summary['validation_status'] ?? '-'); ?></span>

                    <?php if($summary['is_forecast']): ?>
                    <span class="badge text-bg-dark">Prévisionnelle</span>
                    <?php endif; ?>

                    <?php if($summary['is_allocated']): ?>
                    <span class="badge text-bg-info">Ventilée</span>
                    <?php endif; ?>

                    <?php if($summary['requires_approval']): ?>
                    <span class="badge text-bg-light border">Validation requise</span>
                    <?php endif; ?>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3" id="expenseTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Vue d’ensemble</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#validation">Validation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">Paiements</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#allocations">Ventilation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#attachments">Pièces jointes</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#comments">Commentaires</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#history">Historique</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <div class="card">
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Référence</dt>
                                <dd class="col-sm-8"><?php echo e($summary['reference'] ?: '-'); ?></dd>

                                <dt class="col-sm-4">Libellé</dt>
                                <dd class="col-sm-8"><?php echo e($summary['label']); ?></dd>

                                <dt class="col-sm-4">Tiers</dt>
                                <dd class="col-sm-8"><?php echo e($summary['third_party'] ?: '-'); ?></dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8"><?php echo e($summary['expense_type'] ?: '-'); ?></dd>

                                <dt class="col-sm-4">Date facture</dt>
                                <dd class="col-sm-8"><?php echo e(optional($summary['invoice_date'])->format('d/m/Y')); ?></dd>

                                <dt class="col-sm-4">Date prévue paiement</dt>
                                <dd class="col-sm-8"><?php echo e(optional($summary['planned_payment_date'])->format('d/m/Y')); ?></dd>

                                <dt class="col-sm-4">Date paiement</dt>
                                <dd class="col-sm-8"><?php echo e(optional($summary['payment_date'])->format('d/m/Y')); ?></dd>

                                <dt class="col-sm-4">Échéance</dt>
                                <dd class="col-sm-8"><?php echo e(optional($summary['due_date'])->format('d/m/Y')); ?></dd>

                                <dt class="col-sm-4">Catégorie budgétaire</dt>
                                <dd class="col-sm-8"><?php echo e($summary['budget_category'] ?: '-'); ?></dd>

                                <dt class="col-sm-4">Mode ventilation</dt>
                                <dd class="col-sm-8"><?php echo e($summary['allocation_mode'] ?: '-'); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="validation">
                    <div class="card">
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $expense->approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border rounded p-3 mb-3">
                                <div class="fw-semibold"><?php echo e($approval->approver?->name ?? 'Validateur'); ?></div>
                                <div class="text-muted small mb-2">
                                    Statut : <?php echo e($approval->status?->label() ?? '-'); ?>

                                </div>
                                <div><?php echo e($approval->comment ?: 'Aucun commentaire.'); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted">Aucune étape de validation enregistrée.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="payments">
                    <div class="card">
                        <div class="card-body">
                            <?php if($actions['can_pay']): ?>
                            <form method="POST" action="<?php echo e(route('expenses.payments.store', $expense)); ?>" class="row g-2 mb-4">
                                <?php echo csrf_field(); ?>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="Montant">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="payment_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="payment_method" class="form-control" placeholder="Mode de paiement">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-dark w-100">Enregistrer</button>
                                </div>
                            </form>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Mode</th>
                                            <th>Référence</th>
                                            <th>Payé par</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $expense->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e(optional($payment->payment_date)->format('d/m/Y')); ?></td>
                                            <td><?php echo e(number_format((float) $payment->amount, 2, ',', ' ')); ?></td>
                                            <td><?php echo e($payment->payment_method ?: '-'); ?></td>
                                            <td><?php echo e($payment->reference ?: '-'); ?></td>
                                            <td><?php echo e($payment->payer?->name ?: '-'); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">Aucun paiement enregistré.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="allocations">
                    <div class="card">
                        <div class="card-body">
                            <?php if($actions['can_manage_allocation']): ?>
                            <form method="POST" action="<?php echo e(route('expenses.allocations.monthly-equal', $expense)); ?>" class="row g-2 mb-4">
                                <?php echo csrf_field(); ?>
                                <div class="col-md-4">
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary w-100">Générer ventilation mensuelle</button>
                                </div>
                            </form>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Libellé</th>
                                            <th>Date prévue</th>
                                            <th>Montant</th>
                                            <th>Payé</th>
                                            <th>Solde</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $expense->allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($allocation->allocation_number); ?></td>
                                            <td><?php echo e($allocation->label); ?></td>
                                            <td><?php echo e(optional($allocation->planned_payment_date)->format('d/m/Y')); ?></td>
                                            <td><?php echo e(number_format((float) $allocation->amount, 2, ',', ' ')); ?></td>
                                            <td><?php echo e(number_format((float) $allocation->amount_paid, 2, ',', ' ')); ?></td>
                                            <td><?php echo e(number_format((float) $allocation->balance_due, 2, ',', ' ')); ?></td>
                                            <td><?php echo e($allocation->status?->label() ?? '-'); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-muted">Aucune ventilation.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="attachments">
                    <div class="card">
                        <div class="card-body">
                            <?php if($actions['can_upload_attachment']): ?>
                            <form method="POST" action="<?php echo e(route('expenses.attachments.store', $expense)); ?>" enctype="multipart/form-data" class="row g-2 mb-4">
                                <?php echo csrf_field(); ?>
                                <div class="col-md-9">
                                    <input type="file" name="file" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-secondary w-100">Ajouter</button>
                                </div>
                            </form>
                            <?php endif; ?>

                            <?php $__empty_1 = true; $__currentLoopData = $expense->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold"><?php echo e($attachment->original_name); ?></div>
                                    <div class="text-muted small">
                                        <?php echo e($attachment->mime_type); ?> • <?php echo e(number_format($attachment->size / 1024, 1, ',', ' ')); ?> Ko
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted">Aucune pièce jointe.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="comments">
                    <div class="card">
                        <div class="card-body">
                            <?php if($actions['can_comment']): ?>
                            <form method="POST" action="<?php echo e(route('expenses.comments.store', $expense)); ?>" class="mb-4">
                                <?php echo csrf_field(); ?>
                                <textarea name="content" class="form-control mb-2" rows="3" placeholder="Ajouter un commentaire"></textarea>
                                <button class="btn btn-outline-secondary">Publier</button>
                            </form>
                            <?php endif; ?>

                            <?php $__empty_1 = true; $__currentLoopData = $expense->commentsThread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold"><?php echo e($comment->user?->name ?? 'Utilisateur'); ?></div>
                                    <div class="text-muted small"><?php echo e($comment->created_at->format('d/m/Y H:i')); ?></div>
                                </div>
                                <div class="mt-2"><?php echo e($comment->content); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted">Aucun commentaire.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="history">
                    <div class="card">
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $expense->statusLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border-start border-3 ps-3 mb-3">
                                <div class="fw-semibold"><?php echo e($log->action ?: 'Historique'); ?></div>
                                <div class="text-muted small">
                                    <?php echo e($log->created_at->format('d/m/Y H:i')); ?>

                                    <?php if($log->user): ?>
                                    • <?php echo e($log->user->name); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="mt-1">
                                    <?php echo e($log->status_axis); ?> : <?php echo e($log->old_status ?: '-'); ?> → <?php echo e($log->new_status ?: '-'); ?>

                                </div>
                                <?php if($log->comment): ?>
                                <div class="mt-1"><?php echo e($log->comment); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted">Aucun historique enregistré.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Timeline</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-start border-3 ps-3 mb-4">
                        <div class="fw-semibold"><?php echo e($event['title']); ?></div>
                        <div class="text-muted small">
                            <?php echo e(\Illuminate\Support\Carbon::parse($event['date'])->format('d/m/Y H:i')); ?>

                            <?php if(!empty($event['user'])): ?>
                            • <?php echo e($event['user']); ?>

                            <?php endif; ?>
                        </div>
                        <div class="mt-1"><?php echo e($event['description']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted">Aucun événement à afficher.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/expenses/show.blade.php ENDPATH**/ ?>