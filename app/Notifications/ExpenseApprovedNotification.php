<?php

namespace App\Notifications;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Expense $expense
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Dépense validée')
            ->line('Une dépense a été validée.')
            ->line('Libellé : ' . $this->expense->label)
            ->action('Ouvrir la dépense', route('expenses.show', $this->expense));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'expense_approved',
            'expense_id' => $this->expense->id,
            'label' => $this->expense->label,
            'message' => 'Une dépense a été validée.',
        ];
    }
}