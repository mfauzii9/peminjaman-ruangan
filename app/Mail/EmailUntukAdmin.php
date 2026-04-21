<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Pastikan ini ada
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// 1. Tambahkan "implements ShouldQueue" di sini
class EmailUntukAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // 2. Tambahkan variabel ini agar bisa dibaca oleh file blade
    public $pengajuan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // 3. Tangkap data dari controller
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Persetujuan Akhir Ruangan')
            ->view('emails.untuk_admin');
    }
}