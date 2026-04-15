@extends('layouts.app')

@php($title = 'Champs dynamiques')
@php($subtitle = 'Paramétrage des champs dynamiques du module fournisseurs')

@section('content')
<style>
    .cf-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cf-row {
        display: grid;
        grid-template-columns: 28px 1.2fr 1fr 120px 140px 110px 110px 110px auto;
        gap: 10px;
        align-items: center;
        min-height: 68px;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: #fbfdff;
    }

    .cf-drag {
        display: grid;
        place-items: center;
        cursor: grab;
        color: var(--text-soft);
    }

    .cf-row.dragging {
        opacity: .65;
        border-style: dashed;
    }

    .cf-inline {
        width: 100%;
        min-height: 38px;
        border: 1px solid transparent;
        background: transparent;
        border-radius: 10px;
        padding: 6px 10px;
        outline: none;
        transition: .2s ease;
    }

    .cf-inline:hover {
        background: #f7fbfe;
        border-color: #e3edf5;
    }

    .cf-inline:focus {
        background: #fff;
        border-color: rgba(17, 167, 168, .5);
        box-shadow: 0 0 0 4px rgba(17, 167, 168, .08);
    }

    .cf-inline.saving { background: #fffbe8; }
    .cf-inline.saved { background: #ecfdf3; border-color: #bae6c6; }
    .cf-inline.error { background: #fff1f2; border-color: #fecdd3; }

    .cf-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cf-toggle.is-on {
        border-color: #c6ead1;
        color: #1f8f4c;
        background: #effcf3;
    }

    .cf-toggle.is-off {
        border-color: #efcfcc;
        color: #c2413a;
        background: #fff5f5;
    }

    @media (max-width: 1400px) {
        .cf-row {
            grid-template-columns: 1fr;
        }

        .cf-drag {
            display: none;
        }
    }
    .cf-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cf-row {
    display: grid;
    grid-template-columns: 28px minmax(140px, 1.2fr) minmax(110px, 0.9fr) minmax(120px, 0.9fr) minmax(120px, 0.9fr) minmax(110px, 0.8fr) minmax(110px, 0.8fr) minmax(110px, 0.8fr) 96px;
    gap: 10px;
    align-items: center;
    min-height: 68px;
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: #fbfdff;
    overflow-x: auto;
    overflow-y: hidden;
}

.cf-drag {
    display: grid;
    place-items: center;
    cursor: grab;
    color: var(--text-soft);
    flex: 0 0 auto;
}

.cf-row.dragging {
    opacity: .65;
    border-style: dashed;
}

.cf-inline {
    width: 100%;
    min-width: 0;
    min-height: 38px;
    border: 1px solid transparent;
    background: transparent;
    border-radius: 10px;
    padding: 6px 10px;
    outline: none;
    transition: .2s ease;
    white-space: nowrap;
}

.cf-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: nowrap;
    min-width: 96px;
}
</style>

<div class="page-head">
    <h1 class="page-title">Champs dynamiques fournisseurs</h1>
    <p class="page-subtitle">
        Crée, modifie, désactive, supprime et réordonne les champs dynamiques utilisés par le module fournisseurs.
    </p>
</div>

<div class="grid-2">
    <div class="form-card">
        <h3 class="section-title">Créer un nouveau champ</h3>

        <form method="POST" action="{{ route('custom-fields.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label>Module</label>
                    <input class="field" type="text" name="module_code" value="suppliers" readonly>
                </div>

                <div class="form-field">
                    <label>Code technique</label>
                    <input class="field" type="text" name="field_code" placeholder="ex : iban">
                </div>

                <div class="form-field">
                    <label>Libellé</label>
                    <input class="field" type="text" name="label" placeholder="IBAN">
                </div>

                <div class="form-field">
                    <label>Type</label>
                    <select class="field-select" name="field_type">
                        <option value="text">Texte</option>
                        <option value="number">Nombre</option>
                        <option value="date">Date</option>
                        <option value="boolean">Booléen</option>
                        <option value="select">Liste</option>
                    </select>
                </div>

                <div class="form-field">
                    <label>Liste liée</label>
                    <select class="field-select" name="option_list_id">
                        <option value="">Aucune</option>
                        @foreach($optionLists as $optionList)
                            <option value="{{ $optionList->id }}">{{ $optionList->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Ordre</label>
                    <input class="field" type="number" name="sort_order" value="0">
                </div>

                <div class="form-field">
                    <label>Placeholder</label>
                    <input class="field" type="text" name="placeholder">
                </div>

                <div class="form-field">
                    <label>Valeur par défaut</label>
                    <input class="field" type="text" name="default_value">
                </div>

                <div class="form-field" style="grid-column: 1 / -1;">
                    <label>Aide</label>
                    <textarea class="field-textarea" name="help_text"></textarea>
                </div>
            </div>

            <div class="chip-list" style="margin-top: 18px;">
                <label class="chip"><input type="checkbox" name="is_required" value="1"> Obligatoire</label>
                <label class="chip"><input type="checkbox" name="is_active" value="1" checked> Actif</label>
                <label class="chip"><input type="checkbox" name="show_in_form" value="1" checked> Visible dans la fiche</label>
                <label class="chip"><input type="checkbox" name="show_in_table" value="1"> Visible dans le tableau</label>
                <label class="chip"><input type="checkbox" name="show_in_filters" value="1"> Visible dans les filtres</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Créer le champ
                </button>
            </div>
        </form>
    </div>

    <div class="form-card">
        <h3 class="section-title">Champs existants</h3>

        <div class="cf-list" id="customFieldsList">
            @forelse($customFields as $field)
                <div class="cf-row" draggable="true" data-id="{{ $field->id }}">
                    <div class="cf-drag">
                        <i data-lucide="grip-vertical"></i>
                    </div>

                    <input type="text" class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="label" value="{{ $field->label }}">
                    <input type="text" class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="field_code" value="{{ $field->field_code }}">

                    <select class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="field_type">
                        <option value="text" @selected($field->field_type === 'text')>Texte</option>
                        <option value="number" @selected($field->field_type === 'number')>Nombre</option>
                        <option value="date" @selected($field->field_type === 'date')>Date</option>
                        <option value="boolean" @selected($field->field_type === 'boolean')>Booléen</option>
                        <option value="select" @selected($field->field_type === 'select')>Liste</option>
                    </select>

                    <select class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="option_list_id">
                        <option value="">Aucune</option>
                        @foreach($optionLists as $optionList)
                            <option value="{{ $optionList->id }}" @selected($field->option_list_id === $optionList->id)>{{ $optionList->label }}</option>
                        @endforeach
                    </select>

                    <select class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="show_in_form">
                        <option value="1" @selected($field->show_in_form)>Fiche : Oui</option>
                        <option value="0" @selected(! $field->show_in_form)>Fiche : Non</option>
                    </select>

                    <select class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="show_in_table">
                        <option value="1" @selected($field->show_in_table)>Tableau : Oui</option>
                        <option value="0" @selected(! $field->show_in_table)>Tableau : Non</option>
                    </select>

                    <select class="cf-inline js-cf-inline" data-id="{{ $field->id }}" data-field="show_in_filters">
                        <option value="1" @selected($field->show_in_filters)>Filtre : Oui</option>
                        <option value="0" @selected(! $field->show_in_filters)>Filtre : Non</option>
                    </select>

                    <div class="cf-actions">
                        <button
                            type="button"
                            class="btn btn-light btn-sm cf-toggle {{ $field->is_active ? 'is-on' : 'is-off' }} js-cf-toggle"
                            data-id="{{ $field->id }}"
                            title="{{ $field->is_active ? 'Désactiver' : 'Activer' }}"
                        >
                            <i data-lucide="{{ $field->is_active ? 'toggle-right' : 'toggle-left' }}"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm js-cf-delete"
                            data-id="{{ $field->id }}"
                            title="Supprimer"
                        >
                            <i data-lucide="trash-2"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="muted">Aucun champ dynamique défini.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
const cfCsrf = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.js-cf-inline').forEach(input => {
    const save = async () => {
        input.classList.add('saving');

        const response = await fetch(`/custom-fields/${input.dataset.id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': cfCsrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                field: input.dataset.field,
                value: input.value
            })
        });

        input.classList.remove('saving');
        input.classList.add(response.ok ? 'saved' : 'error');

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            alert(payload.message || 'Erreur de mise à jour.');
        }

        setTimeout(() => {
            input.classList.remove('saved', 'error');
        }, 1200);
    };

    if (input.tagName === 'SELECT') {
        input.addEventListener('change', save);
    } else {
        input.addEventListener('blur', save);
    }
});

document.querySelectorAll('.js-cf-toggle').forEach(button => {
    button.addEventListener('click', async () => {
        const response = await fetch(`/custom-fields/${button.dataset.id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': cfCsrf,
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

document.querySelectorAll('.js-cf-delete').forEach(button => {
    button.addEventListener('click', async () => {
        if (!confirm('Supprimer ce champ dynamique ?')) return;

        const response = await fetch(`/custom-fields/${button.dataset.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': cfCsrf,
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

const customFieldsList = document.getElementById('customFieldsList');
if (customFieldsList) {
    let draggedItem = null;

    customFieldsList.querySelectorAll('.cf-row[draggable="true"]').forEach(item => {
        item.addEventListener('dragstart', () => {
            draggedItem = item;
            item.classList.add('dragging');
        });

        item.addEventListener('dragend', async () => {
            item.classList.remove('dragging');

            const orderedIds = Array.from(customFieldsList.querySelectorAll('.cf-row[draggable="true"]'))
                .map(row => Number(row.dataset.id));

            const response = await fetch(`/custom-fields/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': cfCsrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ordered_ids: orderedIds })
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                alert(payload.message || 'Erreur de réorganisation.');
            }
        });

        item.addEventListener('dragover', (e) => {
            e.preventDefault();
            const dragging = customFieldsList.querySelector('.dragging');
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
}
</script>
@endsection