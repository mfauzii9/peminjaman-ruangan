<div style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h3>Halo, {{ $pengajuan->responsible_name }}!</h3>

    @if($tipePesan == 'kode_awal')
        <p>Pengajuan peminjaman ruangan Anda telah kami terima dan sedang dalam proses verifikasi.</p>
        <p>Status pengajuan bisa anda lihat di cek status pengajuan atau di email</p>
        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; display: inline-block; border: 1px solid #d1d5db;">
            <h2 style="margin: 0; color: #2563eb; letter-spacing: 2px;">{{ $pengajuan->public_code }}</h2>
        </div>
    @else
        <p>Ada pembaruan status mengenai pengajuan peminjaman ruangan Anda:</p>
        
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; width: 180px;">Status Kemahasiswaan:</td>
                    <td style="padding: 8px 0;">
                        <span style="font-weight: bold; color: {{ $pengajuan->kema_status == 'disetujui' ? '#059669' : ($pengajuan->kema_status == 'ditolak' ? '#dc2626' : '#d97706') }};">
                            {{ strtoupper($pengajuan->kema_status) }}
                        </span>
                    </td>
                </tr>
                @if($pengajuan->kema_note)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;">Catatan Kemahasiswaan:</td>
                    <td style="padding: 8px 0; font-style: italic;">"{{ $pengajuan->kema_note }}"</td>
                </tr>
                @endif

                <tr><td colspan="2"><hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;"></td></tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Status Akhir (Admin):</td>
                    <td style="padding: 8px 0;">
                        <span style="font-weight: bold; color: {{ $pengajuan->status == 'disetujui' ? '#059669' : ($pengajuan->status == 'ditolak' ? '#dc2626' : '#d97706') }};">
                            {{ strtoupper($pengajuan->status) }}
                        </span>
                    </td>
                </tr>
                @if($pengajuan->admin_note)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;">Catatan Admin:</td>
                    <td style="padding: 8px 0; font-style: italic;">"{{ $pengajuan->admin_note }}"</td>
                </tr>
                @endif
            </table>
        </div>
    @endif

    <p style="margin-top: 25px; font-size: 0.9em; color: #666;">
        Terima kasih telah menggunakan Sistem Peminjaman Ruang.<br>
        <strong>Institut Digital Ekonomi (LPKIA)</strong>
    </p>
</div>