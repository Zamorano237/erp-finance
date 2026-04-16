<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserNotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(25);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $notificationId): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}