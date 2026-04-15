<div class="card-premium p-5">
    <div class="text-xs uppercase tracking-wide text-slate-400">{{ $label }}</div>
    <div class="mt-3 text-3xl font-semibold text-white">{{ $value }}</div>
    @if(!empty($hint))
        <div class="mt-2 text-sm text-slate-400">{{ $hint }}</div>
    @endif
</div>
