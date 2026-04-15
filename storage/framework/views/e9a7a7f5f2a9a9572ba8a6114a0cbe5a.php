<?php ($title = 'Options paramétrables'); ?>
<?php ($subtitle = 'Pilotage des listes de configuration du socle ERP'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .options-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .options-kpi-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-sm);
        padding: 18px 20px;
        min-height: 124px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .options-kpi-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
    }

    .options-kpi-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-soft);
        margin-bottom: 8px;
    }

    .options-kpi-value {
        font-size: 34px;
        line-height: 1;
        font-weight: 800;
        color: #17344b;
        letter-spacing: -.04em;
    }

    .options-kpi-meta {
        color: var(--text-soft);
        font-size: 12px;
    }

    .options-kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
    }

    .options-kpi-icon.red { background: #fff1f1; color: #ef4444; }
    .options-kpi-icon.orange { background: #fff7ed; color: #f59e0b; }
    .options-kpi-icon.green { background: #ecfdf3; color: #22c55e; }
    .options-kpi-icon.purple { background: #f3e8ff; color: #8b5cf6; }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .options-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: var(--shadow-sm);
        padding: 22px;
    }

    .options-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .options-card-title {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 800;
        color: #17344b;
    }

    .options-card-subtitle {
        color: var(--text-soft);
        font-size: 13px;
    }

    .options-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
        max-height: 360px;
        overflow: auto;
        padding-right: 4px;
    }

    .options-item-row {
        display: grid;
        grid-template-columns: 30px 1fr 1fr auto;
        gap: 12px;
        align-items: center;
        min-height: 66px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: #fbfdff;
    }

    .options-drag-handle {
        cursor: grab;
        color: var(--text-soft);
        display: grid;
        place-items: center;
    }

    .options-item-row.dragging {
        opacity: .65;
        border-style: dashed;
    }

    .options-inline-input {
        width: 100%;
        min-height: 40px;
        border: 1px solid transparent;
        background: transparent;
        border-radius: 10px;
        padding: 6px 10px;
        outline: none;
        transition: .2s ease;
    }

    .options-inline-input:hover {
        background: #f7fbfe;
        border-color: #e3edf5;
    }

    .options-inline-input:focus {
        background: #fff;
        border-color: rgba(17, 167, 168, .5);
        box-shadow: 0 0 0 4px rgba(17, 167, 168, .08);
    }

    .options-inline-input.saving { background: #fffbe8; }
    .options-inline-input.saved { background: #ecfdf3; border-color: #bae6c6; }
    .options-inline-input.error { background: #fff1f2; border-color: #fecdd3; }

    .options-item-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .options-toggle-btn.is-on {
        border-color: #c6ead1;
        color: #1f8f4c;
        background: #effcf3;
    }

    .options-toggle-btn.is-off {
        border-color: #efcfcc;
        color: #c2413a;
        background: #fff5f5;
    }

    .options-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .options-help {
        color: var(--text-soft);
        font-size: 12px;
        margin-top: 8px;
    }

    @media (max-width: 1280px) {
        .options-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1080px) {
        .options-grid,
        .options-form-grid {
            grid-template-columns: 1fr;
        }

        .options-item-row {
            grid-template-columns: 1fr;
            align-items: stretch;
        }

        .options-item-actions {
            justify-content: flex-start;
        }

        .options-drag-handle {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .options-kpi-grid {
            grid-template-columns: 1fr;
        }

        .options-card-head {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="page-head">
    <h1 class="page-title">Paramétrage des options</h1>
    <p class="page-subtitle">
        Toutes les listes dropdown métier du socle ERP sont centralisées dans cet écran premium.
    </p>
</div>

<div class="options-kpi-grid">
    <div class="options-kpi-card">
        <div class="options-kpi-top">
            <div>
                <div class="options-kpi-label">Listes paramétrables</div>
                <div class="options-kpi-value"><?php echo e($dashboard['lists_count'] ?? 0); ?></div>
            </div>
            <div class="options-kpi-icon red">
                <i data-lucide="list"></i>
            </div>
        </div>
        <div class="options-kpi-meta">Nombre total de listes</div>
    </div>

    <div class="options-kpi-card">
        <div class="options-kpi-top">
            <div>
                <div class="options-kpi-label">Valeurs totales</div>
                <div class="options-kpi-value"><?php echo e($dashboard['items_count'] ?? 0); ?></div>
            </div>
            <div class="options-kpi-icon orange">
                <i data-lucide="rows-3"></i>
            </div>
        </div>
        <div class="options-kpi-meta">Total des valeurs configurées</div>
    </div>

    <div class="options-kpi-card">
        <div class="options-kpi-top">
            <div>
                <div class="options-kpi-label">Valeurs actives</div>
                <div class="options-kpi-value"><?php echo e($dashboard['active_items_count'] ?? 0); ?></div>
            </div>
            <div class="options-kpi-icon green">
                <i data-lucide="badge-check"></i>
            </div>
        </div>
        <div class="options-kpi-meta">Utilisables dans les dropdowns</div>
    </div>

    <div class="options-kpi-card">
        <div class="options-kpi-top">
            <div>
                <div class="options-kpi-label">Valeurs inactives</div>
                <div class="options-kpi-value"><?php echo e($dashboard['inactive_items_count'] ?? 0); ?></div>
            </div>
            <div class="options-kpi-icon purple">
                <i data-lucide="badge-x"></i>
            </div>
        </div>
        <div class="options-kpi-meta">Masquées ou non utilisées</div>
    </div>
</div>

<div class="options-grid">
    <?php $__empty_1 = true; $__currentLoopData = $optionLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="options-card">
            <div class="options-card-head">
                <div>
                    <h3 class="options-card-title"><?php echo e($optionList->label); ?></h3>
                    <div class="options-card-subtitle">Code : <?php echo e($optionList->code); ?></div>
                </div>

                <span class="badge info"><?php echo e($optionList->items->count()); ?> valeur(s)</span>
            </div>

            <div class="options-items-list js-options-list" data-list-id="<?php echo e($optionList->id); ?>">
                <?php $__empty_2 = true; $__currentLoopData = $optionList->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <div class="options-item-row" draggable="true" data-item-id="<?php echo e($item->id); ?>">
                        <div class="options-drag-handle" title="Déplacer">
                            <i data-lucide="grip-vertical"></i>
                        </div>

                        <input
                            type="text"
                            class="options-inline-input js-option-inline"
                            data-list-id="<?php echo e($optionList->id); ?>"
                            data-item-id="<?php echo e($item->id); ?>"
                            data-field="label"
                            value="<?php echo e($item->label); ?>"
                        >

                        <input
                            type="text"
                            class="options-inline-input js-option-inline"
                            data-list-id="<?php echo e($optionList->id); ?>"
                            data-item-id="<?php echo e($item->id); ?>"
                            data-field="value"
                            value="<?php echo e($item->value); ?>"
                        >

                        <div class="options-item-actions">
                            <button
                                type="button"
                                class="btn btn-light btn-sm options-toggle-btn <?php echo e($item->is_active ? 'is-on' : 'is-off'); ?> js-option-toggle"
                                data-list-id="<?php echo e($optionList->id); ?>"
                                data-item-id="<?php echo e($item->id); ?>"
                                title="<?php echo e($item->is_active ? 'Désactiver' : 'Activer'); ?>"
                            >
                                <i data-lucide="<?php echo e($item->is_active ? 'toggle-right' : 'toggle-left'); ?>"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-danger btn-sm js-option-delete"
                                data-list-id="<?php echo e($optionList->id); ?>"
                                data-item-id="<?php echo e($item->id); ?>"
                                title="Supprimer"
                            >
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <div class="options-item-row">
                        <div class="options-item-main">
                            <div class="options-item-title">Aucune valeur</div>
                            <div class="options-item-subtitle">Cette liste n’a pas encore d’élément.</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?php echo e(route('options.items.store', $optionList)); ?>">
                <?php echo csrf_field(); ?>

                <div class="options-form-grid">
                    <div class="form-field">
                        <label>Libellé</label>
                        <input class="field" type="text" name="label" required>
                    </div>

                    <div class="form-field">
                        <label>Valeur</label>
                        <input class="field" type="text" name="value" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus"></i>
                        Ajouter
                    </button>
                </div>

                <div class="options-help">
                    Drag & drop pour réordonner, édition directe sur les champs, activation/désactivation et suppression sécurisée.
                </div>
            </form>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="options-card">
            <div class="muted">Aucune liste paramétrable trouvée.</div>
        </div>
    <?php endif; ?>
</div>

<script>
const optionsCsrf = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.js-option-inline').forEach(input => {
    const saveInline = async () => {
        input.classList.add('saving');

        const listId = input.dataset.listId;
        const itemId = input.dataset.itemId;
        const field = input.dataset.field;
        const value = input.value;

        const response = await fetch(`/options/${listId}/items/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': optionsCsrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ field, value })
        });

        input.classList.remove('saving');
        input.classList.add(response.ok ? 'saved' : 'error');

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            alert(payload.message || 'Erreur lors de la mise à jour.');
        }

        setTimeout(() => {
            input.classList.remove('saved', 'error');
        }, 1200);
    };

    input.addEventListener('blur', saveInline);
});

document.querySelectorAll('.js-option-toggle').forEach(button => {
    button.addEventListener('click', async () => {
        const listId = button.dataset.listId;
        const itemId = button.dataset.itemId;

        const response = await fetch(`/options/${listId}/items/${itemId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': optionsCsrf,
                'Accept': 'application/json'
            }
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            alert(payload.message || 'Action impossible.');
            return;
        }

        window.location.reload();
    });
});

document.querySelectorAll('.js-option-delete').forEach(button => {
    button.addEventListener('click', async () => {
        if (!confirm('Supprimer cette valeur ?')) return;

        const listId = button.dataset.listId;
        const itemId = button.dataset.itemId;

        const response = await fetch(`/options/${listId}/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': optionsCsrf,
                'Accept': 'application/json'
            }
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            alert(payload.message || 'Suppression impossible.');
            return;
        }

        window.location.reload();
    });
});

document.querySelectorAll('.js-options-list').forEach(list => {
    let draggedItem = null;

    list.querySelectorAll('.options-item-row[draggable="true"]').forEach(item => {
        item.addEventListener('dragstart', () => {
            draggedItem = item;
            item.classList.add('dragging');
        });

        item.addEventListener('dragend', async () => {
            item.classList.remove('dragging');

            const orderedIds = Array.from(list.querySelectorAll('.options-item-row[draggable="true"]'))
                .map(row => Number(row.dataset.itemId));

            const response = await fetch(`/options/${list.dataset.listId}/items/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': optionsCsrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ordered_ids: orderedIds })
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                alert(payload.message || 'Erreur lors de la réorganisation.');
            }
        });

        item.addEventListener('dragover', (e) => {
            e.preventDefault();
            const dragging = list.querySelector('.dragging');
            if (!dragging || dragging === item) return;

            const rect = item.getBoundingClientRect();
            const offset = e.clientY - rect.top;

            if (offset > rect.height / 2) {
                item.after(dragging);
            } else {
                item.before(dragging);
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dev\erp-finance\resources\views/options/index.blade.php ENDPATH**/ ?>