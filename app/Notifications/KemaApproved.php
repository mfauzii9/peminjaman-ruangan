<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KemaApproved extends Notification
{
    use Queueable;

    public $requestId;
    public $roomLabel;
    public $startText;
    public $endText;

    public function __construct($requestId, $roomLabel, $startText, $endText)
    {
        $this->requestId = (int) $requestId;
        $this->roomLabel = (string) $roomLabel;
        $this->startText = (string) $startText;
        $this->endText   = (string) $endText;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Disetujui Kemahasiswaan')
            ->greeting('Halo!')
            ->line('Pengajuan ruangan telah disetujui oleh Kemahasiswaan.')
            ->line('ID: #' . $this->requestId)
            ->line('Ruangan: ' . $this->roomLabel)
            ->line('Waktu: ' . $this->startText . ' s/d ' . $this->endText)
            ->line('Pengajuan sekarang menunggu persetujuan Admin.')
            ->salutation('Terima kasih.');
    }
}