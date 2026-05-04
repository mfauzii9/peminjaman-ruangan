<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman Ruangan - LPKIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Tema Biru Muda (Light Blue / Sky Blue) yang Elegan & Bersih */
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;

            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-600: #475569;
            --neutral-700: #334155;

            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --error: #ef4444;
            --error-light: #fee2e2;
            --info: #0ea5e9;
            --info-light: #e0f2fe;

            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --border-light: #f1f5f9;
            --border-regular: #e2e8f0;
            --border-medium: #cbd5e1;

            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --text-muted: #94a3b8;

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.025);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.01);

            --radius-sm: 0.5rem;
            --radius: 0.75rem;
            --radius-md: 1rem;
            --radius-lg: 1.25rem;

            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-sans);
            background: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 0.9rem;
            -webkit-font-smoothing: antialiased;
        }

        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem 6rem 2rem;
        }

        .page-header { margin-bottom: 2.5rem; text-align: center; }
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }
        .page-subtitle { color: var(--text-tertiary); font-size: 0.95rem; }

        .booking-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            align-items: start;
        }

        .form-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition-smooth);
        }
        .form-card:hover { box-shadow: var(--shadow-md); }

        .card-header {
            padding: 1.75rem 2rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-primary);
        }
        .card-header h2 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--text-primary); }
        .card-header p { color: var(--text-tertiary); font-size: 0.85rem; }

        .card-body { padding: 2rem; }

        .card-section { margin-bottom: 2.5rem; }
        .card-section:last-child { margin-bottom: 0; }

        .section-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--text-primary);
        }
        .section-title i { color: var(--primary-500); font-size: 1.1rem; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .form-group { margin-bottom: 1rem; }
        .form-group.full-width { grid-column: span 2; }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .label-left { display: flex; align-items: center; gap: 0.35rem; }

        .required-badge {
            color: var(--primary-600);
            font-size: 0.65rem;
            font-weight: 600;
            background: var(--primary-50);
            padding: 0.15rem 0.4rem;
            border-radius: var(--radius-sm);
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 1rem; color: var(--text-muted); font-size: 0.9rem; transition: var(--transition-smooth); }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-regular);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--text-primary);
            background: var(--neutral-50);
            transition: var(--transition-smooth);
        }
        .form-control.with-icon { padding-left: 2.5rem; }

        .form-control:hover { border-color: var(--primary-300); background: var(--bg-primary); }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-400);
            background: var(--bg-primary);
            box-shadow: 0 0 0 3px var(--primary-100);
        }
        .form-control:focus + .input-icon, .form-control:focus ~ .input-icon { color: var(--primary-500); }

        .form-control.is-invalid {
            border-color: var(--error);
            background: var(--error-light);
        }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 3px var(--error-light); }

        .form-hint { margin-top: 0.4rem; font-size: 0.75rem; color: var(--text-tertiary); display: flex; align-items: center; gap: 0.3rem;}
        .form-hint.error { color: var(--error); }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            font-size: 0.85rem;
        }
        .alert i { font-size: 1.1rem; margin-top: 0.1rem; }
        .alert-content { flex: 1; }
        .alert-title { font-weight: 600; margin-bottom: 0.25rem; }
        .alert ul { margin-left: 1.25rem; color: inherit; font-size: 0.8rem; margin-top:0.25rem; }

        .alert-danger { background: var(--error-light); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--error); }
        .alert-success { background: var(--success-light); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); }
        .alert-info { background: var(--info-light); border: 1px solid rgba(14, 165, 233, 0.2); color: var(--primary-700); }
        .alert-warning { background: var(--warning-light); border: 1px solid rgba(245, 158, 11, 0.2); color: var(--warning); }

        .dropzone {
            border: 2px dashed var(--border-medium);
            border-radius: var(--radius-md);
            padding: 2.5rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition-smooth);
            background: var(--neutral-50);
            position: relative;
        }
        .dropzone:hover { border-color: var(--primary-400); background: var(--primary-50); }
        .dropzone.dragover { border-color: var(--primary-500); background: var(--primary-100); transform: scale(1.01); }

        .dropzone-icon { font-size: 2.5rem; color: var(--primary-400); margin-bottom: 1rem; transition: var(--transition-smooth); }
        .dropzone:hover .dropzone-icon { color: var(--primary-500); transform: translateY(-3px); }
        .dropzone-text { font-weight: 500; color: var(--text-primary); margin-bottom: 0.4rem; font-size: 0.95rem;}
        .dropzone-hint { color: var(--text-tertiary); font-size: 0.8rem; }

        .file-info {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-primary);
            border: 1px solid var(--primary-200);
            border-radius: var(--radius-sm);
            text-align: left;
            transition: var(--transition-smooth);
        }
        .file-info.has-file { display: flex; align-items: center; justify-content: space-between; animation: slideUp 0.3s ease forwards; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-details { display: flex; align-items: center; gap: 1rem; }
        .file-icon {
            width: 2.5rem; height: 2.5rem;
            background: var(--primary-50);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary-500);
            font-size: 1.1rem;
        }

        .file-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.15rem; color: var(--text-primary); }
        .file-meta { color: var(--text-tertiary); font-size: 0.75rem; }

        .file-remove {
            color: var(--error);
            cursor: pointer;
            padding: 0.4rem 0.75rem;
            border-radius: var(--radius-sm);
            background: var(--error-light);
            font-size: 0.8rem;
            font-weight: 500;
            border: none;
            transition: var(--transition-smooth);
            display: flex; align-items: center; gap: 0.3rem;
        }
        .file-remove:hover { background: var(--error); color: white; }
        .fileHidden { display: none; }

        /* --- SUMMARY CARD --- */
        .summary-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: sticky;
            top: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition-smooth);
        }
        .summary-card:hover { box-shadow: var(--shadow-md); }

        .summary-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
        }
        .summary-header h3 { font-size: 1.05rem; font-weight: 600; margin-bottom: 0.25rem; }
        .summary-header p { font-size: 0.8rem; opacity: 0.9; }

        .room-preview { padding: 1.5rem; border-bottom: 1px solid var(--border-light); }
        .room-name { font-weight: 700; font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--text-primary); }

        .room-meta {
            display: flex; flex-wrap: wrap; gap: 1rem;
            color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1rem;
        }
        .room-meta span { display: flex; align-items: center; gap: 0.4rem; }
        .room-meta i { color: var(--primary-500); }

        .room-facilities { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .facility-tag {
            background: var(--neutral-50);
            padding: 0.3rem 0.6rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem; color: var(--text-secondary);
            border: 1px solid var(--border-regular);
            font-weight: 500;
        }

        .time-summary { padding: 1.5rem; border-bottom: 1px solid var(--border-light); background: var(--neutral-50); }
        .time-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.85rem; font-size: 0.9rem;
        }
        .time-row:last-child { margin-bottom: 0; }
        .time-label { color: var(--text-tertiary); display: flex; align-items: center; gap: 0.4rem; }
        .time-value { font-weight: 600; color: var(--text-primary); }

        .duration-badge {
            background: var(--primary-50); color: var(--primary-700);
            padding: 0.6rem; border-radius: var(--radius-sm); text-align: center;
            font-size: 0.85rem; font-weight: 600; border: 1px solid var(--primary-100);
            margin-top: 0.5rem;
        }

        .requirements-list { padding: 1.5rem; }
        .requirement-item {
            display: flex; gap: 0.75rem; margin-bottom: 1rem;
            color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;
        }
        .requirement-item:last-child { margin-bottom: 0; }
        .requirement-icon { color: var(--primary-500); font-size: 1rem; margin-top: 0.1rem; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.85rem 1.5rem; border-radius: var(--radius-sm);
            font-weight: 600; font-size: 0.95rem; text-decoration: none;
            transition: var(--transition-smooth); border: 1px solid transparent; cursor: pointer;
            background: none; font-family: inherit; outline: none;
        }
        .btn-primary { 
            background: var(--primary-500); 
            color: white; 
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2), 0 2px 4px -2px rgba(14, 165, 233, 0.1); 
        }
        .btn-primary:hover { 
            background: var(--primary-400); /* Biru Muda Hover */
            transform: translateY(-2px); 
            box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.3), 0 4px 6px -4px rgba(56, 189, 248, 0.1); 
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-block { width: 100%; }

        .loading { position: relative; pointer-events: none; color: transparent !important; }
        .loading::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 1.25rem; height: 1.25rem; margin: -0.625rem 0 0 -0.625rem;
            border: 2px solid rgba(255,255,255, 0.3); border-top-color: white;
            border-radius: 50%; animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ✅ FLOATING PILL BUTTON (KEMBALI) - Elegan Biru Muda */
        .fab-pill {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.5rem;
            background: var(--primary-500);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: 2px solid var(--primary-200);
            border-radius: 9999px;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.25);
            transition: var(--transition-smooth);
            z-index: 999;
        }

        .fab-pill i {
            font-size: 1.1rem;
            color: #ffffff;
            transition: transform 0.3s;
        }

        .fab-pill:hover {
            background: var(--primary-400); /* Efek terang / biru muda */
            border-color: var(--primary-100);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(56, 189, 248, 0.4);
        }

        .fab-pill:hover i { transform: translateX(-3px); }

        /* Footer Attribution */
        .app-footer {
            text-align: center;
            padding: 2rem 1rem;
            margin-top: 1rem;
            border-top: 1px solid var(--border-light);
            color: var(--text-tertiary);
            font-size: 0.85rem;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .booking-layout { grid-template-columns: 1fr; }
            .summary-card { position: static; order: -1; margin-bottom: 2rem; }
        }
        @media (max-width: 768px) {
            .page-wrapper { padding: 2rem 1rem 6rem 1rem; }
            .form-grid { grid-template-columns: 1fr; gap: 1rem;}
            .form-group.full-width { grid-column: span 1; }
            .card-body { padding: 1.5rem; }
            .card-header { padding: 1.5rem; }
            
            .fab-pill {
                bottom: 1.5rem;
                right: 1.5rem;
                padding: 0.75rem 1.25rem;
                font-size: 0.85rem;
            }
        }

        /* ✅ SWEETALERT ELEGAN */
        .swal2-popup.swal-elegant { padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow-lg); }
        .swal2-title.swal-elegant-title { font-size: 1.25rem; font-weight: 700; margin: 0 0 .5rem 0; color: var(--text-primary); }
        .swal2-html-container.swal-elegant-html { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; margin: 0; }
        .swal2-confirm.swal-elegant-btn {
            font-size: 0.9rem !important; padding: 0.6rem 1.5rem !important;
            border-radius: 0.5rem !important; font-weight: 600 !important;
        }
    </style>
</head>
<body>

<div class="page-wrapper" id="pageRoot">
    <div class="page-header">
        <h1 class="page-title">Ajukan Peminjaman Ruangan</h1>
        <p class="page-subtitle">Lengkapi formulir di bawah ini untuk proses peminjaman</p>
    </div>

    <div class="booking-layout">
        <div class="form-card" id="formCard">
            <div class="card-header">
                <h2>Formulir Peminjaman</h2>
                <p>Pastikan seluruh informasi yang Anda masukkan valid.</p>
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
                                    <span class="label-left">Email</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-envelope input-icon"></i>
                                    <input type="email" name="email" class="form-control with-icon @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left">No. Telepon</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="tel" name="phone" class="form-control with-icon @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <div class="form-label">
                                    <span class="label-left">Nama Penanggung Jawab</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" name="responsible_name" class="form-control with-icon @error('responsible_name') is-invalid @enderror" value="{{ old('responsible_name') }}" placeholder="Nama lengkap penanggung jawab" required>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <div class="form-label">
                                    <span class="label-left">Nama UKM/Organisasi</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-building input-icon"></i>
                                    <input type="text" name="org_name" class="form-control with-icon @error('org_name') is-invalid @enderror" value="{{ old('org_name') }}" placeholder="Nama UKM atau organisasi" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="section-title"><i class="fa-regular fa-clock"></i> Waktu Peminjaman</div>

                        <div class="form-grid">
                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left">Mulai</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="datetime-local" name="start_time" id="startTime" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                <div class="form-hint"><i class="fa-solid fa-info-circle"></i> Mulai dari waktu saat ini</div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">
                                    <span class="label-left">Selesai</span>
                                    <span class="required-badge">Wajib</span>
                                </div>
                                <input type="datetime-local" name="end_time" id="endTime" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                <div class="form-hint"><i class="fa-solid fa-info-circle"></i> Maksimal 8 jam peminjaman</div>
                            </div>
                        </div>

                        <div id="durationPreview" style="display:none;" class="duration-badge">
                            Total Durasi: <span id="durationText"></span>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="section-title"><i class="fa-solid fa-file-pdf"></i> Dokumen Pendukung</div>

                        <div class="alert alert-info">
                            <i class="fa-solid fa-circle-info"></i>
                            <div class="alert-content"><strong>Format Surat Permohonan:</strong> Wajib PDF dengan ukuran maksimal 5MB.</div>
                        </div>

                        <div class="dropzone" id="dropzone">
                            <input type="file" name="letter" id="letterInput" class="fileHidden" accept=".pdf,application/pdf" required>
                            <div class="dropzone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <div class="dropzone-text">Tarik dan letakkan file PDF di sini</div>
                            <div class="dropzone-hint">atau klik untuk menelusuri file</div>
                        </div>

                        <div class="file-info" id="fileInfo">
                            <div class="file-details">
                                <div class="file-icon"><i class="fa-solid fa-file-pdf"></i></div>
                                <div>
                                    <div class="file-name" id="fileName"></div>
                                    <div class="file-meta" id="fileSize"></div>
                                </div>
                            </div>
                            <button type="button" class="file-remove" id="removeFile">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                        <i class="fa-regular fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

        <div class="summary-card" id="summaryCard">
            <div class="summary-header">
                <h3>Ringkasan Peminjaman</h3>
                <p>Detail ruangan yang Anda pilih</p>
            </div>

            <div class="room-preview">
                <div class="room-name">{{ $room->name ?? 'Nama Ruangan' }}</div>
                <div class="room-meta">
                    <span><i class="fa-solid fa-layer-group"></i> Lantai {{ $room->floor ?? '1' }}</span>
                    <span><i class="fa-solid fa-users"></i> {{ number_format($room->capacity ?? 0) }} Orang</span>
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
                    <span class="time-label"><i class="fa-regular fa-clock"></i> Waktu</span>
                    <span class="time-value" id="previewTime">-</span>
                </div>
                <div class="time-row">
                    <span class="time-label"><i class="fa-solid fa-stopwatch"></i> Durasi</span>
                    <span class="time-value" id="previewDuration">-</span>
                </div>
            </div>

            <div class="requirements-list">
                <div class="requirement-item">
                    <i class="fa-regular fa-circle-check requirement-icon"></i>
                    <span>Sistem akan menolak otomatis jika jadwal bentrok dengan kegiatan lain.</span>
                </div>
                <div class="requirement-item">
                    <i class="fa-regular fa-circle-check requirement-icon"></i>
                    <span>Pengajuan ini memerlukan proses persetujuan dan validasi dari pihak Admin.</span>
                </div>
            </div>
            
            <div style="padding: 0 1.5rem 1.5rem;">
                <div class="alert alert-warning" style="margin-bottom: 0;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Perhatian:</strong>
                        <p style="font-size: 0.8rem; margin-top: 0.3rem;">
                            Mohon pastikan seluruh data yang Anda masukkan sudah benar sebelum menekan tombol kirim.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="app-footer">
        &copy; {{ date('Y') }} Institut Digital Ekonomi LPKIA. by Haikal
    </footer>
</div>

<a href="{{ route('ruangan.index') }}" class="fab-pill" title="Kembali ke Daftar Ruangan">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

<script>
(function () {
    /* ==============================
       ✅ SWEETALERT ELEGAN (Warna selaras Light Blue)
    ============================== */
    function swalElegant({icon='info', title='Info', html='', confirmText='OK'}){
        return Swal.fire({
            icon, title, html,
            width: 400, padding: '1.5rem',
            confirmButtonText: confirmText, 
            confirmButtonColor: '#0ea5e9', // Sesuai var(--primary-500)
            customClass: {
                popup: 'swal-elegant', title: 'swal-elegant-title',
                htmlContainer: 'swal-elegant-html', confirmButton: 'swal-elegant-btn',
            },
        });
    }

    // Menampilkan Popup Pemberitahuan Persyaratan Dokumen Saat Halaman Dimuat
    @if(!session('error_conflict') && !session('error') && !$errors->any())
        swalElegant({
            icon: 'info',
            title: 'Pemberitahuan',
            html: 'Pastikan dokumen surat permohonan peminjaman yang akan Anda unggah <b>sudah ditandatangani oleh pihak Kemahasiswaan</b>.',
            confirmText: 'Saya Mengerti'
        });
    @endif

    // Tangkap error bentrok dari controller
    @if(session('error_conflict') || session('error'))
        swalElegant({
            icon: 'error',
            title: 'Gagal Memproses',
            html: '{{ session("error_conflict") ?? session("error") }}',
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
        const text = `${hrs > 0 ? hrs + ' Jam ' : ''}${mins > 0 ? mins + ' Menit' : ''}`.trim() || '0 Menit';

        durationText.textContent = text;
        previewDuration.textContent = text;
        durationPreview.style.display = 'block';
    }

    function handleFile(file){
        if (!file) return;
        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')){
            clearFile(); swalElegant({ icon:'error', title:'Format Tidak Sesuai', html:'Harap unggah dokumen berformat PDF.', confirmText:'Coba Lagi' });
            return;
        }
        if (file.size > 5 * 1024 * 1024){
            clearFile(); swalElegant({ icon:'error', title:'Ukuran Terlalu Besar', html:'Maksimal ukuran file adalah 5MB.', confirmText:'Coba Lagi' });
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
            await swalElegant({ icon:'warning', title:'Dokumen Kosong', html:'Surat permohonan berformat PDF wajib diunggah.', confirmText:'Mengerti' });
            return;
        }

        const start = parseLocalInput(startTimeInput.value);
        const end = parseLocalInput(endTimeInput.value);
        
        if(end < minEndFromStart(start) || end > maxEndFromStart(start) || start < nowMinStart()) {
            e.preventDefault();
            await swalElegant({ icon:'error', title:'Waktu Tidak Valid', html:'Pastikan durasi dan rentang waktu sesuai dengan ketentuan.', confirmText:'Perbaiki' });
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
    });

    // Inisialisasi tampilan awal
    applyMinForStart(); applyMinForEnd(); updatePreview();
})();
</script>

</body>
</html>