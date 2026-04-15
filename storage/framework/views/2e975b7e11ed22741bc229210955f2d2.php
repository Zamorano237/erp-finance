<?php ($title = $supplier->exists ? 'Modifier un fournisseur' : 'Créer un fournisseur'); ?>
<?php ($subtitle = 'Saisie premium du référentiel fournisseurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-head">
    <h1 class="page-title"><?php echo e($supplier->exists ? 'Modification fournisseur' : 'Création fournisseur'); ?></h1>
    <p class="page-subtitle">
        Formulaire premium de gestion du référentiel fournisseurs avec structure métier claire.
    </p>
</div>

<form method="POST" action="<?php echo e($supplier->exists ? route('suppliers.update', $supplier) : route('suppliers.store')); ?>">
    <?php echo csrf_field(); ?>
    <?php if($supplier->exists): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <div class="grid-2">
        <div class="form-card">
            <h3 class="section-title">Identité fournisseur</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Code</label>
                    <input class="field" type="text" name="code" value="<?php echo e(old('code', $supplier->code)); ?>">
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="muted"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-field">
                    <label>Raison sociale</label>
                    <input class="field" type="text" name="name" value="<?php echo e(old('name', $supplier->name)); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="muted"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-field">
                    <label>Catégorie</label>
                    <select class="field-select" name="category">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['supplier_categories'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('category', $supplier->category) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Type tiers</label>
                    <select class="field-select" name="third_party_type">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['tier_types'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('third_party_type', $supplier->third_party_type) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Catégorie budgétaire</label>
                    <select class="field-select" name="budget_category">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['budget_categories'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('budget_category', $supplier->budget_category) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Auxiliaire</label>
                    <input class="field" type="text" name="auxiliary_account" value="<?php echo e(old('auxiliary_account', $supplier->auxiliary_account)); ?>">
                </div>

                <div class="form-field">
                    <label>Email</label>
                    <input class="field" type="email" name="email" value="<?php echo e(old('email', $supplier->email)); ?>">
                </div>

                <div class="form-field">
                    <label>Téléphone</label>
                    <input class="field" type="text" name="phone" value="<?php echo e(old('phone', $supplier->phone)); ?>">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3 class="section-title">Paramètres de gestion</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Fréquence</label>
                    <select class="field-select" name="frequency">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['supplier_frequencies'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('frequency', $supplier->frequency) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Mode de réception</label>
                    <select class="field-select" name="receipt_mode">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['supplier_receipt_modes'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('receipt_mode', $supplier->receipt_mode) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Mode de règlement</label>
                    <select class="field-select" name="payment_mode">
                        <option value="">Choisir</option>
                        <?php $__currentLoopData = ($configLists['supplier_payment_modes'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('payment_mode', $supplier->payment_mode) === $value): echo 'selected'; endif; ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Délai paiement (jours)</label>
                    <input class="field" type="number" name="payment_delay_days" value="<?php echo e(old('payment_delay_days', $supplier->payment_delay_days)); ?>">
                </div>

                <div class="form-field">
                    <label>Montant prévisionnel</label>
                    <input class="field" type="number" step="0.01" name="forecast_amount" value="<?php echo e(old('forecast_amount', $supplier->forecast_amount)); ?>">
                </div>

                <div class="form-field">
                    <label>TVA défaut</label>
                    <input class="field" type="number" step="0.01" name="vat_rate_default" value="<?php echo e(old('vat_rate_default', $supplier->vat_rate_default)); ?>">
                </div>

                <div class="form-field" style="grid-column:1 / -1;">
                    <label>Libellé par défaut</label>
                    <input class="field" type="text" name="default_label" value="<?php echo e(old('default_label', $supplier->default_label)); ?>">
                </div>

                <div class="form-field" style="grid-column:1 / -1;">
                    <label>Notes</label>
                    <textarea class="field-textarea" name="notes"><?php echo e(old('notes', $supplier->notes)); ?></textarea>
                </div>

                <div class="form-field">
                    <label>Actif</label>
                    <select class="field-select" name="is_active">
                        <option value="1" <?php if((string) old('is_active', $supplier->is_active ?? 1) === '1'): echo 'selected'; endif; ?>>Oui</option>
                        <option value="0" <?php if((string) old('is_active', $supplier->is_active ?? 1) === '0'): echo 'selected'; endif; ?>>Non</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('suppliers.index')); ?>" class="btn btn-light">
                    <i data-lucide="arrow-left"></i>
                    Retour
                </a>

                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    <?php echo e($supplier->exists ? 'Mettre à jour' : 'Créer le fournisseur'); ?>

                </button>
            </div>
        </div>
    </div>
<?php if(isset($customFormFields) && $customFormFields->count() > 0): ?>
    <div class="form-card" style="margin-top: 18px;">
        <h3 class="section-title">Champs dynamiques</h3>

        <div class="form-grid">
            <?php $__currentLoopData = $customFormFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-field">
                    <label><?php echo e($field->label); ?></label>

                    <?php if($field->field_type === 'number'): ?>
                        <input
                            class="field"
                            type="number"
                            step="0.01"
                            name="custom_fields[<?php echo e($field->id); ?>]"
                            value="<?php echo e(old('custom_fields.' . $field->id, '')); ?>"
                        >

                    <?php elseif($field->field_type === 'date'): ?>
                        <input
                            class="field"
                            type="date"
                            name="custom_fields[<?php echo e($field->id); ?>]"
                            value="<?php echo e(old('custom_fields.' . $field->id, '')); ?>"
                        >

                    <?php elseif($field->field_type === 'boolean'): ?>
                        <select class="field-select" name="custom_fields[<?php echo e($field->id); ?>]">
                            <option value="">Choisir</option>
                            <option value="1" <?php if(old('custom_fields.' . $field->id) == '1'): echo 'selected'; endif; ?>>Oui</option>
                            <option value="0" <?php if(old('custom_fields.' . $field->id) == '0'): echo 'selected'; endif; ?>>Non</option>
                        </select>

                    <?php elseif($field->field_type === 'select'): ?>
                        <select class="field-select" name="custom_fields[<?php echo e($field->id); ?>]">
                            <option value="">Choisir</option>
                        </select>

                    <?php else: ?>
                        <input
                            class="field"
                            type="text"
                            name="custom_fields[<?php echo e($field->id); ?>]"
                            value="<?php echo e(old('custom_fields.' . $field->id, '')); ?>"
                        >
                    <?php endif; ?>

                    <?php if(!empty($field->help_text)): ?>
                        <div class="muted"><?php echo e($field->help_text); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/suppliers/form.blade.php ENDPATH**/ ?>