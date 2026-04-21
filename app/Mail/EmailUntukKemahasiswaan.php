<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Pastikan baris ini ada
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// Tambahkan "implements ShouldQueue" agar email dikirim lewat antrean (Background)
class EmailUntukKemahasiswaan extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Variabel publik agar otomatis terbaca di file Blade (emails.untuk_kemahasiswaan)
    public $pengajuan;

    /**
     * Membuat instance pesan baru.
     *
     * @return void
     */
    public function __construct($pengajuan)
    {
        // Menangkap data pengajuan dari Controller
        $this->pengajuan = $pengajuan;
    }

    /**
     * Membangun pesan email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ada Pengajuan Ruangan Baru')
                    ->view('emails.untuk_kemahasiswaan');
    }
}