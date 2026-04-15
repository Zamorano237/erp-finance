<?php ($title = 'Dashboard ERP Finance'); ?>
<?php ($subtitle = 'Pilotage direction, lecture premium et synthèse du socle ERP'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-head">
    <h1 class="page-title">Dashboard direction</h1>
    <p class="page-subtitle">
        Vue consolidée du référentiel, des dépenses, des budgets et de la trésorerie avec une interface premium orientée pilotage.
    </p>
</div>

<div class="kpi-strip">
    <div class="kpi-card">
        <h4>Fournisseurs</h4>
        <div class="value"><?php echo e($supplierCount ?? 0); ?></div>
        <div class="meta">Référentiel consolidé</div>
    </div>

    <div class="kpi-card">
        <h4>Dépenses</h4>
        <div class="value"><?php echo e($expenseCount ?? 0); ?></div>
        <div class="meta">Flux enregistrés</div>
    </div>

    <div class="kpi-card">
        <h4>Budget total</h4>
        <div class="value"><?php echo e(isset($budgetTotal) ? number_format($budgetTotal, 0, ',', ' ') : '0'); ?></div>
        <div class="meta">Montant budgété consolidé</div>
    </div>

    <div class="kpi-card">
        <h4>Solde projeté</h4>
        <div class="value"><?php echo e(isset($projectedBalance) ? number_format($projectedBalance, 0, ',', ' ') : '0'); ?></div>
        <div class="meta">Projection de trésorerie</div>
    </div>
</div>

<div class="grid-2">
    <div class="mini-panel">
        <div class="mini-panel-head">
            <h3 class="mini-panel-title">Accès rapides</h3>
            <span class="badge info">Premium UI</span>
        </div>

        <div class="grid-2">
            <a href="<?php echo e(route('suppliers.index')); ?>" class="check-tile" style="justify-content:space-between;">
                <span>Référentiel fournisseurs</span>
                <i data-lucide="arrow-right"></i>
            </a>

            <a href="<?php echo e(route('expenses.index')); ?>" class="check-tile" style="justify-content:space-between;">
                <span>Dépenses</span>
                <i data-lucide="arrow-right"></i>
            </a>

            <a href="<?php echo e(route('budgets.index')); ?>" class="check-tile" style="justify-content:space-between;">
                <span>Budgets</span>
                <i data-lucide="arrow-right"></i>
            </a>

            <a href="<?php echo e(route('treasury.index')); ?>" class="check-tile" style="justify-content:space-between;">
                <span>Trésorerie</span>
                <i data-lucide="arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="mini-panel">
        <div class="mini-panel-head">
            <h3 class="mini-panel-title">État du socle</h3>
            <span class="badge success">Actif</span>
        </div>

        <div class="list-card">
            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Référentiels paramétrables</div>
                    <div class="list-row-subtitle">Listes centralisées et maintenables</div>
                </div>
                <span class="badge success">OK</span>
            </div>

            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">UI premium V2</div>
                    <div class="list-row-subtitle">Sidebar, KPI, tables, modals premium</div>
                </div>
                <span class="badge success">Actif</span>
            </div>

            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Préférences utilisateur</div>
                    <div class="list-row-subtitle">Colonnes visibles et personnalisation</div>
                </div>
                <span class="badge success">Disponible</span>
            </div>

            <div class="list-row">
                <div class="list-row-main">
                    <div class="list-row-title">Migration ERP Laravel</div>
                    <div class="list-row-subtitle">Construction progressive du socle métier</div>
                </div>
                <span class="badge warning">En cours</span>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/dashboard/index.blade.php ENDPATH**/ ?>