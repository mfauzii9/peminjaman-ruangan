{{-- resources/views/admin/dashboard.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Dashboard</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --text:#0b1220;
      --muted:#64748b;
      --border:#e6eaf2;

      --primary:#2563eb;
      --primary2:#1d4ed8;

      --radius:16px;
      --radius2:14px;

      --shadow: 0 10px 30px rgba(2, 6, 23, .06);
      --shadowHover: 0 18px 50px rgba(2, 6, 23, .10);

      --success:#16a34a;
      --warning:#f59e0b;
      --danger:#ef4444;
      --violet:#7c3aed;
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      font-size: 12.8px;
      line-height: 1.6;
    }

    /* ===== Layout ===== */
    .app{ display:flex; min-height:100vh; }
    .main{ flex:1; min-width:0; }

    /* Sidebar is in partials.sidebar - we only support collapse class */
    .sb-collapsed .sidebar{ width:92px; }
    .sb-collapsed .sb-text,.sb-collapsed .sb-title,.sb-collapsed .sb-userinfo{ display:none; }
    .sb-collapsed .sb-brand{ justify-content:center; }
    .sb-collapsed .sb-toggle{ margin-left:0; }
    .sb-collapsed .sb-item{ justify-content:center; }
    .sb-collapsed .sb-badge{ position:absolute; margin-left:38px; }

    /* ===== Topbar ===== */
    .topbar{
      position:sticky; top:0; z-index:50;
      background: rgba(246,247,251,.85);
      backdrop-filter: blur(12px);
      border-bottom:1px solid var(--border);
      padding: 12px 16px;
      display:flex; align-items:center; justify-content:space-between; gap:12px;
    }

    .titleWrap{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .pageTitle{
      display:inline-flex; align-items:center; gap:10px;
      padding: 8px 12px;
      border-radius:999px;
      background: rgba(37,99,235,.10);
      border: 1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
      font-weight:800;
      font-size:12px;
      white-space:nowrap;
    }
    .subtitle{ color:var(--muted); font-weight:600; }

    .actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

    /* ===== Buttons ===== */
    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      padding: 10px 12px;
      border-radius: var(--radius2);
      border:1px solid var(--border);
      background:#fff;
      color: var(--text);
      text-decoration:none;
      font-weight:800;
      font-size:12px;
      transition: .15s ease;
      cursor:pointer;
      white-space:nowrap;
      box-shadow: none;
    }
    .btn:hover{ transform: translateY(-1px); background:#f8fafc; }
    .btn-primary{
      background: linear-gradient(135deg, var(--primary), var(--primary2));
      border-color: transparent;
      color:#fff;
      box-shadow: 0 14px 34px rgba(37,99,235,.20);
    }
    .btn-mini{ padding: 8px 10px; border-radius: 12px; }

    /* ===== Container ===== */
    .container{
      max-width: 1240px;
      margin: 16px auto 28px;
      padding: 0 16px;
    }

    /* ===== Stats Cards ===== */
    .stats{
      display:grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 12px;
      margin-top: 12px;
    }
    .stat{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 12px 12px;
      box-shadow: var(--shadow);
      transition: .15s ease;
      cursor:pointer;
      position:relative;
      overflow:hidden;
      min-height: 82px;
    }
    .stat:hover{ transform: translateY(-2px); box-shadow: var(--shadowHover); }

    .statTop{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
    }
    .statLabel{
      color: var(--muted);
      font-weight: 700;
      font-size: 11.6px;
      display:flex; align-items:center; gap:8px;
    }
    .statValue{
      margin-top: 6px;
      font-size: 20px;
      font-weight: 850;
      letter-spacing: .2px;
    }
    .statHint{
      margin-top: 6px;
      color: var(--muted);
      font-weight: 600;
      font-size: 11.4px;
    }

    .ico{
      width: 34px; height: 34px;
      border-radius: 12px;
      display:grid; place-items:center;
      border: 1px solid rgba(37,99,235,.18);
      background: rgba(37,99,235,.08);
      color:#1d4ed8;
      flex-shrink:0;
    }
    .ico.success{ border-color: rgba(22,163,74,.18); background: rgba(22,163,74,.10); color:#166534; }
    .ico.warning{ border-color: rgba(245,158,11,.22); background: rgba(245,158,11,.12); color:#92400e; }
    .ico.violet{ border-color: rgba(124,58,237,.20); background: rgba(124,58,237,.10); color:#5b21b6; }
    .ico.danger{ border-color: rgba(239,68,68,.20); background: rgba(239,68,68,.10); color:#991b1b; }

    /* ===== Cards Grid ===== */
    .grid{
      display:grid;
      grid-template-columns: 1.6fr 1fr;
      gap: 12px;
      margin-top: 12px;
      align-items: stretch;
    }
    .gridBottom{
      display:grid;
      grid-template-columns: 1.2fr 1.8fr;
      gap: 12px;
      margin-top: 12px;
      align-items: stretch;
    }

    .card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
    }
    .cardHead{
      padding: 10px 12px;
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      border-bottom: 1px solid #f0f2f7;
      background:#fff;
    }
    .cardTitle{
      display:flex; align-items:center; gap:10px;
      font-weight:850;
      font-size: 12.6px;
    }
    .cardTitle .miniIco{
      width: 34px; height: 34px;
      border-radius: 12px;
      display:grid; place-items:center;
      background: rgba(37,99,235,.08);
      border: 1px solid rgba(37,99,235,.18);
      color: #1d4ed8;
    }
    .cardBody{ padding: 10px 12px; }

    /* Compact charts */
    .chartWrap{ height: 160px; position:relative; }
    .donutWrap{ height: 190px; position:relative; }
    .muted{ color: var(--muted); font-weight: 600; }

    /* Activities */
    .actItem{
      display:flex; justify-content:space-between; gap:10px;
      padding: 10px 0;
      border-bottom: 1px solid #f1f5f9;
    }
    .actItem:last-child{ border-bottom:none; }
    .ellip{ white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }
    .actTitle{ font-weight:800; font-size: 12.6px; }
    .actMeta{ margin-top: 4px; color: var(--muted); font-weight:600; font-size: 11.2px; }
    .actRight{ text-align:right; flex-shrink:0; }

    .tag{
      display:inline-flex; align-items:center; gap:7px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 11.2px;
      font-weight: 800;
      border: 1px solid var(--border);
      background:#fff;
      cursor:pointer;
      white-space:nowrap;
    }
    .tag.req{ background:#eff6ff; border-color:#bfdbfe; color:#1e3a8a; }
    .tag.blk{ background:#f5f3ff; border-color:#ddd6fe; color:#5b21b6; }
    .tag.pbm{ background:#ecfeff; border-color:#a5f3fc; color:#0f766e; }

    /* Shortcuts (super simple) */
    .shortcutGrid{
      display:grid;
      grid-template-columns: repeat(2, minmax(0,1fr));
      gap: 10px;
    }
    .shortcut{
      display:flex; align-items:center; gap:10px;
      padding: 10px 12px;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow);
      cursor:pointer;
      transition: .15s ease;
      text-decoration:none;
      color:inherit;
      min-width:0;
    }
    .shortcut:hover{ transform: translateY(-2px); box-shadow: var(--shadowHover); }
    .shortcut .sIco{
      width: 36px; height: 36px;
      border-radius: 12px;
      display:grid; place-items:center;
      background: rgba(37,99,235,.08);
      border: 1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
      flex-shrink:0;
    }
    .shortcut .sText{ min-width:0; }
    .shortcut .sText .t{ font-weight:850; font-size: 12.4px; line-height: 1.2; }
    .shortcut .sText .d{ margin-top: 4px; color: var(--muted); font-weight:600; font-size: 11.2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    .footNote{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #f1f5f9;
    }

    /* ===== Responsive ===== */
    @media (max-width: 1100px){
      .stats{ grid-template-columns: repeat(2, minmax(0,1fr)); }
      .grid{ grid-template-columns: 1fr; }
      .gridBottom{ grid-template-columns: 1fr; }
    }
    @media (max-width: 820px){
      .app{ flex-direction:column; }
      .topbar{ position:relative; }
    }
  </style>
</head>

<body>
  <div class="app">
    @include('partials.sidebar')

    <div class="main">
      <header class="topbar">
        <div class="titleWrap">
          <div class="pageTitle"> Dashboard</div>
        </div>
        <div class="actions">
          <button class="btn" type="button" onclick="openHelp()">
            <i class="fa-solid fa-circle-question"></i> Info
          </button>
        </div>
      </header>

      <main class="container">



        <section class="grid">
          <div class="card">
            <div class="cardHead">
              <div class="cardTitle">
                <span class="miniIco"><i class="fa-solid fa-chart-line"></i></span>
                <span>Trend 12 Bulan</span>
              </div>
              <button class="btn btn-mini" type="button" onclick="openChartTips()">
                <i class="fa-solid fa-circle-info"></i> Tips
              </button>
            </div>
            <div class="cardBody">
              <div class="chartWrap">
                <canvas id="lineChart"></canvas>
              </div>
              <div class="muted" style="margin-top:8px">Ringkasan pengajuan per bulan (12 bulan terakhir).</div>
            </div>
          </div>

          <div class="card">
            <div class="cardHead">
              <div class="cardTitle">
                <span class="miniIco"><i class="fa-solid fa-chart-pie"></i></span>
                <span>Status Pengajuan</span>
              </div>
              <button class="btn btn-mini" type="button" onclick="openDonutInfo()">
                <i class="fa-solid fa-circle-info"></i> Detail
              </button>
            </div>
            <div class="cardBody">
              <div class="donutWrap">
                <canvas id="donutChart"></canvas>
              </div>
              <div class="muted" style="margin-top:8px">
                Pending <b>{{ (int)$donut['pending'] }}%</b> • Approved <b>{{ (int)$donut['approved'] }}%</b> • Others <b>{{ (int)$donut['others'] }}%</b>
              </div>
            </div>
          </div>
        </section>

        <section class="gridBottom">
          <div class="card">
            <div class="cardHead">
              <div class="cardTitle">
                <span class="miniIco"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <span>Aktivitas Terbaru</span>
              </div>
              <a class="btn btn-mini" href="{{ route('admin.pengajuan') }}">
                <i class="fa-solid fa-up-right-from-square"></i> Lihat semua
              </a>
            </div>
            <div class="cardBody" style="padding-top:6px">
              @forelse($recentActivities as $a)
                <div class="actItem">
                  <div class="ellip">
                    <div class="actTitle ellip">{{ $a->title ?? '(tanpa judul)' }}</div>
                    <div class="actMeta ellip">{{ $a->meta ?? '-' }}</div>
                  </div>

                  <div class="actRight">
                    @php($kind = $a->kind ?? '')
                    @if($kind === 'request')
                      <span class="tag req" onclick="goPengajuan('history','all')"><i class="fa-solid fa-paper-plane"></i> request</span>
                    @elseif($kind === 'block')
                      <span class="tag blk" onclick="goPengajuan('history','block')"><i class="fa-solid fa-lock"></i> block</span>
                    @elseif($kind === 'pbm')
                      <span class="tag pbm" onclick="window.location.href='{{ route('admin.pbm.index') }}'"><i class="fa-solid fa-calendar-week"></i> pbm</span>
                    @endif

                    <div class="actMeta" style="margin-top:6px">
                      {{ \Carbon\Carbon::parse($a->created_at)->diffForHumans() }}
                    </div>
                  </div>
                </div>
              @empty
                <div class="muted">Belum ada aktivitas.</div>
              @endforelse
            </div>
          </div>

          <div class="card">
            <div class="cardHead">
              <div class="cardTitle">
                <span class="miniIco"><i class="fa-solid fa-bolt"></i></span>
                <span>Akses Cepat</span>
              </div>
              <span class="muted">Fokus kerja admin</span>
            </div>

            <div class="cardBody">
              <div class="shortcutGrid">
                <button class="shortcut" type="button"
                        onclick="confirmGo('Pengajuan Menunggu', `{{ route('admin.pengajuan', ['view'=>'history','status'=>'menunggu']) }}`)">
                  <span class="sIco"><i class="fa-solid fa-bell"></i></span>
                  <span class="sText">
                    <div class="t">Pengajuan Menunggu</div>
                    <div class="d">Antrian approval</div>
                  </span>
                </button>

                <a class="shortcut" href="{{ route('admin.rooms.index') }}">
                  <span class="sIco"><i class="fa-solid fa-building"></i></span>
                  <span class="sText">
                    <div class="t">Kelola Ruangan</div>
                    <div class="d">CRUD data ruangan</div>
                  </span>
                </a>

                <button class="shortcut" type="button"
                        onclick="confirmGo('Ruangan Dipakai', `{{ route('admin.pengajuan', ['view'=>'rooms','type'=>'occupied']) }}`)">
                  <span class="sIco"><i class="fa-solid fa-door-closed"></i></span>
                  <span class="sText">
                    <div class="t">Ruangan Dipakai</div>
                    <div class="d">Realtime occupancy</div>
                  </span>
                </button>

                <button class="shortcut" type="button"
                        onclick="confirmGo('Kelola PBM', `{{ route('admin.pbm.index') }}`)">
                  <span class="sIco"><i class="fa-solid fa-calendar-days"></i></span>
                  <span class="sText">
                    <div class="t">Kelola PBM</div>
                    <div class="d">Template & jadwal</div>
                  </span>
                </button>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <script>
    (function initSidebarState(){
      const saved = localStorage.getItem('sb-collapsed');
      if (saved === null) localStorage.setItem('sb-collapsed', '0');
      const collapsed = localStorage.getItem('sb-collapsed') === '1';
      document.documentElement.classList.toggle('sb-collapsed', collapsed);
    })();

    window.toggleSidebar = function toggleSidebar(){
      const isCollapsed = document.documentElement.classList.toggle('sb-collapsed');
      try { localStorage.setItem('sb-collapsed', isCollapsed ? '1' : '0'); } catch(e){}
    }

    function goPengajuan(view, param){
      if (view === 'rooms'){
        window.location.href = `{{ route('admin.pengajuan') }}?view=rooms&type=${encodeURIComponent(param||'all')}`;
      } else {
        window.location.href = `{{ route('admin.pengajuan') }}?view=history&status=${encodeURIComponent(param||'all')}`;
      }
    }

    function confirmGo(title, url){
      Swal.fire({
        title: 'Buka halaman?',
        html: `<div style="font-weight:800">${title}</div><div style="margin-top:6px; color:#64748b">Lanjutkan?</div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, buka',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
      }).then((r)=>{ if (r.isConfirmed) window.location.href = url; });
    }

    function openHelp(){
      Swal.fire({
        title: 'Panduan Singkat',
        html: `
          <div style="text-align:left; line-height:1.7">
            <b>Menunggu</b>: pengajuan yang perlu approval.<br>
            <b>Dipakai</b>: ruangan yang sedang terisi (approved / block / PBM occurrence).<br>
            <b>Kosong</b>: ruangan tersedia sekarang.<br>
            <b>Riwayat</b>: total data request + block + PBM.
          </div>
        `,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb',
      });
    }

    function openMoreStats(){
      Swal.fire({
        title: 'Ringkasan Statistik',
        html: `
          <div style="text-align:left; line-height:1.7">
            <div><b>Total Ruangan:</b> {{ (int)$roomsTotal }}</div>
            <div><b>Dipakai:</b> {{ (int)$occupied }}</div>
            <div><b>Kosong:</b> {{ (int)$emptyRooms }}</div>
            <hr style="border:none;border-top:1px solid #e6eaf2;margin:10px 0">
            <div><b>Pending:</b> {{ (int)$pending }}</div>
            <div><b>Approved:</b> {{ (int)$approved }}</div>
            <div><b>Rejected:</b> {{ (int)$rejected }}</div>
            <div><b>Finished:</b> {{ (int)$finished }}</div>
            <div><b>Expired:</b> {{ (int)$expired }}</div>
            <hr style="border:none;border-top:1px solid #e6eaf2;margin:10px 0">
            <div><b>Blocks:</b> {{ (int)$blocksTotal }}</div>
            <div><b>PBM Approved:</b> {{ (int)$pbmOccTotal }}</div>
            <div><b>History Total:</b> {{ (int)$historyTotal }}</div>
          </div>
        `,
        icon: 'success',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#2563eb',
      });
    }

    function openChartTips(){
      Swal.fire({
        title: 'Tips Chart',
        html: `<div style="text-align:left; line-height:1.7">
          Trend mengambil data dari <b>borrow_requests.created_at</b> untuk 12 bulan terakhir.
        </div>`,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb',
      });
    }

    function openDonutInfo(){
      Swal.fire({
        title: 'Status Pengajuan',
        html: `<div style="text-align:left; line-height:1.7">
          Donut dihitung dari total <b>borrow_requests</b>.<br>
          Pending = menunggu • Approved = disetujui • Others = status lainnya.
        </div>`,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb',
      });
    }

    const labels = @json($labels);
    const series = @json($series);
    const donut  = @json([(int)$donut['pending'], (int)$donut['approved'], (int)$donut['others']]);

    new Chart(document.getElementById('lineChart'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Pengajuan',
          data: series,
          tension: 0.35,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: { maxRotation: 0, autoSkip: true } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });

    new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: ['Pending','Approved','Others'],
        datasets: [{ data: donut }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        cutout: '70%'
      }
    });
  </script>
</body>
</html>