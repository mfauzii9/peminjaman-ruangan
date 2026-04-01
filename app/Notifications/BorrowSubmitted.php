<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowSubmitted extends Notification
{
    use Queueable;

    /** @var int */
    public $requestId;

    /** @var string */
    public $roomLabel;

    /** @var string */
    public $startText;

    /** @var string */
    public $endText;

    public function __construct($requestId, $roomLabel, $startText, $endText)
    {
        $this->requestId  = (int) $requestId;
        $this->roomLabel  = (string) $roomLabel;
        $this->startText  = (string) $startText;
        $this->endText    = (string) $endText;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Peminjaman Ruangan Diterima')
            ->greeting('Halo!')
            ->line('Pengajuan kamu sudah diterima.')
            ->line('ID: #' . $this->requestId)
            ->line('Ruangan: ' . $this->roomLabel)
            ->line('Waktu: ' . $this->startText . ' s/d ' . $this->endText)
            ->line('Silakan tunggu verifikasi Kemahasiswaan dan Admin.')
            ->salutation('Terima kasih.');
    }
}