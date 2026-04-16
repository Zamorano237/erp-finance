

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Nouvelle dépense</h1>
        <p class="text-muted mb-0">Création d’une dépense.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('expenses.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Référence</label>
                        <input type="text" name="reference" class="form-control" value="<?php echo e(old('reference')); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Libellé</label>
                        <input type="text" name="label" class="form-control" value="<?php echo e(old('label')); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fournisseur</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Sélectionner</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>">
                                    <?php echo e($supplier->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Montant TTC</label>
                        <input type="number" step="0.01" name="amount_ttc" class="form-control" value="<?php echo e(old('amount_ttc')); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type de dépense</label>
                        <input type="text" name="expense_type" class="form-control" value="<?php echo e(old('expense_type')); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mode de règlement</label>
                        <input type="text" name="payment_mode" class="form-control" value="<?php echo e(old('payment_mode')); ?>">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/expenses/create.blade.php ENDPATH**/ ?>