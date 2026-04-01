<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman Ruangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            --primary-200: #c7d2fe;
            --primary-300: #a5b4fc;
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;

            --neutral-50: #fafafa;
            --neutral-100: #f5f5f5;
            --neutral-200: #e5e5e5;
            --neutral-300: #d4d4d4;
            --neutral-400: #a3a3a3;
            --neutral-500: #737373;
            --neutral-600: #525252;
            --neutral-700: #404040;

            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fed7aa;
            --error: #ef4444;
            --error-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;

            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --border-light: #f0f0f0;
            --border-regular: #e4e4e7;
            --border-medium: #d4d4d8;

            --text-primary: #18181b;
            --text-secondary: #52525b;
            --text-tertiary: #71717a;
            --text-muted: #a1a1aa;

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;

            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-sans);
            background: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.5;
            font-size: 0.875rem;
        }

        .page-wrapper {
            max-width: 1280px;
            margin: 2rem auto;
            padding: 0 2rem 6rem 2rem;
        }

        .page-header { margin-bottom: 2rem; }
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            margin-bottom: 0.25rem;
        }
        .page-subtitle { color: var(--text-tertiary); font-size: 0.875rem; }

        .booking-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.5rem;
        }

        .form-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-regular);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-secondary);
        }
        .card-header h2 { font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .card-header p { color: var(--text-tertiary); font-size: 0.75rem; }

        .card-body { padding: 1.5rem; }

        .card-section { margin-bottom: 2rem; }
        .card-section:last-child { margin-bottom: 0; }

        .section-title {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title i { color: var(--primary-500); font-size: 1rem; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .form-group { margin-bottom: 1rem; }
        .form-group.full-width { grid-column: span 2; }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .label-left { display: flex; align-items: center; gap: 0.25rem; }

        .required-badge {
            color: var(--error);
            font-size: 0.625rem;
            font-weight: 600;
            background: var(--error-light);
            padding: 0.125rem 0.375rem;
            border-radius: var(--radius-sm);
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 0.75rem; color: var(--text-muted); font-size: 0.875rem; }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-regular);
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            background: var(--bg-primary);
        }
        .form-control.with-icon { padding-left: 2.25rem; }

        .form-control:hover { border-color: var(--neutral-400); }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px var(--primary-100);
        }

        .form-control.is-invalid {
            border-color: var(--error);
            background: var(--error-light);
        }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 3px var(--error-light); }

        .form-hint { margin-top: 0.25rem; font-size: 0.625rem; color: var(--text-tertiary); }
        .form-hint i { margin-right: 0.25rem; }
        .form-hint.error { color: var(--error); }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }
        .alert i { font-size: 1rem; margin-top: 0.125rem; }
        .alert-content { flex: 1; }
        .alert-title { font-weight: 600; margin-bottom: 0.25rem; }
        .alert ul { margin-left: 1.25rem; color: var(--text-secondary); font-size: 0.75rem; margin-top:0.25rem; }

        .alert-danger { background: var(--error-light); border: 1px solid var(--error); color: var(--error); }
        .alert-success { background: var(--success-light); border: 1px solid var(--success); color: var(--success); }
        .alert-info { background: var(--info-light); border: 1px solid var(--info); color: var(--info); }
        .alert-warning { background: var(--warning-light); border: 1px solid var(--warning); color: var(--warning); }

        .dropzone {
            border: 2px dashed var(--border-medium);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--bg-secondary);
            position: relative;
        }
        .dropzone:hover { border-color: var(--primary-400); background: var(--primary-50); }
        .dropzone.dragover { border-color: var(--primary-600); background: var(--primary-100); transform: scale(1.02); }

        .dropzone-icon { font-size: 2.5rem; color: var(--primary-400); margin-bottom: 0.75rem; }
        .dropzone-text { font-weight: 500; color: var(--text-primary); margin-bottom: 0.25rem; }
        .dropzone-hint { color: var(--text-tertiary); font-size: 0.75rem; }
        .dropzone-hint i { margin-right: 0.25rem; }

        .file-info {
            display: none;
            margin-top: 1rem;
            padding: 0.75rem;
            background: var(--bg-primary);
            border: 1px solid var(--border-regular);
            border-radius: var(--radius);
            text-align: left;
        }
        .file-info.has-file { display: flex; align-items: center; justify-content: space-between; }

        .file-details { display: flex; align-items: center; gap: 0.75rem; }
        .file-icon {
            width: 2rem; height: 2rem;
            background: var(--primary-50);
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary-600);
        }

        .file-name { font-weight: 500; font-size: 0.875rem; margin-bottom: 0.125rem; }
        .file-meta { color: var(--text-tertiary); font-size: 0.625rem; }

        .file-remove {
            color: var(--error);
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            background: var(--error-light);
            font-size: 0.75rem;
            border: none;
        }
        .file-remove:hover { background: var(--error); color: white; }
        .fileHidden { display: none; }

        /* --- SUMMARY CARD --- */
        .summary-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-regular);
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: sticky;
            top: 2rem;
            box-shadow: var(--shadow);
        }
        .summary-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
            color: white;
        }
        .summary-header h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .summary-header p { font-size: 0.75rem; opacity: 0.9; }

        .room-preview { padding: 1.25rem; border-bottom: 1px solid var(--border-light); }
        .room-name { font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem; }

        .room-meta {
            display: flex; flex-wrap: wrap; gap: 0.75rem;
            color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.75rem;
        }
        .room-meta span { display: flex; align-items: center; gap: 0.25rem; }

        .room-facilities { display: flex; flex-wrap: wrap; gap: 0.375rem; }
        .facility-tag {
            background: var(--neutral-100);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.625rem; color: var(--text-secondary);
            border: 1px solid var(--border-light);
        }

        .time-summary { padding: 1.25rem; border-bottom: 1px solid var(--border-light); }
        .time-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.75rem; font-size: 0.875rem;
        }
        .time-label { color: var(--text-tertiary); display: flex; align-items: center; gap: 0.375rem; }
        .time-value { font-weight: 500; color: var(--text-primary); }

        .duration-badge {
            background: var(--primary-50); color: var(--primary-700);
            padding: 0.5rem; border-radius: var(--radius); text-align: center;
            font-size: 0.75rem; font-weight: 500;
        }

        .requirements-list { padding: 1.25rem; }
        .requirement-item {
            display: flex; gap: 0.75rem; margin-bottom: 0.75rem;
            color: var(--text-secondary); font-size: 0.75rem;
        }
        .requirement-icon { color: var(--primary-500); font-size: 0.875rem; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; border-radius: var(--radius);
            font-weight: 500; font-size: 0.875rem; text-decoration: none;
            transition: all 0.2s; border: 1px solid transparent; cursor: pointer;
            background: none; font-family: inherit;
        }
        .btn-primary { background: var(--primary-600); color: white; box-shadow: var(--shadow-sm); }
        .btn-primary:hover { background: var(--primary-700); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn-primary:active { transform: translateY(0); }
        .btn-block { width: 100%; }
        .btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }

        .loading { position: relative; pointer-events: none; opacity: 0.7; }
        .loading::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 1.5rem; height: 1.5rem; margin: -0.75rem 0 0 -0.75rem;
            border: 2px solid var(--primary-200); border-top-color: var(--primary-600);
            border-radius: 50%; animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ✅ FLOATING PILL BUTTON (KEMBALI) - DESAIN BIRU SOLID */
        .fab-pill {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-600);
            color: #ffffff;
            padding: 14px 24px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 14px;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .fab-pill:hover {
            background: var(--primary-700);
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 14px 30px rgba(79, 70, 229, 0.5);
            color: #ffffff;
        }

        .fab-pill i {
            font-size: 16px;
            color: #ffffff;
            transition: transform 0.3s ease;
        }

        .fab-pill:hover i {
            transform: translateX(-4px);
            color: #ffffff;
        }

        @media (max-width: 1024px) {
            .booking-layout { grid-template-columns: 1fr; }
            .summary-card { position: static; order: -1; }
        }
        @media (max-width: 768px) {
            .page-wrapper { padding: 0 1rem 6rem 1rem; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .card-body { padding: 1rem; }
            
            .fab-pill {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
                font-size: 13px;
            }
        }

        /* ✅ SWEETALERT ELEGAN */
        .swal2-popup.swal-elegant { padding: 1.2rem; border-radius: 16px; }
        .swal2-title.swal-elegant-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 .5rem 0; }
        .swal2-html-container.swal-elegant-html { font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; }
        .swal2-confirm.swal-elegant-btn {
            font-size: 0.875rem !important; padding: 0.5rem 1.25rem !important;
            border-radius: 8px !important; font-weight: 600 !important;
        }
    </style>
</head>
<body>

<div class="page-wrapper" id="pageRoot">
    <div class="page-header">
        <h1 class="page-title">Ajukan Peminjaman Ruangan</h1>
        <p class="page-subtitle">Isi formulir berikut untuk mengajukan peminjaman ruangan</p>
    </div>

    <div class="booking-layout">
        <div class="form-card" id="formCard">
            <div class="card-header">
                <h2>Formulir Peminjaman</h2>
                <p>Pastikan semua data yang diisi benar</p>
            </div>

            <div class="card-body">
                {{-- Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div class="alert-content">
                            <div class="alert-title">Terdapat kesalahan pada formulir</div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Form Input --}}
                <form method="POST" action="{{ route('pinjam.store', $room->id) }}" enctype="multipart/form-data" id="borrowForm">
                    @csrf

                    <div class="card-section">
                        <div class="section-title"><i class="fa-solid fa-address-card"></i> Informasi Kontak</div>

                        <div class="form-grid">
                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-regular fa-envelope"></i> Email</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-envelope input-icon"></i>
                                    <input type="email" name="email" class="form-control with-icon @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-solid fa-phone"></i> No. Telepon</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="tel" name="phone" class="form-control with-icon @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-solid fa-user"></i> Nama Penanggung Jawab</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="text" name="responsible_name" class="form-control @error('responsible_name') is-invalid @enderror" value="{{ old('responsible_name') }}" placeholder="Nama lengkap penanggung jawab" required>
                            </div>

                            <div class="form-group full-width">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-solid fa-building"></i> Nama UKM/Organisasi</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="text" name="org_name" class="form-control @error('org_name') is-invalid @enderror" value="{{ old('org_name') }}" placeholder="Nama UKM atau organisasi" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="section-title"><i class="fa-regular fa-clock"></i> Waktu Peminjaman</div>

                        <div class="form-grid">
                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-regular fa-calendar"></i> Mulai</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="datetime-local" name="start_time" id="startTime" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                <div class="form-hint"><i class="fa-regular fa-clock"></i> Mulai dari waktu saat ini</div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left"><i class="fa-regular fa-calendar-check"></i> Selesai</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="datetime-local" name="end_time" id="endTime" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                <div class="form-hint"><i class="fa-regular fa-hourglass"></i> Maksimal 8 jam</div>
                            </div>
                        </div>

                        <div id="durationPreview" style="display:none;" class="duration-badge">
                            Durasi: <span id="durationText"></span>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="section-title"><i class="fa-solid fa-file-pdf"></i> Upload Surat Permohonan</div>

                        <div class="alert alert-info" style="margin-bottom: 1rem;">
                            <i class="fa-solid fa-circle-info"></i>
                            <div class="alert-content"><strong>Format surat:</strong> Wajib PDF (Maks. 5MB)</div>
                        </div>

                        <div class="dropzone" id="dropzone">
                            <input type="file" name="letter" id="letterInput" class="fileHidden" accept=".pdf,application/pdf" required>
                            <div class="dropzone-icon"><i class="fa-solid fa-cloud-upload-alt"></i></div>
                            <div class="dropzone-text">Seret file ke sini atau klik untuk upload</div>
                            <div class="dropzone-hint"><i class="fa-regular fa-file-pdf"></i> PDF saja (Max 5MB)</div>
                        </div>

                        <div class="file-info" id="fileInfo">
                            <div class="file-details">
                                <div class="file-icon"><i class="fa-regular fa-file-pdf"></i></div>
                                <div>
                                    <div class="file-name" id="fileName"></div>
                                    <div class="file-meta" id="fileSize"></div>
                                </div>
                            </div>
                            <button type="button" class="file-remove" id="removeFile">
                                <i class="fa-solid fa-times"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
                        <i class="fa-regular fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </form>
            </div>
        </div>

        <div class="summary-card" id="summaryCard">
            <div class="summary-header">
                <h3>Ringkasan Peminjaman</h3>
                <p>Detail ruangan yang dipilih</p>
            </div>

            <div class="room-preview">
                <div class="room-name">{{ $room->name ?? 'Nama Ruangan' }}</div>
                <div class="room-meta">
                    <span><i class="fa-solid fa-layer-group"></i> Lantai {{ $room->floor ?? '1' }}</span>
                    <span><i class="fa-solid fa-users"></i> {{ number_format($room->capacity ?? 0) }} orang</span>
                    <span><i class="fa-regular fa-building"></i> {{ $room->building ?? 'Gedung Utama' }}</span>
                </div>

                @if(!empty($room->facilities))
                <div class="room-facilities">
                    @foreach(explode(',', $room->facilities) as $facility)
                        <span class="facility-tag">{{ trim($facility) }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="time-summary">
                <div class="time-row">
                    <span class="time-label"><i class="fa-regular fa-calendar"></i> Tanggal</span>
                    <span class="time-value" id="previewDate">-</span>
                </div>
                <div class="time-row">
                    <span class="time-label"><i class="fa-regular fa-clock"></i> Jam</span>
                    <span class="time-value" id="previewTime">-</span>
                </div>
                <div class="time-row">
                    <span class="time-label"><i class="fa-regular fa-hourglass"></i> Durasi</span>
                    <span class="time-value" id="previewDuration">-</span>
                </div>
            </div>

            <div class="requirements-list">
                <div class="requirement-item">
                    <i class="fa-regular fa-circle-check requirement-icon"></i>
                    <span>Sistem akan menolak otomatis jika jadwal bentrok dengan acara lain.</span>
                </div>
                <div class="requirement-item">
                    <i class="fa-regular fa-circle-check requirement-icon"></i>
                    <span>Tunggu persetujuan Admin setelah pengajuan dikirim.</span>
                </div>
            </div>
            
            <div style="padding: 0 1.25rem 1.25rem;">
                <div class="alert alert-warning" style="margin-bottom: 0;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Perhatian:</strong>
                        <p style="font-size: 0.625rem; margin-top: 0.25rem;">
                            Pastikan data yang diisi benar. Perubahan data tidak dapat dilakukan setelah pengajuan dikirim.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('ruangan.index') }}" class="fab-pill" title="Kembali ke Daftar Ruangan">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

<script>
(function () {
    /* ==============================
       ✅ SWEETALERT ELEGAN
    ============================== */
    function swalElegant({icon='info', title='Info', html='', confirmText='OK'}){
        return Swal.fire({
            icon, title, html,
            width: 380, padding: '1.25rem',
            confirmButtonText: confirmText, confirmButtonColor: '#4f46e5',
            customClass: {
                popup: 'swal-elegant', title: 'swal-elegant-title',
                htmlContainer: 'swal-elegant-html', confirmButton: 'swal-elegant-btn',
            },
        });
    }

    // TANGKAP ERROR BENTROK DARI CONTROLLER LALU MUNCULKAN POPUP
    @if(session('error_conflict'))
        swalElegant({
            icon: 'error',
            title: 'Jadwal Bentrok!',
            // Teks disesuaikan dengan permintaan
            html: 'Maaf jadwal bentrok, Anda bisa lihat dulu di daftar jadwal di beranda Home.',
            confirmText: 'Tutup'
        });
    @endif

    /* ==============================
       LOGIC WAKTU & FILE UPLOAD
    ============================== */
    const MIN_ADVANCE_MINUTES = 0; 
    const MIN_DURATION_MINUTES = 30;
    const MAX_DURATION_HOURS = 8;

    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const durationPreview = document.getElementById('durationPreview');
    const durationText = document.getElementById('durationText');

    const previewDate = document.getElementById('previewDate');
    const previewTime = document.getElementById('previewTime');
    const previewDuration = document.getElementById('previewDuration');

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('letterInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFile');

    const form = document.getElementById('borrowForm');
    const submitBtn = document.getElementById('submitBtn');

    function pad2(n){ return String(n).padStart(2,'0'); }
    function ceilToMinutes(date, step=5){
        const ms = step * 60 * 1000;
        return new Date(Math.ceil(date.getTime() / ms) * ms);
    }
    function toLocalInputValue(date){
        return `${date.getFullYear()}-${pad2(date.getMonth()+1)}-${pad2(date.getDate())}T${pad2(date.getHours())}:${pad2(date.getMinutes())}`;
    }
    function parseLocalInput(value){ return value ? new Date(value) : null; }
    
    function nowMinStart(){
        return ceilToMinutes(new Date(new Date().getTime() + MIN_ADVANCE_MINUTES * 60000), 5);
    }
    function minEndFromStart(start){
        return ceilToMinutes(new Date(start.getTime() + MIN_DURATION_MINUTES * 60000), 5);
    }
    function maxEndFromStart(start){
        return new Date(start.getTime() + MAX_DURATION_HOURS * 60 * 60000);
    }

    function applyMinForStart(){
        const minStart = nowMinStart();
        startTimeInput.min = toLocalInputValue(minStart);
        const currentStart = parseLocalInput(startTimeInput.value);
        if (!currentStart || currentStart < minStart) startTimeInput.value = toLocalInputValue(minStart);
    }

    function applyMinForEnd(){
        const start = parseLocalInput(startTimeInput.value);
        if (!start) return;

        const minEnd = minEndFromStart(start);
        const maxEnd = maxEndFromStart(start);
        
        endTimeInput.min = toLocalInputValue(minEnd);
        endTimeInput.max = toLocalInputValue(maxEnd);

        const currentEnd = parseLocalInput(endTimeInput.value);
        if (!currentEnd || currentEnd < minEnd) endTimeInput.value = toLocalInputValue(minEnd);
        if (parseLocalInput(endTimeInput.value) > maxEnd) endTimeInput.value = toLocalInputValue(maxEnd);
    }

    function updatePreview(){
        const start = parseLocalInput(startTimeInput.value);
        const end = parseLocalInput(endTimeInput.value);
        if (!start || !end) return;

        previewDate.textContent = start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        previewTime.textContent = `${start.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })} - ${end.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })}`;

        const diffMins = Math.max(0, Math.floor((end - start) / 60000));
        const hrs = Math.floor(diffMins / 60);
        const mins = diffMins % 60;
        const text = `${hrs > 0 ? hrs + ' jam ' : ''}${mins} menit`.trim();

        durationText.textContent = text;
        previewDuration.textContent = text;
        durationPreview.style.display = 'block';
    }

    function handleFile(file){
        if (!file) return;
        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')){
            clearFile(); swalElegant({ icon:'error', title:'Format Salah', html:'Wajib berformat PDF.', confirmText:'Pilih Ulang' });
            return;
        }
        if (file.size > 5 * 1024 * 1024){
            clearFile(); swalElegant({ icon:'error', title:'Ukuran Besar', html:'Maksimal 5MB.', confirmText:'Pilih Ulang' });
            return;
        }
        fileInfo.classList.add('has-file');
        fileName.textContent = file.name;
        fileSize.textContent = file.size < 1048576 ? (file.size/1024).toFixed(1)+' KB' : (file.size/1048576).toFixed(1)+' MB';
    }

    function clearFile(){
        fileInput.value = '';
        fileInfo.classList.remove('has-file');
    }

    if (dropzone) {
        dropzone.addEventListener('click', () => fileInput.click());
        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault(); dropzone.classList.remove('dragover');
            if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; handleFile(fileInput.files[0]); }
        });
        fileInput.addEventListener('change', function() { if (this.files.length) handleFile(this.files[0]); });
        removeFileBtn.addEventListener('click', clearFile);
    }

    startTimeInput.addEventListener('change', () => { applyMinForStart(); applyMinForEnd(); updatePreview(); });
    endTimeInput.addEventListener('change', () => { applyMinForEnd(); updatePreview(); });

    form.addEventListener('submit', async function(e){
        const f = fileInput.files[0];
        if (!f || (f.type !== 'application/pdf' && !f.name.toLowerCase().endsWith('.pdf'))){
            e.preventDefault();
            await swalElegant({ icon:'error', title:'Surat Permohonan', html:'Surat PDF wajib diunggah.', confirmText:'OK' });
            return;
        }

        const start = parseLocalInput(startTimeInput.value);
        const end = parseLocalInput(endTimeInput.value);
        
        if(end < minEndFromStart(start) || end > maxEndFromStart(start) || start < nowMinStart()) {
            e.preventDefault();
            await swalElegant({ icon:'error', title:'Waktu Tidak Valid', html:'Pastikan waktu sesuai dengan aturan yang berlaku.', confirmText:'OK' });
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengecek Jadwal...';
    });

    applyMinForStart(); applyMinForEnd(); updatePreview();
})();
</script>

</body>
</html>