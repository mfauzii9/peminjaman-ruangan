{{-- resources/views/admin/pengajuan.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Pengajuan & Ruangan</title>

  @php
    /**
     * Expected variables:
     * $stat_rooms_total, $stat_occupied, $stat_soon_free, $stat_empty,
     * $stat_history_total, $stat_pending, $stat_blocks_total, $stat_pbm_occ_total
     * $soonMinutes, $defaultView
     */
  @endphp

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      /* Modern Color Palette */
      --bg: #f8fafc;
      --card: #ffffff;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      
      --primary: #2563eb;
      --primary-hover: #1d4ed8;
      --primary-light: #eff6ff;

      --success: #10b981; --success-bg: #d1fae5; --success-text: #065f46;
      --warning: #f59e0b; --warning-bg: #fef3c7; --warning-text: #92400e;
      --danger: #ef4444;  --danger-bg: #fee2e2;  --danger-text: #991b1b;
      --info: #06b6d4;    --info-bg: #cffafe;    --info-text: #164e63;
      --violet: #8b5cf6;  --violet-bg: #ede9fe;  --violet-text: #5b21b6;

      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;

      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --ring: 0 0 0 3px rgba(37,99,235,.15);
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text-main);
      font-size: 13px;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: fadeUp .4s ease forwards; }

    /* ===== Layout ===== */
    .app { display: flex; min-height: 100vh; }
    .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
    .container {
      max-width: 1320px;
      margin: 0 auto;
      padding: 24px;
      width: 100%;
    }

    /* Sidebar is in partials.sidebar */
    .sb-collapsed .sidebar { width: 92px; }
    .sb-collapsed .sb-text, .sb-collapsed .sb-title, .sb-collapsed .sb-userinfo { display: none; }
    .sb-collapsed .sb-brand { justify-content: center; }
    .sb-collapsed .sb-toggle { margin-left: 0; }
    .sb-collapsed .sb-item { justify-content: center; }
    .sb-collapsed .sb-badge { position: absolute; margin-left: 38px; }

    /* ===== Buttons ===== */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 8px 16px;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text-main);
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      transition: all 0.2s ease;
      cursor: pointer;
      white-space: nowrap;
    }
    .btn:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn:active { transform: scale(0.98); }
    
    .btn-primary {
      background: var(--primary);
      border-color: var(--primary);
      color: #fff;
      box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
    .btn-primary:hover { background: var(--primary-hover); color: #fff; transform: translateY(-1px); }
    .btn-mini { padding: 6px 12px; font-size: 12px; border-radius: var(--radius-sm); }
    
    .btn-action {
      width: 100%;
      padding: 8px 12px;
      justify-content: center;
    }

    /* ===== Stats Cards ===== */
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
    }
    .stat {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 20px;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
    }
    .stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); border-color: #cbd5e1; }
    .statTop { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    
    .statInfo h3 { margin: 0; font-size: 26px; font-weight: 800; line-height: 1.2; color: var(--text-main); }
    .statInfo p { margin: 4px 0 0; font-size: 13px; font-weight: 600; color: var(--text-muted); }
    
    .statIcon {
      width: 44px; height: 44px;
      border-radius: 12px;
      display: grid; place-items: center;
      font-size: 18px;
    }
    .statFoot {
      margin-top: auto; padding-top: 12px; border-top: 1px dashed var(--border);
      font-size: 12px; font-weight: 500; color: var(--text-muted); display: flex; align-items: center; gap: 6px;
    }

    .icon-info { background: var(--info-bg); color: var(--info); }
    .icon-success { background: var(--success-bg); color: var(--success); }
    .icon-warning { background: var(--warning-bg); color: var(--warning); }
    .icon-primary { background: var(--primary-light); color: var(--primary); }
    .icon-violet { background: var(--violet-bg); color: var(--violet); }

    /* ===== Toolbar ===== */
    .toolbar {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 16px;
      margin-bottom: 24px;
      box-shadow: var(--shadow-sm);
      display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    }

    /* Segmented Control Tabs */
    .tabs {
      display: inline-flex;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 4px;
    }
    .tabbtn {
      border: none; background: transparent;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 600; font-size: 13px;
      color: var(--text-muted);
      cursor: pointer;
      display: inline-flex; align-items: center; gap: 8px;
      transition: all 0.2s ease;
    }
    .tabbtn:hover { color: var(--text-main); }
    .tabbtn.active { background: #fff; color: var(--primary); box-shadow: 0 1px 3px rgba(0,0,0,0.1); font-weight: 700;}

    /* Form Fields */
    .field-group {
      display: flex; align-items: center; gap: 12px;
      flex: 1; min-width: 280px;
    }
    .input-wrap {
      display: flex; align-items: center; gap: 10px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 0 12px;
      height: 40px;
      flex: 1;
      transition: all 0.2s ease;
    }
    .input-wrap:focus-within { border-color: var(--primary); box-shadow: var(--ring); }
    .input-wrap i { color: var(--text-muted); }
    .input-wrap input, .input-wrap select {
      border: none; outline: none; background: transparent;
      width: 100%; height: 100%;
      font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-main); font-weight: 500;
    }
    .input-wrap select { cursor: pointer; }

    .tool-actions { display: flex; gap: 10px; margin-left: auto; }

    /* ===== Table Card ===== */
    .table-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-sm);
      display: flex; flex-direction: column;
      overflow: hidden;
    }
    .tableHead {
      padding: 16px 24px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid var(--border);
      background: #fff;
    }
    .tableHead .title { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 15px; }
    .tableHead .title-icon {
      width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center;
      background: var(--primary-light); color: var(--primary); font-size: 14px;
    }
    .tableHead .meta { font-size: 13px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 16px; }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1000px; }
    th {
      background: #f8fafc;
      padding: 14px 20px;
      text-align: left;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }
    td {
      padding: 16px 20px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: top;
      color: var(--text-main);
    }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: #fbfdff; }

    /* Typography inside table */
    .fw-bold { font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
    .text-sub { color: var(--text-muted); font-size: 12px; line-height: 1.4; display: flex; align-items: center; gap: 6px; margin-top: 4px;}
    
    .id-pill {
      display: inline-block; padding: 4px 8px; background: #f1f5f9; border-radius: 6px;
      font-size: 12px; font-weight: 600; color: var(--text-muted);
    }

    /* Badges */
    .badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      white-space: nowrap;
    }
    .badge-warning { background: var(--warning-bg); color: var(--warning-text); border: 1px solid #fde68a; }
    .badge-success { background: var(--success-bg); color: var(--success-text); border: 1px solid #bbf7d0; }
    .badge-danger  { background: var(--danger-bg);  color: var(--danger-text);  border: 1px solid #fecaca; }
    .badge-info    { background: var(--info-bg);    color: var(--info-text);    border: 1px solid #a5f3fc; }
    .badge-primary { background: var(--primary-light); color: #1e40af; border: 1px solid #bfdbfe; }
    .badge-violet  { background: var(--violet-bg);  color: var(--violet-text);  border: 1px solid #ddd6fe; }

    /* Sticky Columns with scroll shadow */
    .sticky-left { position: sticky; left: 0; z-index: 10; background: inherit; }
    .sticky-right { position: sticky; right: 0; z-index: 10; background: inherit; }
    
    /* Ensure the table cell backgrounds are white so sticky doesn't show transparent */
    tbody td.sticky-left, tbody td.sticky-right { background: #fff; }
    tbody tr:hover td.sticky-left, tbody tr:hover td.sticky-right { background: #fbfdff; }
    
    /* Add subtle shadow to sticky columns */
    th.sticky-left::after, td.sticky-left::after {
      content: ''; position: absolute; top: 0; right: -5px; bottom: 0; width: 5px;
      background: linear-gradient(to right, rgba(0,0,0,0.05), transparent); pointer-events: none;
    }
    th.sticky-right::before, td.sticky-right::before {
      content: ''; position: absolute; top: 0; left: -5px; bottom: 0; width: 5px;
      background: linear-gradient(to left, rgba(0,0,0,0.05), transparent); pointer-events: none;
    }

    /* Pagination */
    .pager {
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 24px;
      border-top: 1px solid var(--border);
      background: #f8fafc;
      flex-wrap: wrap; gap: 16px;
    }
    .pager-info { font-size: 13px; color: var(--text-muted); font-weight: 500; }
    .pager-info b { color: var(--text-main); font-weight: 700; }
    .pager-btns { display: flex; gap: 6px; }
    .page-btn {
      min-width: 32px; height: 32px; padding: 0 10px;
      display: inline-flex; align-items: center; justify-content: center;
      border: 1px solid var(--border); border-radius: var(--radius-sm);
      background: #fff; color: var(--text-main); font-weight: 600; font-size: 13px;
      cursor: pointer; transition: all 0.2s ease;
    }
    .page-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; }
    .page-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Skeleton Loader */
    .skeleton {
      background: #e2e8f0;
      border-radius: 6px;
      height: 14px;
      position: relative;
      overflow: hidden;
    }
    .skeleton::after {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: shimmer 1.2s infinite;
    }
    @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

    /* Toast */
    .toast {
      position: fixed; right: 24px; bottom: 24px; z-index: 9999;
      background: #0f172a; color: #fff;
      padding: 16px; border-radius: var(--radius-md);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
      display: flex; align-items: flex-start; gap: 12px;
      min-width: 300px;
      transform: translateY(20px); opacity: 0; pointer-events: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }
    .toast .t-ico { font-size: 20px; }
    .toast .t-title { font-weight: 700; font-size: 14px; margin-bottom: 4px; }
    .toast .t-desc { font-weight: 500; font-size: 13px; color: #cbd5e1; }
    
    .toast.success .t-ico { color: #10b981; }
    .toast.warn .t-ico { color: #f59e0b; }
    .toast.info .t-ico { color: #38bdf8; }

    @media (max-width: 1024px) {
      .toolbar { flex-direction: column; align-items: stretch; }
      .field-group { flex-direction: column; align-items: stretch; }
      .tool-actions { width: 100%; justify-content: flex-end; }
    }
  </style>

  <script>
    document.documentElement.classList.remove('sb-collapsed');
  </script>
</head>

<body>
  <div class="app">
    @include('partials.sidebar')

    <div class="main">
      <main class="container">

        {{-- STATS SECTION --}}
        <section class="stats animate-in" style="animation-delay: 0.1s">
          <div class="stat" onclick="shortcutRooms('occupied')">
            <div class="statTop">
              <div class="statInfo">
                <h3 id="statOccupied" data-val="{{ (int)$stat_occupied }}">{{ (int)$stat_occupied }}</h3>
                <p>Ruangan Dipakai</p>
              </div>
              <div class="statIcon icon-violet"><i class="fa-solid fa-door-closed"></i></div>
            </div>
            <div class="statFoot"><i class="fa-solid fa-circle-info"></i> Sedang aktif digunakan</div>
          </div>

          <div class="stat" onclick="shortcutRooms('soon')">
            <div class="statTop">
              <div class="statInfo">
                <h3 id="statSoon" data-val="{{ (int)$stat_soon_free }}">{{ (int)$stat_soon_free }}</h3>
                <p>Segera Kosong</p>
              </div>
              <div class="statIcon icon-warning"><i class="fa-solid fa-hourglass-half"></i></div>
            </div>
            <div class="statFoot"><i class="fa-solid fa-clock"></i> Akan selesai &le; {{ (int)($soonMinutes ?? 30) }}m</div>
          </div>

          <div class="stat" onclick="shortcutRooms('empty')">
            <div class="statTop">
              <div class="statInfo">
                <h3 id="statEmpty" data-val="{{ (int)$stat_empty }}">{{ (int)$stat_empty }}</h3>
                <p>Ruangan Kosong</p>
              </div>
              <div class="statIcon icon-success"><i class="fa-solid fa-door-open"></i></div>
            </div>
            <div class="statFoot"><i class="fa-solid fa-check"></i> Siap untuk dibooking</div>
          </div>

          <div class="stat" onclick="shortcutHistory()">
            <div class="statTop">
              <div class="statInfo">
                <h3 id="statHistory" data-val="{{ (int)$stat_history_total }}">{{ (int)$stat_history_total }}</h3>
                <p>Total Riwayat</p>
              </div>
              <div class="statIcon icon-primary"><i class="fa-solid fa-layer-group"></i></div>
            </div>
            <div class="statFoot"><i class="fa-solid fa-database"></i> Mahasiswa, Cepat, PBM</div>
          </div>
        </section>

        {{-- TOOLBAR SECTION --}}
        <section class="toolbar animate-in" style="animation-delay: 0.2s">
          <div class="tabs" role="tablist">
            <button id="tabHistory" class="tabbtn" type="button" onclick="setView('history')">
              <i class="fa-solid fa-timeline"></i> Riwayat Jadwal
            </button>
            <button id="tabRooms" class="tabbtn" type="button" onclick="setView('rooms')">
              <i class="fa-solid fa-building-circle-check"></i> Status Ruangan
            </button>
          </div>

          <div class="field-group">
            <div class="input-wrap">
              <i class="fa-solid fa-filter"></i>
              <select id="sourceFilter"></select>
            </div>
            
            <div class="input-wrap">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" id="quickSearch" placeholder="Cari kegiatan, peminjam, atau ruangan...">
            </div>
          </div>

          <div class="tool-actions">
            <button class="btn btn-primary" type="button" onclick="applyFilters(1, {scroll:true})">
              <i class="fa-solid fa-search"></i> Terapkan
            </button>
            <button class="btn" type="button" onclick="resetFilters()">
              <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
          </div>
        </section>

        {{-- TABLE SECTION --}}
        <section class="table-card animate-in" style="animation-delay: 0.3s" id="unifiedSection">
          <div class="tableHead">
            <div class="title">
              <div class="title-icon"><i class="fa-solid fa-table-list"></i></div>
              <span id="tableTitle">Data Riwayat Peminjaman</span>
            </div>
            <div class="meta">
              <span id="tableMeta">—</span>
              <button class="btn btn-mini" style="margin-left: 8px" type="button" onclick="refreshNow()">
                <i class="fa-solid fa-rotate"></i>
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table id="unifiedTable">
              <thead>
                <tr>
                  <th class="sticky-left" style="width: 180px;">Pengajuan</th>
                  <th style="width: 180px;">Ruangan</th>
                  <th style="min-width: 250px;">Kegiatan / Peminjam</th>
                  <th style="min-width: 200px;">Organisasi / Sumber</th>
                  <th style="width: 180px;">Waktu</th>
                  <th style="width: 150px;">Status</th>
                  <th class="sticky-right" style="width: 120px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbody">
                <tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted)">Memuat data...</td></tr>
              </tbody>
            </table>
          </div>

          <div class="pager" id="pager" style="display:none;">
            <div class="pager-info" id="pagerInfo">—</div>
            <div class="pager-btns" id="pagerBtns"></div>
          </div>
        </section>

      </main>
    </div>
  </div>

  {{-- TOAST NOTIFICATION --}}
  <div id="toast" class="toast" role="alert">
    <div class="t-ico"><i class="fa-solid fa-circle-info"></i></div>
    <div>
      <div class="t-title" id="toastTitle">Pemberitahuan</div>
      <div class="t-desc" id="toastDesc">—</div>
    </div>
  </div>

  <script>
    const URL_STATS        = @json(route('admin.pengajuan.stats'));
    const URL_HISTORY      = @json(route('admin.pengajuan.history'));
    const URL_ROOMS        = @json(route('admin.pengajuan.rooms'));
    const URL_ROOMS_FREE   = @json(route('admin.pengajuan.rooms_free'));
    const URL_CONFIRM_BASE = @json(url('/admin/pengajuan/confirm'));
    const PER_PAGE = 6;

    let lastPending = @json((int)$stat_pending);
    let lastBlocks  = @json((int)($stat_blocks_total ?? 0));
    let lastPbmOcc  = @json((int)($stat_pbm_occ_total ?? 0));

    // Utilities
    function escapeHtml(s){
      return String(s ?? '').replace(/[&<>"']/g, (m)=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[m]));
    }

    function animateNumber(el, to){
      const from = Number(el.getAttribute('data-val') ?? el.textContent ?? 0);
      const start = performance.now();
      const dur = 400;
      function tick(t){
        const p = Math.min(1, (t - start) / dur);
        const val = Math.round(from + (to - from) * p);
        el.textContent = val;
        if (p < 1) requestAnimationFrame(tick);
        else el.setAttribute('data-val', String(to));
      }
      requestAnimationFrame(tick);
    }

    function setDot(n){
      const dotSb = document.getElementById('pendingDotSb');
      if (!dotSb) return;
      if (n > 0){
        dotSb.style.display = 'inline-flex';
        dotSb.textContent = n;
      } else {
        dotSb.style.display = 'none';
      }
    }

    function showToast(title, desc, type='success'){
      const t = document.getElementById('toast');
      const tt = document.getElementById('toastTitle');
      const td = document.getElementById('toastDesc');
      const ico = t.querySelector('.t-ico i');
      if (!t || !tt || !td) return;

      t.className = 'toast'; // reset
      t.classList.add(type);
      tt.textContent = title;
      td.textContent = desc;

      if(type==='success') ico.className = 'fa-solid fa-circle-check';
      else if(type==='warn') ico.className = 'fa-solid fa-triangle-exclamation';
      else ico.className = 'fa-solid fa-circle-info';

      requestAnimationFrame(() => t.classList.add('show'));
      clearTimeout(window.__toastTimer);
      window.__toastTimer = setTimeout(() => t.classList.remove('show'), 4000);
    }

    // Polling Stats
    async function pollStats(){
      try{
        const res = await fetch(URL_STATS, {cache:'no-store'});
        const data = await res.json();
        if (!data || !data.ok) return;

        const elOcc   = document.getElementById('statOccupied');
        const elSoon  = document.getElementById('statSoon');
        const elEmpty = document.getElementById('statEmpty');
        const elHist  = document.getElementById('statHistory');

        if (elOcc)   animateNumber(elOcc,   Number(data.occupied_rooms ?? 0));
        if (elSoon)  animateNumber(elSoon,  Number(data.soon_free_rooms ?? 0));
        if (elEmpty) animateNumber(elEmpty, Number(data.empty_rooms ?? 0));
        if (elHist)  animateNumber(elHist,  Number(data.history_total ?? 0));

        const newPending = Number(data.pending ?? 0);
        const newBlocks  = Number(data.blocks_total ?? 0);
        const newPbmOcc  = Number(data.pbm_occ_total ?? 0);

        if (newPending > lastPending) showToast('Pengajuan Baru Masuk', `Menunggu: ${newPending} (sebelumnya ${lastPending})`, 'success');
        if (newBlocks > lastBlocks)   showToast('Booking Cepat Diperbarui', `Total booking admin: ${newBlocks}`, 'warn');
        if (newPbmOcc > lastPbmOcc)   showToast('Jadwal PBM Diperbarui', `Perubahan pada jadwal perkuliahan.`, 'info');

        setDot(newPending);
        lastPending = newPending; lastBlocks = newBlocks; lastPbmOcc = newPbmOcc;
      } catch(e){}
    }

    // State Management
    const quickSearch = document.getElementById('quickSearch');
    const sourceFilter = document.getElementById('sourceFilter');
    const tabHistory = document.getElementById('tabHistory');
    const tabRooms   = document.getElementById('tabRooms');
    const tableTitle = document.getElementById('tableTitle');
    const tableMeta  = document.getElementById('tableMeta');
    const tbody      = document.getElementById('tbody');
    const pager      = document.getElementById('pager');
    const pagerInfo  = document.getElementById('pagerInfo');
    const pagerBtns  = document.getElementById('pagerBtns');

    let viewMode = 'history';
    let currentHistorySource = 'all';
    let currentRoomType = 'all';
    let lastMode = { view:'history', roomType:'all', historySource:'all', q:'', page:1 };

    function setUrl(params){
      const u = new URL(window.location.href);
      Object.keys(params).forEach(k => {
        const v = params[k];
        if (v === '' || v === null || v === undefined) u.searchParams.delete(k);
        else u.searchParams.set(k, String(v));
      });
      history.replaceState({}, '', u.toString());
    }

    function syncTabs(){
      tabHistory.classList.toggle('active', viewMode === 'history');
      tabRooms.classList.toggle('active', viewMode === 'rooms');
    }

    // Fungsi ini HANYA boleh dipanggil saat ganti tab, bukan setiap kali memfilter
    function syncSecondaryFilter(){
      if (viewMode === 'history'){
        sourceFilter.innerHTML = `
          <option value="all">Semua Sumber Data</option>
          <option value="request">Pengajuan Mahasiswa</option>
          <option value="block">Booking Cepat Admin</option>
          <option value="pbm">Jadwal PBM Reguler</option>
        `;
        sourceFilter.value = currentHistorySource;
      } else {
        sourceFilter.innerHTML = `
          <option value="all">Semua Kondisi Ruangan</option>
          <option value="occupied">Sedang Digunakan</option>
          <option value="soon">Segera Kosong (&le; 30m)</option>
          <option value="empty">Ruangan Kosong</option>
        `;
        sourceFilter.value = currentRoomType;
      }
    }

    window.setView = function setView(v){
      const newMode = (v === 'rooms') ? 'rooms' : 'history';
      if (viewMode !== newMode) {
          viewMode = newMode;
          syncTabs(); 
          syncSecondaryFilter();
      }
      applyFilters(1, {scroll:true});
    }

    function renderSkeleton(){
      const row = `<tr>
        <td class="sticky-left"><div class="skeleton" style="width:40px"></div></td>
        <td><div class="skeleton" style="width:100px"></div><div class="skeleton" style="width:70px; margin-top:6px;"></div></td>
        <td><div class="skeleton" style="width:80%"></div><div class="skeleton" style="width:50%; margin-top:6px;"></div></td>
        <td><div class="skeleton" style="width:60%"></div></td>
        <td><div class="skeleton" style="width:120px"></div><div class="skeleton" style="width:80px; margin-top:6px;"></div></td>
        <td><div class="skeleton" style="width:90px; height:24px; border-radius:12px;"></div></td>
        <td class="sticky-right" style="text-align:center;"><div class="skeleton" style="width:70px; height:32px; border-radius:8px; margin:0 auto;"></div></td>
      </tr>`;
      tbody.innerHTML = row.repeat(6);
    }

    function renderPager(meta){
      if (!meta || !meta.total_pages || meta.total_pages <= 1){
        pager.style.display = 'none'; return;
      }
      pager.style.display = 'flex';
      const page = Number(meta.page || 1), total = Number(meta.total || 0), per = Number(meta.per_page || PER_PAGE), totalPages = Number(meta.total_pages || 1);
      const start = total ? ((page - 1) * per + 1) : 0, end = Math.min(page * per, total);

      pagerInfo.innerHTML = `Menampilkan <b>${start}-${end}</b> dari <b>${total}</b> data`;

      const mkBtn = (lbl, p, dis=false, act=false, ic=null) => 
        `<button class="page-btn ${act?'active':''}" ${dis?'disabled':''} onclick="gotoPage(${p})">${ic?`<i class="fa-solid ${ic}"></i>`:lbl}</button>`;

      let html = mkBtn('', Math.max(1, page-1), page<=1, false, 'fa-chevron-left');
      const from = Math.max(1, page - 2), to = Math.min(totalPages, page + 2);
      if (from > 1) html += mkBtn('1', 1, false, page===1);
      if (from > 2) html += `<span style="color:var(--text-muted); padding:0 4px">...</span>`;
      for (let i=from; i<=to; i++) html += mkBtn(String(i), i, false, i===page);
      if (to < totalPages-1) html += `<span style="color:var(--text-muted); padding:0 4px">...</span>`;
      if (to < totalPages) html += mkBtn(String(totalPages), totalPages, false, page===totalPages);
      html += mkBtn('', Math.min(totalPages, page+1), page>=totalPages, false, 'fa-chevron-right');
      
      pagerBtns.innerHTML = html;
    }

    window.gotoPage = function gotoPage(p){ applyFilters(Math.max(1, Number(p||1)), {scroll:true}); }

    // RENDER TABLES
    function renderHistoryRows(rows){
      if (!rows || !rows.length){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted)">Tidak ada data ditemukan.</td></tr>`;
        return;
      }
      const pbmUrl = @json(route('admin.pbm.index'));

      tbody.innerHTML = rows.map(r=>{
        const kind = (r.kind || r.__kind || '').toLowerCase();
        const roomLabel = escapeHtml(r.room_name ?? '-');
        const roomFloor = escapeHtml(r.room_floor ?? '-');
        const dtCreated = escapeHtml(r.created_at || '-');

        if (kind === 'request'){
          const st = (r.status || '').toLowerCase();
          let bClass = 'badge-warning', ico = 'fa-hourglass-half';
          if (st === 'disetujui') { bClass = 'badge-success'; ico='fa-circle-check'; }
          else if (st === 'ditolak') { bClass = 'badge-danger'; ico='fa-circle-xmark'; }
          else if (st === 'selesai') { bClass = 'badge-primary'; ico='fa-flag-checkered'; }
          else if (st === 'hangus') { bClass = 'badge-danger'; ico='fa-clock-rotate-left'; }

          const detailUrl = URL_CONFIRM_BASE + '/' + encodeURIComponent(r.id);

          return `<tr>
            <td class="sticky-left"><div class="id-pill">${dtCreated}</div></td>
            <td><div class="fw-bold">${roomLabel}</div><div class="text-sub">Lantai ${roomFloor}</div></td>
            <td><div class="fw-bold">${escapeHtml(r.title || '-')}</div><div class="text-sub"><i class="fa-solid fa-envelope"></i> ${escapeHtml(r.email || '-')}</div><div class="text-sub"><i class="fa-solid fa-phone"></i> ${escapeHtml(r.phone || '-')}</div></td>
            <td><div class="fw-bold">${escapeHtml(r.org_name || '-')}</div><div class="text-sub"><span style="padding:2px 6px; background:#eff6ff; border-radius:4px; color:#1e40af">Pengajuan Mhs</span></div></td>
            <td><div class="fw-bold">${escapeHtml(r.start_time || '-')}</div><div class="text-sub">s/d ${escapeHtml(r.end_time || '-')}</div></td>
            <td><span class="badge ${bClass}"><i class="fa-solid ${ico}"></i> ${escapeHtml(r.status || '-')}</span></td>
            <td class="sticky-right" style="text-align:center;"><a class="btn btn-primary btn-action" href="${detailUrl}"><i class="fa-solid fa-arrow-right"></i> Proses</a></td>
          </tr>`;
        }

        if (kind === 'block'){
          return `<tr>
            <td class="sticky-left"><div class="id-pill">${dtCreated}</div></td>
            <td><div class="fw-bold">${roomLabel}</div><div class="text-sub">Lantai ${roomFloor}</div></td>
            <td><div class="fw-bold">${escapeHtml(r.title || 'Booking Cepat')}</div><div class="text-sub"><i class="fa-solid fa-align-left"></i> ${escapeHtml(r.note || '-')}</div></td>
            <td><div class="fw-bold">Booking Admin</div><div class="text-sub"><span style="padding:2px 6px; background:#f5f3ff; border-radius:4px; color:#5b21b6">Booking Cepat</span></div></td>
            <td><div class="fw-bold">${escapeHtml(r.start_time || '-')}</div><div class="text-sub">s/d ${escapeHtml(r.end_time || '-')}</div></td>
            <td><span class="badge badge-violet"><i class="fa-solid fa-lock"></i> Terbooking</span></td>
            <td class="sticky-right" style="text-align:center;"><span class="btn btn-action" style="opacity:.5; cursor:not-allowed; background:#f1f5f9 border:none"><i class="fa-solid fa-lock"></i> Block</span></td>
          </tr>`;
        }

        if (kind === 'pbm'){
          let isBatal = (r.status === 'ditolak'); // Controller returns 'ditolak' for cancelled PBM
          let bClass = isBatal ? 'badge-danger' : 'badge-info';
          let ico = isBatal ? 'fa-ban' : 'fa-calendar-check';
          let txt = isBatal ? 'Dibatalkan' : 'Jadwal PBM';

          return `<tr>
            <td class="sticky-left"><div class="id-pill">${dtCreated}</div></td>
            <td><div class="fw-bold">${roomLabel}</div><div class="text-sub">Lantai ${roomFloor}</div></td>
            <td><div class="fw-bold">${escapeHtml(r.title || 'PBM')}</div><div class="text-sub"><i class="fa-solid fa-graduation-cap"></i> ${escapeHtml(r.note || '-')}</div></td>
            <td><div class="fw-bold">${escapeHtml(r.org_name || 'Reguler')}</div><div class="text-sub"><span style="padding:2px 6px; background:#ecfeff; border-radius:4px; color:#0f766e">Jadwal Kuliah</span></div></td>
            <td><div class="fw-bold">${escapeHtml(r.start_time || '-')}</div><div class="text-sub">s/d ${escapeHtml(r.end_time || '-')}</div></td>
            <td><span class="badge ${bClass}"><i class="fa-solid ${ico}"></i> ${txt}</span></td>
            <td class="sticky-right" style="text-align:center;"><a class="btn btn-action" href="${pbmUrl}" style="background:#f8fafc; border-color:#cbd5e1"><i class="fa-solid fa-calendar-week"></i> Kelola</a></td>
          </tr>`;
        }
        return '';
      }).join('');
    }

    function renderRoomRows(rows){
      if (!rows || !rows.length){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted)">Tidak ada data ruangan ditemukan.</td></tr>`;
        return;
      }
      const quickBase = @json(route('admin.quick_booking'));

      tbody.innerHTML = rows.map(r=>{
        const roomLabel = escapeHtml(r.name ?? '-');
        const roomFloor = escapeHtml(r.floor ?? '-');
        const dtCreated = escapeHtml(r.created_at || '-');
        const statusRaw = (r.room_status || 'kosong').toLowerCase();
        const soonFlag  = Number(r.soon_flag || 0);

        let badge = `<span class="badge badge-success"><i class="fa-solid fa-check"></i> Tersedia</span>`;
        if (statusRaw === 'terisi' && soonFlag === 1) badge = `<span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Segera Kosong</span>`;
        else if (statusRaw === 'terisi') badge = `<span class="badge badge-danger"><i class="fa-solid fa-door-closed"></i> Sedang Dipakai</span>`;

        let srcLabel = '-';
        if ((r.src || '').toLowerCase() === 'request') srcLabel = '<span style="padding:2px 6px; background:#eff6ff; border-radius:4px; color:#1e40af; font-size:11px; font-weight:600">Mhs</span>';
        else if ((r.src || '').toLowerCase() === 'block') srcLabel = '<span style="padding:2px 6px; background:#f5f3ff; border-radius:4px; color:#5b21b6; font-size:11px; font-weight:600">Admin</span>';
        else if ((r.src || '').toLowerCase() === 'pbm') srcLabel = '<span style="padding:2px 6px; background:#ecfeff; border-radius:4px; color:#0f766e; font-size:11px; font-weight:600">PBM</span>';

        const title = escapeHtml(r.title || 'Belum ada jadwal aktif');
        const note  = escapeHtml(r.note || '-');
        const endTime = r.end_time ? escapeHtml(String(r.end_time).substring(0,16)) : '-';
        const quickUrl = quickBase + `?room_id=${encodeURIComponent(r.id)}`;

        return `<tr>
          <td class="sticky-left"><div class="id-pill">${dtCreated}</div></td>
          <td><div class="fw-bold">${roomLabel}</div><div class="text-sub">Lantai ${roomFloor} | Kapasitas: ${escapeHtml(r.capacity ?? '-')}</div></td>
          <td colspan="2">
            ${statusRaw === 'terisi' 
              ? `<div class="fw-bold">${title}</div><div class="text-sub" style="margin-bottom:6px">${note}</div>${srcLabel}`
              : `<div class="text-sub"><i class="fa-solid fa-check-circle" style="color:var(--success)"></i> Ruangan siap digunakan.</div>`
            }
          </td>
          <td><div class="fw-bold">${statusRaw === 'terisi' ? 'Selesai pada:' : '-'}</div><div class="text-sub">${statusRaw === 'terisi' ? endTime : '-'}</div></td>
          <td>${badge}</td>
          <td class="sticky-right" style="text-align:center;"><a class="btn btn-primary btn-action" href="${quickUrl}"><i class="fa-solid fa-bolt"></i> Booking</a></td>
        </tr>`;
      }).join('');
    }

    // Load Data
    async function loadHistory(page=1){
      const q = (quickSearch.value || '').trim();
      const source = sourceFilter.value || 'all';
      currentHistorySource = source;
      tableTitle.textContent = 'Riwayat Jadwal (Peminjaman & PBM)';
      tableMeta.textContent = 'Memuat data...';
      pager.style.display = 'none'; renderSkeleton();

      lastMode = { view:'history', roomType:currentRoomType, historySource:source, q, page };
      setUrl({ view:'history', source, q, page, type:null });

      try {
        const params = new URLSearchParams({ status:'all', source, date_from:'', date_to:'', q, page:String(page), per_page:String(PER_PAGE) });
        const res = await fetch(URL_HISTORY + '?' + params.toString(), {cache:'no-store'});
        const json = await res.json();
        
        if (!json || !json.ok) throw new Error();
        tableMeta.textContent = `Menampilkan Hasil${q ? ` untuk "${q}"` : ''}`;
        renderHistoryRows((json.items || []));
        renderPager(json.meta || null);
      } catch(e){
        tableMeta.textContent = 'Terjadi Kesalahan.';
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:30px; color:var(--danger)">Gagal memuat data dari server.</td></tr>`;
      }
    }

    async function loadRooms(page=1){
      const q = (quickSearch.value || '').trim();
      const type = sourceFilter.value || 'all';
      currentRoomType = type;
      tableTitle.textContent = 'Status Ruangan (Real-time)';
      tableMeta.textContent = 'Memuat data...';
      pager.style.display = 'none'; renderSkeleton();

      lastMode = { view:'rooms', roomType:type, historySource:currentHistorySource, q, page };
      setUrl({ view:'rooms', type, q, page, source:null });

      try {
        const params = new URLSearchParams({ type, q, page:String(page), per_page:String(PER_PAGE) });
        const res = await fetch(URL_ROOMS + '?' + params.toString(), {cache:'no-store'});
        const json = await res.json();

        if (!json || !json.ok) throw new Error();
        tableMeta.textContent = `Pembaruan Realtime${q ? ` • Pencarian "${q}"` : ''}`;
        renderRoomRows((json.items || []));
        renderPager(json.meta || null);
      } catch(e){
        tableMeta.textContent = 'Terjadi Kesalahan.';
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:30px; color:var(--danger)">Gagal memuat data dari server.</td></tr>`;
      }
    }

    function applyFilters(page=1, opts={scroll:true}){
      // Hapus pemanggilan syncTabs() dan syncSecondaryFilter() dari sini
      if (viewMode === 'history') loadHistory(page); else loadRooms(page);
      if (opts && opts.scroll) document.getElementById('unifiedSection')?.scrollIntoView({behavior:'smooth', block:'start'});
    }

    function resetFilters(){
      quickSearch.value = ''; 
      if (viewMode === 'history') {
          currentHistorySource = 'all';
          sourceFilter.value = 'all';
      } else {
          currentRoomType = 'all';
          sourceFilter.value = 'all';
      }
      applyFilters(1, {scroll:true});
    }
    
    function refreshNow(){ applyFilters(lastMode.page || 1, {scroll:true}); }

    window.shortcutRooms = function(type){ 
        viewMode = 'rooms'; 
        currentRoomType = type || 'all'; 
        syncTabs();
        syncSecondaryFilter();
        sourceFilter.value = currentRoomType;
        applyFilters(1, {scroll:true}); 
    }
    
    window.shortcutHistory = function(){ 
        viewMode = 'history'; 
        currentHistorySource = 'all'; 
        syncTabs();
        syncSecondaryFilter();
        sourceFilter.value = currentHistorySource;
        applyFilters(1, {scroll:true}); 
    }

    // Event Listeners
    let __qTimer = null;
    quickSearch.addEventListener('input', () => {
      clearTimeout(__qTimer);
      __qTimer = setTimeout(() => applyFilters(1, {scroll:false}), 400);
    });
    sourceFilter.addEventListener('change', () => applyFilters(1, {scroll:false}));

    // Init
    setDot(lastPending);
    setInterval(pollStats, 15000); // Polling setiap 15 detik untuk hemat server
    
    viewMode = @json(($defaultView ?? 'history')) === 'rooms' ? 'rooms' : 'history';
    syncTabs(); syncSecondaryFilter(); applyFilters(1, {scroll:false});
  </script>
</body>
</html>