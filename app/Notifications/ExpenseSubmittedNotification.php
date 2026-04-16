<?php

namespace App\Notifications;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseSubmittedNotification extends Notification
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
            ->subject('Dépense soumise en validation')
            ->line('Une dépense vous a été soumise pour validation.')
            ->line('Libellé : ' . $this->expense->label)
            ->line('Montant TTC : ' . number_format((float) $this->expense->amount_ttc, 2, ',', ' '))
            ->action('Ouvrir la dépense', route('expenses.show', $this->expense));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'expense_submitted',
            'expense_id' => $this->expense->id,
            'label' => $this->expense->label,
            'amount_ttc' => (float) $this->expense->amount_ttc,
            'message' => 'Une dépense vous a été soumise en validation.',
        ];
    }
}