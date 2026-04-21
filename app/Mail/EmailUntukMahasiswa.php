<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Pastikan ini ada untuk antrean
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUntukMahasiswa extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Variabel publik agar otomatis tersedia di file Blade
    public $pengajuan;
    public $tipePesan; 

    /**
     * Create a new message instance.
     * * @param $pengajuan
     * @param string $tipePesan (kode_awal atau status_akhir)
     */
    public function __construct($pengajuan, $tipePesan = 'kode_awal')
    {
        $this->pengajuan = $pengajuan;
        $this->tipePesan = $tipePesan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Menentukan subjek berdasarkan tipe pesan
        $subject = $this->tipePesan == 'kode_awal' 
            ? 'Kode Peminjaman Ruangan Anda' 
            : 'Update: Status Peminjaman Ruangan Anda';

        return $this->subject($subject)
                    ->view('emails.untuk_mahasiswa');
    }
}