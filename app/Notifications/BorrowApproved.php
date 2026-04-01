<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowApproved extends Notification
{
    use Queueable;

    public $requestId;
    public $roomLabel;
    public $startText;
    public $endText;
    public $adminNote; // nullable

    public function __construct($requestId, $roomLabel, $startText, $endText, $adminNote = null)
    {
        $this->requestId = (int) $requestId;
        $this->roomLabel = (string) $roomLabel;
        $this->startText = (string) $startText;
        $this->endText   = (string) $endText;
        $this->adminNote = $adminNote !== null ? (string) $adminNote : null;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Pengajuan Ruangan Disetujui')
            ->greeting('Halo!')
            ->line('Pengajuan ruangan kamu telah disetujui.')
            ->line('ID: #' . $this->requestId)
            ->line('Ruangan: ' . $this->roomLabel)
            ->line('Waktu: ' . $this->startText . ' s/d ' . $this->endText);

        if (!empty($this->adminNote)) {
            $mail->line('Catatan Admin: ' . $this->adminNote);
        }

        return $mail->salutation('Terima kasih.');
    }
}