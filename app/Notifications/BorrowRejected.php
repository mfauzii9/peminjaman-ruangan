<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowRejected extends Notification
{
    use Queueable;

    public $requestId;
    public $reason;

    public function __construct($requestId, $reason)
    {
        $this->requestId = (int) $requestId;
        $this->reason    = (string) $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Ruangan Ditolak')
            ->greeting('Halo!')
            ->line('Pengajuan ruangan kamu ditolak.')
            ->line('ID: #' . $this->requestId)
            ->line('Alasan: ' . $this->reason)
            ->salutation('Terima kasih.');
    }
}