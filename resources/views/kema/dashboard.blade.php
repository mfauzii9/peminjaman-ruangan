{{-- resources/views/kema/dashboard.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kemahasiswaan - Dashboard</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @php
    $stats = $stats ?? ['today'=>0,'pending'=>0,'approved'=>0,'rejected'=>0];
    $recent = $recent ?? [];
    $adminName = session('admin_name') ?? session('admin_username', session('username', 'Kemahasiswaan'));

    $kemaPill = function($s){
      $s = strtolower(trim((string)$s));
      if($s === 'disetujui') return ['ok','Disetujui'];
      if($s === 'ditolak') return ['no','Ditolak'];
      return ['wait','Menunggu'];
    };

    $adminPill = function($s){
      $s = strtolower(trim((string)$s));
      if($s === 'disetujui') return ['ok','Admin: Disetujui'];
      if($s === 'ditolak') return ['no','Admin: Ditolak'];
      if($s === 'hangus') return ['no','Admin: Hangus'];
      if($s === 'selesai') return ['ok','Admin: Selesai'];
      return ['gray','Admin: Menunggu'];
    };
  @endphp

  <style>
    :root {
      --bg: #f4f7fe;
      --card: #ffffff;
      --text: #1e293b;
      --muted: #64748b;
      --border: #e2e8f0;
      --radius: 12px;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      
      /* Card Gradients based on screenshot */
      --grad-blue: linear-gradient(135deg, #3b82f6, #2563eb);
      --grad-orange: linear-gradient(135deg, #f59e0b, #d97706);
      --grad-green: linear-gradient(135deg, #10b981, #059669);
      --grad-purple: linear-gradient(135deg, #8b5cf6, #6d28d9);

      /* Soft Colors for Middle Section */
      --soft-yellow: #fef3c7;
      --soft-cyan: #cffafe;
      --soft-green: #d1fae5;
      --soft-gray: #f1f5f9;

      /* Button / Primary */
      --primary: #3b82f6;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Plus Jakarta Sans", sans-serif;
      background: var(--bg);
      color: var(--text);
      font-size: 13px;
      line-height: 1.6;
    }

    /* Layout */
    .app { display: flex; min-height: 100vh; }
    .main { flex: 1; min-width: 0; padding: 20px 24px; }

    /* Header Section */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 15px;
    }
    .header-title h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #0f172a;
    }
    .header-title p {
      margin: 4px 0 0 0;
      color: var(--muted);
      font-weight: 500;
    }
    .date-chip {
      background: #fff;
      border: 1px solid var(--border);
      padding: 8px 16px;
      border-radius: 999px;
      font-weight: 600;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: var(--shadow);
    }

    /* Top Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 24px;
    }
    .stat-card {
      border-radius: var(--radius);
      padding: 20px;
      color: #fff;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 130px;
      transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card.blue { background: var(--grad-blue); }
    .stat-card.orange { background: var(--grad-orange); }
    .stat-card.green { background: var(--grad-green); }
    .stat-card.purple { background: var(--grad-purple); }

    .stat-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }
    .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: grid;
      place-items: center;
      font-size: 18px;
    }
    .stat-badge {
      background: rgba(255, 255, 255, 0.2);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }
    .stat-value {
      font-size: 28px;
      font-weight: 800;
      margin-top: 15px;
      line-height: 1;
    }
    .stat-label {
      font-size: 12px;
      font-weight: 500;
      margin-top: 5px;
      opacity: 0.9;
    }

    /* Middle Layout (2 Columns) */
    .middle-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-bottom: 24px;
    }
    .card {
      background: var(--card);
      border-radius: var(--radius);
      padding: 20px;
      box-shadow: var(--shadow);
    }
    .card-title {
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
      color: #0f172a;
    }

    /* Status Operational / Pengajuan */
    .status-boxes {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
    }
    .status-box {
      border-radius: 8px;
      padding: 15px;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .status-box.yellow { background: var(--soft-yellow); color: #b45309; }
    .status-box.cyan { background: var(--soft-cyan); color: #0e7490; }
    .status-box.green { background: var(--soft-green); color: #047857; }
    .status-box.gray { background: var(--soft-gray); color: #475569; }
    
    .status-box h4 { font-size: 24px; font-weight: 800; margin: 0; }
    .status-box p { font-size: 11px; font-weight: 700; text-transform: uppercase; margin: 5px 0 0 0; letter-spacing: 0.5px; }

    /* Notifikasi / Info List */
    .info-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 15px;
    }
    .info-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 12px;
      border-bottom: 1px dashed var(--border);
    }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-item .label { display: flex; align-items: center; gap: 8px; font-weight: 500; }
    .info-item .count {
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
      color: #fff;
    }
    .bg-yellow { background: #f59e0b; }
    .bg-green { background: #10b981; }
    .bg-red { background: #ef4444; }

    .btn-outline {
      display: block;
      width: 100%;
      text-align: center;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid var(--primary);
      color: var(--primary);
      background: transparent;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
      cursor: pointer;
    }
    .btn-outline:hover { background: var(--primary); color: #fff; }

    /* Table Section */
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .btn-light {
      background: #f1f5f9;
      border: 1px solid var(--border);
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      text-decoration: none;
      cursor: pointer;
    }
    .btn-light:hover { background: #e2e8f0; }

    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    th {
      text-align: left;
      padding: 12px 10px;
      font-size: 11px;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 700;
      border-bottom: 2px solid var(--border);
    }
    td {
      padding: 12px 10px;
      border-bottom: 1px solid var(--border);
      font-size: 13px;
      vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    /* Pills & Tags */
    .pill {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 4px 10px; border-radius: 20px;
      font-weight: 600; font-size: 11px;
    }
    .pill.wait { background: #fef3c7; color: #b45309; }
    .pill.ok { background: #d1fae5; color: #047857; }
    .pill.no { background: #fee2e2; color: #b91c1c; }
    .pill.gray { background: #f1f5f9; color: #475569; }

    .tagSoon { font-weight: 700; color: #b45309; font-size: 11px; }
    .tagLate { font-weight: 700; color: #b91c1c; font-size: 11px; }

    .action-btn {
      color: var(--primary);
      text-decoration: none;
      font-weight: 700;
      font-size: 12px;
    }
    .action-btn:hover { text-decoration: underline; }

    /* Responsive */
    @media (max-width: 1024px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
      .middle-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .stats-grid { grid-template-columns: 1fr; }
      .status-boxes { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>

<body>
  <div class="app">
    {{-- Memanggil Sidebar Kema (Pastikan styling dan lokasinya sesuai) --}}
    @include('partials.kema.sidebar')

    <div class="main">
      
      {{-- Header Section --}}
      <div class="page-header">
        <div class="header-title">
          <h2><i class="fa-solid fa-house" style="color:var(--primary)"></i> Dashboard Utama</h2>
          <p>Halo, <b>{{ $adminName }}</b>! Selamat datang kembali di panel kontrol Kemahasiswaan.</p>
        </div>
        <div class="date-chip">
          <i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('d F Y') }}
        </div>
      </div>

      {{-- Top Stats Cards --}}
      <div class="stats-grid">
        <div class="stat-card blue">
          <div class="stat-header">
            <div class="stat-icon"><i class="fa-solid fa-inbox"></i></div>
            <div class="stat-badge">Hari Ini</div>
          </div>
          <div>
            <div class="stat-value">{{ (int)($stats['today'] ?? 0) }}</div>
            <div class="stat-label">Total Pengajuan Masuk</div>
          </div>
        </div>

        <div class="stat-card orange">
          <div class="stat-header">
            <div class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-badge">KEMA</div>
          </div>
          <div>
            <div class="stat-value">{{ (int)($stats['pending'] ?? 0) }}</div>
            <div class="stat-label">Total Menunggu</div>
          </div>
        </div>

        <div class="stat-card green">
          <div class="stat-header">
            <div class="stat-icon"><i class="fa-solid fa-check"></i></div>
            <div class="stat-badge">KEMA</div>
          </div>
          <div>
            <div class="stat-value">{{ (int)($stats['approved'] ?? 0) }}</div>
            <div class="stat-label">Total Disetujui</div>
          </div>
        </div>

        <div class="stat-card purple">
          <div class="stat-header">
            <div class="stat-icon"><i class="fa-solid fa-ban"></i></div>
            <div class="stat-badge">KEMA</div>
          </div>
          <div>
            <div class="stat-value">{{ (int)($stats['rejected'] ?? 0) }}</div>
            <div class="stat-label">Total Ditolak</div>
          </div>
        </div>
      </div>

      {{-- Middle Section --}}
      <div class="middle-grid">
        
        {{-- Status Pengajuan (Left) --}}
        <div class="card">
          <div class="card-title">
            <i class="fa-solid fa-chart-line" style="color:var(--primary)"></i> Status Operasional Pengajuan
          </div>
          <div class="status-boxes">
            <div class="status-box yellow">
              <h4>{{ (int)($stats['pending'] ?? 0) }}</h4>
              <p>Pending</p>
            </div>
            <div class="status-box cyan">
              <h4>{{ (int)($stats['approved'] ?? 0) }}</h4>
              <p>Disetujui</p>
            </div>
            <div class="status-box green">
              <h4>{{ (int)($stats['today'] ?? 0) }}</h4>
              <p>Hari Ini</p>
            </div>
            <div class="status-box gray">
              <h4>{{ (int)($stats['rejected'] ?? 0) }}</h4>
              <p>Ditolak</p>
            </div>
          </div>
        </div>

        {{-- Info / Notifikasi (Right) --}}
        <div class="card">
          <div class="card-title">
            <i class="fa-regular fa-bell" style="color:var(--primary)"></i> Ringkasan Verifikasi
          </div>
          <div class="info-list">
            <div class="info-item">
              <span class="label"><i class="fa-regular fa-circle" style="color:#f59e0b"></i> Menunggu Aksi</span>
              <span class="count bg-yellow">{{ (int)($stats['pending'] ?? 0) }}</span>
            </div>
            <div class="info-item">
              <span class="label"><i class="fa-regular fa-circle-check" style="color:#10b981"></i> Berhasil Verifikasi</span>
              <span class="count bg-green">{{ (int)($stats['approved'] ?? 0) }}</span>
            </div>
            <div class="info-item">
              <span class="label"><i class="fa-regular fa-circle-xmark" style="color:#ef4444"></i> Ditolak</span>
              <span class="count bg-red">{{ (int)($stats['rejected'] ?? 0) }}</span>
            </div>
          </div>
          <button class="btn-outline" onclick="showTips()">Lihat Log / Panduan</button>
        </div>

      </div>

      {{-- Table Section --}}
      <div class="card">
        <div class="table-header">
          <div class="card-title" style="margin:0;">
            <i class="fa-regular fa-clock" style="color:var(--primary)"></i> Antrian Terbaru
          </div>
          <a href="{{ route('kema.pengajuan.index') }}" class="btn-light">Lihat Semua</a>
        </div>
        
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th style="width:140px;">Nomor / Dibuat</th>
                <th>Pemohon</th>
                <th>Ruang</th>
                <th>Jadwal</th>
                <th>Status KEMA</th>
                <th>Status Admin</th>
                <th style="text-align:right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @if(empty($recent))
                <tr>
                  <td colspan="7" style="text-align:center; padding:30px; color:var(--muted);">
                    Belum ada data antrian pengajuan terbaru.
                  </td>
                </tr>
              @else
                @foreach($recent as $r)
                  @php
                    [$kpill, $klabel] = $kemaPill($r['kema_status'] ?? 'menunggu');
                    [$apill, $alabel] = $adminPill($r['admin_status'] ?? 'menunggu');

                    $h = $r['hoursToStart'] ?? null;
                    $scheduleTag = '';
                    if(is_numeric($h)){
                      if($h < 0) $scheduleTag = '<span class="tagLate">Lewat</span>';
                      elseif($h <= 24) $scheduleTag = '<span class="tagSoon">≤24 jam</span>';
                    }
                  @endphp
                  <tr>
                    <td>
                      <div style="font-weight:700;">#{{ $r['id'] ?? rand(1000,9999) }}</div>
                      <div style="color:var(--muted); font-size:11px;">{{ $r['created'] ?? '-' }}</div>
                    </td>
                    <td>
                      <div style="font-weight:600; color:#0f172a;">{{ $r['name'] ?? '-' }}</div>
                      <div style="color:var(--muted); font-size:11px;">{{ $r['org'] ?? '-' }}</div>
                    </td>
                    <td style="font-weight:500;">{{ $r['room'] ?? '—' }}</td>
                    <td>
                      <div style="font-weight:500;">{{ $r['schedule'] ?? '—' }}</div>
                      @if($scheduleTag)
                        <div style="margin-top:2px;">{!! $scheduleTag !!}</div>
                      @endif
                    </td>
                    <td><span class="pill {{ $kpill }}">{{ $klabel }}</span></td>
                    <td><span class="pill {{ $apill }}">{{ $alabel }}</span></td>
                    <td style="text-align:right;">
                      <a href="{{ route('kema.pengajuan.index') }}" class="action-btn">Detail</a>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script>
    function showTips(){
      Swal.fire({
        icon: 'info',
        title: 'Panduan Verifikasi',
        html: `
          <div style="text-align:left;font-size:13px;line-height:1.6;color:#334155;">
            <p style="margin-top:0">Prioritaskan pengajuan dengan status <b>Menunggu</b>.</p>
            <ol style="margin-left: 20px; padding:0;">
              <li>Periksa kelengkapan surat pemohon.</li>
              <li>Pastikan jadwal tidak bentrok (terutama yang <b>≤ 24 jam</b>).</li>
              <li>Berikan catatan jika pengajuan ditolak.</li>
            </ol>
          </div>
        `,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3b82f6'
      });
    }
  </script>
</body>
</html>