<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KemaRejected extends Notification
{
    use Queueable;

    public $requestId;
    public $roomLabel;
    public $startText;
    public $endText;
    public $reason;

    public function __construct($requestId, $roomLabel, $startText, $endText, $reason)
    {
        $this->requestId = (int) $requestId;
        $this->roomLabel = (string) $roomLabel;
        $this->startText = (string) $startText;
        $this->endText   = (string) $endText;
        $this->reason    = (string) $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Ditolak oleh Kemahasiswaan')
            ->greeting('Halo!')
            ->line('Pengajuan ruangan kamu telah ditolak oleh Kemahasiswaan.')
            ->line('ID: #' . $this->requestId)
            ->line('Ruangan: ' . $this->roomLabel)
            ->line('Waktu: ' . $this->startText . ' s/d ' . $this->endText)
            ->line('Alasan: ' . $this->reason)
            ->line('Jika ada pertanyaan, silakan hubungi pihak Kemahasiswaan.')
            ->salutation('Terima kasih.');
    }
}