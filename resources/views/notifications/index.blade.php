@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Notifications</h1>
            <p class="text-muted mb-0">Historique des événements utiles du module.</p>
        </div>
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button class="btn btn-outline-secondary">Tout marquer comme lu</button>
        </form>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">
                                {{ $notification->data['message'] ?? 'Notification' }}
                            </div>
                            <div class="text-muted small">
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if(empty($notification->read_at))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">Marquer lu</button>
                                </form>
                            @endif

                            @if(!empty($notification->data['expense_id']))
                                <a href="{{ route('expenses.show', $notification->data['expense_id']) }}" class="btn btn-sm btn-outline-secondary">
                                    Ouvrir
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-muted">Aucune notification.</div>
            @endforelse
        </div>

        <div class="card-body">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection