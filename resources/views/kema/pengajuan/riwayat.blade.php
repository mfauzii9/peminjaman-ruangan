{{-- resources/views/kema/pengajuan/riwayat.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kemahasiswaan - Riwayat Pengajuan</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --text:#0b1220;
      --muted:#64748b;
      --border:#e6eaf2;

      --primary:#0f766e;
      --primary2:#10b981;

      --radius:16px;
      --radius2:14px;

      --shadow:0 10px 30px rgba(2, 6, 23, .06);
      --shadowHover:0 18px 50px rgba(2, 6, 23, .10);

      --success:#16a34a;
      --warning:#f59e0b;
      --danger:#ef4444;
      --slate:#475569;

      --successSoft:rgba(22,163,74,.10);
      --warningSoft:rgba(245,158,11,.12);
      --dangerSoft:rgba(239,68,68,.10);
      --slateSoft:rgba(71,85,105,.10);

      --successBorder:rgba(22,163,74,.18);
      --warningBorder:rgba(245,158,11,.22);
      --dangerBorder:rgba(239,68,68,.20);
      --slateBorder:rgba(71,85,105,.18);

      --priSoft:rgba(16,185,129,.10);
      --priBorder:rgba(16,185,129,.22);
    }

    *{ box-sizing:border-box; }

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background:var(--bg);
      color:var(--text);
      font-size:12.8px;
      line-height:1.6;
    }

    button,input,select,textarea,table{
      font-family:inherit;
    }

    /* ===== Layout sama dashboard ===== */
    .app{
      display:flex;
      min-height:100vh;
    }

    .main{
      flex:1;
      min-width:0;
    }

    /* ===== Collapse behavior sama dashboard ===== */
    html.sb-collapsed .sidebar{
      width:86px;
      min-width:86px;
    }

    html.sb-collapsed .sb__text,
    html.sb-collapsed .sb__title,
    html.sb-collapsed .sb__userinfo,
    html.sb-collapsed .sb__sectionLabel{
      display:none;
    }

    html.sb-collapsed .sb__brand{
      justify-content:center;
    }

    html.sb-collapsed .sb__toggle{
      margin-left:0;
    }

    html.sb-collapsed .sb__item{
      justify-content:center;
    }

    html.sb-collapsed .sb__badgePremium{
      position:absolute;
      right:8px;
      top:7px;
    }

    /* ===== Topbar ===== */
    .topbar{
      position:sticky;
      top:0;
      z-index:50;
      background:rgba(246,247,251,.85);
      backdrop-filter:blur(12px);
      border-bottom:1px solid var(--border);
      padding:12px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    .titleWrap{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
      min-width:0;
    }

    .pageTitle{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:8px 12px;
      border-radius:999px;
      background:rgba(16,185,129,.10);
      border:1px solid rgba(16,185,129,.18);
      color:#0f766e;
      font-weight:750;
      font-size:12px;
      white-space:nowrap;
    }

    .actions{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }

    /* ===== Button ===== */
    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      padding:10px 12px;
      border-radius:var(--radius2);
      border:1px solid var(--border);
      background:#fff;
      color:var(--text);
      text-decoration:none;
      font-weight:650;
      font-size:12px;
      transition:.15s ease;
      cursor:pointer;
      white-space:nowrap;
      box-shadow:none;
    }

    .btn:hover{
      transform:translateY(-1px);
      background:#f8fafc;
    }

    .btn-primary{
      background:linear-gradient(135deg, var(--primary), var(--primary2));
      border-color:transparent;
      color:#fff;
      box-shadow:0 14px 34px rgba(16,185,129,.18);
    }

    .btn-mini{
      padding:8px 10px;
      border-radius:12px;
      font-size:11.4px;
    }

    .btn-action{
      padding:8px 10px;
      border-radius:11px;
      font-size:11.4px;
      width:100%;
    }

    /* ===== Container ===== */
    .container{
      max-width:1240px;
      margin:16px auto 28px;
      padding:0 16px;
    }

    /* ===== Hero ===== */
    .hero{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:14px;
      box-shadow:var(--shadow);
    }

    .heroRow{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }

    .kicker{
      color:var(--muted);
      font-weight:650;
      font-size:11.6px;
    }

    .heroTitle{
      margin:6px 0 0;
      font-weight:800;
      font-size:18px;
      letter-spacing:.2px;
    }

    .heroDesc{
      margin:8px 0 0;
      color:var(--muted);
      font-weight:600;
      max-width:840px;
    }

    /* ===== Note ===== */
    .note{
      margin-top:12px;
      background:#fff;
      border:1px solid var(--border);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:10px 12px;
      display:flex;
      align-items:flex-start;
      gap:10px;
      color:var(--muted);
      font-weight:600;
    }

    .note i{
      margin-top:2px;
      color:#0f766e;
    }

    /* ===== Card ===== */
    .card{
      margin-top:12px;
      background:var(--card);
      border:1px solid var(--border);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      overflow:hidden;
    }

    .cardHead{
      padding:10px 12px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      border-bottom:1px solid #f0f2f7;
      background:#fff;
      flex-wrap:wrap;
    }

    .cardTitle{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:750;
      font-size:12.6px;
    }

    .miniIco{
      width:34px;
      height:34px;
      border-radius:12px;
      display:grid;
      place-items:center;
      background:rgba(16,185,129,.10);
      border:1px solid rgba(16,185,129,.18);
      color:#0f766e;
    }

    .cardSub{
      margin-top:4px;
      color:var(--muted);
      font-weight:600;
      font-size:11.2px;
    }

    /* ===== Toolbar ===== */
    .toolbar{
      padding:10px 12px;
      border-bottom:1px solid #eef2f7;
      background:#fbfcff;
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      align-items:center;
      justify-content:space-between;
    }

    .toolLeft,
    .toolRight{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
    }

    .field{
      display:flex;
      align-items:center;
      gap:8px;
      padding:8px 10px;
      border:1px solid var(--border);
      border-radius:12px;
      background:#fff;
      min-height:38px;
    }

    .field label{
      font-size:11.2px;
      font-weight:650;
      color:#475569;
      white-space:nowrap;
    }

    .field input,
    .field select{
      border:none;
      outline:none;
      background:transparent;
      font-size:12px;
      font-weight:600;
      color:var(--text);
      min-width:0;
    }

    .field.search{
      min-width:260px;
    }

    .field.search input{
      width:220px;
    }

    /* ===== Table ===== */
    .cardBody{
      padding:10px 12px;
    }

    .tableWrap{
      border:1px solid #eef2f7;
      border-radius:14px;
      overflow:hidden;
      background:#fff;
    }

    .tableMeta{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      flex-wrap:wrap;
      padding:10px 10px;
      border-bottom:1px solid #eef2f7;
      background:#fbfcff;
    }

    .toolNote{
      color:var(--muted);
      font-weight:600;
      font-size:11.4px;
      display:flex;
      align-items:center;
      gap:8px;
    }

    .tableScroll{
      overflow:auto;
      max-width:100%;
    }

    table{
      width:100%;
      border-collapse:collapse;
      min-width:900px;
    }

    th, td{
      padding:10px 10px;
      border-bottom:1px solid #eef2f7;
      text-align:left;
      font-size:11.6px;
      vertical-align:middle;
    }

    th{
      position:sticky;
      top:0;
      z-index:2;
      background:#fbfcff;
      font-weight:700;
      font-size:10.6px;
      text-transform:uppercase;
      letter-spacing:.35px;
      color:#334155;
      white-space:nowrap;
    }

    td{
      white-space:normal;
      word-break:break-word;
    }

    tr:hover td{
      background:#fafbff;
    }

    .mainText{
      font-weight:650;
      color:var(--text);
    }

    .subText{
      margin-top:4px;
      color:var(--muted);
      font-weight:600;
      font-size:11.1px;
    }

    .badge{
      display:inline-flex;
      align-items:center;
      gap:7px;
      padding:6px 10px;
      border-radius:999px;
      border:1px solid var(--border);
      font-weight:650;
      font-size:11.2px;
      background:#fff;
      white-space:nowrap;
    }

    .b-wait{
      background:var(--warningSoft);
      border-color:var(--warningBorder);
      color:#92400e;
    }

    .b-ok{
      background:var(--successSoft);
      border-color:var(--successBorder);
      color:#166534;
    }

    .b-no{
      background:var(--dangerSoft);
      border-color:var(--dangerBorder);
      color:#991b1b;
    }

    .b-exp{
      background:#f8fafc;
      border-color:#e2e8f0;
      color:#334155;
    }

    /* ===== Skeleton ===== */
    .skeleton{
      position:relative;
      overflow:hidden;
      background:#eef2ff;
      border-radius:10px;
      height:12px;
    }

    .skeleton::after{
      content:'';
      position:absolute;
      inset:-100% -150%;
      background:linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent);
      animation:shimmer 1.05s ease infinite;
    }

    @keyframes shimmer{
      0%{transform:translateX(-30%)}
      100%{transform:translateX(30%)}
    }

    /* ===== Overlay ===== */
    .overlay{
      position:fixed;
      inset:0;
      z-index:9998;
      background:rgba(15,23,42,.22);
      display:none;
      align-items:center;
      justify-content:center;
      backdrop-filter:blur(4px);
    }

    .overlay.show{
      display:flex;
    }

    .overlayCard{
      background:#fff;
      border:1px solid rgba(226,232,240,.95);
      border-radius:18px;
      box-shadow:0 18px 60px rgba(15,23,42,.22);
      padding:14px 16px;
      min-width:280px;
      display:flex;
      gap:12px;
      align-items:center;
    }

    .spinner{
      width:18px;
      height:18px;
      border-radius:999px;
      border:3px solid rgba(16,185,129,.20);
      border-top-color:rgba(16,185,129,.95);
      animation:spin .8s linear infinite;
    }

    @keyframes spin{
      to{ transform:rotate(360deg); }
    }

    @media (max-width:820px){
      .app{
        flex-direction:column;
      }

      .topbar{
        position:relative;
      }

      .toolbar{
        flex-direction:column;
        align-items:stretch;
      }

      .toolLeft,
      .toolRight{
        width:100%;
      }

      .field.search{
        min-width:unset;
        width:100%;
      }

      .field.search input{
        width:100%;
      }
    }
  </style>
</head>

<body>
  <div class="app">
    @include('partials.kema.sidebar')

    <div class="main">
      <header class="topbar">
        <div class="titleWrap">
          <div class="pageTitle">
            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengajuan
          </div>
        </div>

        <div class="actions">
          <button class="btn" type="button" onclick="showTips()">
            <i class="fa-solid fa-lightbulb"></i> Tips
          </button>
        </div>
      </header>

      <main class="container">
        <section class="card">
          <div class="cardHead">
            <div>
              <div class="cardTitle">
                <span class="miniIco"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <span>Daftar Riwayat</span>
              </div>
              <div class="cardSub">Tampilan seragam dengan dashboard, pengajuan, dan sidebar yang bisa open / close.</div>
            </div>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <button class="btn btn-mini" type="button" onclick="applyFilters(true)">
                <i class="fa-solid fa-rotate"></i> Muat Ulang
              </button>
            </div>
          </div>

          <div class="toolbar">
            <div class="toolLeft">
              <div class="field">
                <label><i class="fa-solid fa-filter"></i> Status</label>
                <select id="kemaStatus">
                  <option value="all" selected>Semua</option>
                  <option value="disetujui">Disetujui</option>
                  <option value="ditolak">Ditolak</option>
                  <option value="hangus">Hangus</option>
                </select>
              </div>

              <div class="field">
                <label><i class="fa-regular fa-calendar"></i> Dari</label>
                <input type="date" id="dateFrom">
              </div>

              <div class="field">
                <label><i class="fa-regular fa-calendar"></i> Sampai</label>
                <input type="date" id="dateTo">
              </div>

              <div class="field search">
                <label><i class="fa-solid fa-magnifying-glass"></i> Cari</label>
                <input type="text" id="q" placeholder="nama / email / organisasi / kode booking">
              </div>
            </div>

            <div class="toolRight">
              <button class="btn btn-primary" type="button" onclick="applyFilters(true)">
                <i class="fa-solid fa-check"></i> Terapkan
              </button>
              <button class="btn" type="button" onclick="resetFilters()">
                <i class="fa-solid fa-rotate-left"></i> Reset
              </button>
            </div>
          </div>

          <div class="cardBody">
            <div class="tableWrap">
              <div class="tableMeta">
                <div class="toolNote">
                  <i class="fa-solid fa-circle-info"></i>
                  <span id="meta">Memuat data...</span>
                </div>
              </div>

              <div class="tableScroll">
                <table>
                  <thead>
                    <tr>
                      <th style="width:90px;">ID</th>
                      <th style="width:160px;">Kode</th>
                      <th style="width:180px;">Ruangan</th>
                      <th style="width:230px;">Pemohon</th>
                      <th style="width:230px;">Jadwal</th>
                      <th style="width:150px;">Status KEMA</th>
                      <th style="width:160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">
                    <tr>
                      <td colspan="7" style="text-align:center; padding:22px; color:var(--muted); font-weight:600;">
                        <i class="fa-solid fa-circle-info"></i> Memuat data...
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <div id="overlay" class="overlay" aria-hidden="true">
    <div class="overlayCard">
      <div class="spinner"></div>
      <div>
        <div style="font-weight:700;">Memuat data...</div>
        <div style="color:#64748b;font-weight:600;font-size:12px;margin-top:2px;">Mohon tunggu.</div>
      </div>
    </div>
  </div>

  <script>
    const URL_LIST    = @json(route('kema.pengajuan.list', [], false));
    const BASE_DETAIL = @json(url('/kema/pengajuan'));

    const overlay = document.getElementById('overlay');
    const tbody   = document.getElementById('tbody');
    const meta    = document.getElementById('meta');

    const kemaStatus = document.getElementById('kemaStatus');
    const dateFrom   = document.getElementById('dateFrom');
    const dateTo     = document.getElementById('dateTo');
    const q          = document.getElementById('q');

    const toast = (icon, title) => Swal.fire({
      toast:true,
      position:'top-end',
      icon,
      title,
      showConfirmButton:false,
      timer:2600,
      timerProgressBar:true
    });

    /* ===== Sidebar collapse state sama dashboard ===== */
    (function initSidebarState(){
      const saved = localStorage.getItem('sb-collapsed-kema');
      if (saved === null) localStorage.setItem('sb-collapsed-kema', '0');
      const collapsed = localStorage.getItem('sb-collapsed-kema') === '1';
      document.documentElement.classList.toggle('sb-collapsed', collapsed);
    })();

    window.toggleSidebar = function toggleSidebar(){
      const isCollapsed = document.documentElement.classList.toggle('sb-collapsed');
      try{
        localStorage.setItem('sb-collapsed-kema', isCollapsed ? '1' : '0');
      }catch(e){}
    }

    function showOverlay(){ overlay.classList.add('show'); }
    function hideOverlay(){ overlay.classList.remove('show'); }

    function escapeHtml(s){
      return String(s ?? '').replace(/[&<>"']/g, (m)=>( {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m] ));
    }

    function badge(st){
      st = (st || '').toLowerCase();
      if (st === 'disetujui') return `<span class="badge b-ok"><i class="fa-solid fa-circle-check"></i> Disetujui</span>`;
      if (st === 'ditolak')   return `<span class="badge b-no"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>`;
      if (st === 'hangus')    return `<span class="badge b-exp"><i class="fa-solid fa-clock"></i> Hangus</span>`;
      return `<span class="badge b-wait"><i class="fa-solid fa-hourglass-half"></i> Menunggu</span>`;
    }

    async function safeFetch(url){
      showOverlay();
      try{
        const res = await fetch(url, {
          cache:'no-store',
          headers:{ 'Accept':'application/json' }
        });

        const json = await res.json().catch(()=>null);
        if (!res.ok || !json) throw new Error(json?.message || `Fetch gagal (${res.status})`);
        return json;
      } finally {
        hideOverlay();
      }
    }

    function renderSkeleton(){
      const row = () => `
        <tr>
          <td><div class="skeleton" style="width:64px"></div></td>
          <td><div class="skeleton" style="width:110px"></div></td>
          <td><div class="skeleton" style="width:150px"></div></td>
          <td><div class="skeleton" style="width:180px"></div></td>
          <td><div class="skeleton" style="width:170px"></div></td>
          <td><div class="skeleton" style="width:110px"></div></td>
          <td><div class="skeleton" style="width:120px;height:30px;border-radius:11px"></div></td>
        </tr>
      `;
      tbody.innerHTML = row()+row()+row()+row()+row();
    }

    async function applyFilters(showToast=false){
      if (!URL_LIST){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:22px; color:var(--muted); font-weight:600;">Route <b>kema.pengajuan.list</b> belum dibuat.</td></tr>`;
        return;
      }

      const st = kemaStatus.value || 'all';
      const df = (dateFrom.value || '').trim();
      const dt = (dateTo.value || '').trim();
      const qq = (q.value || '').trim();

      renderSkeleton();
      meta.textContent = 'Memuat data...';

      const params = new URLSearchParams({
        view: 'history',
        status: st,
        date_from: df,
        date_to: dt,
        q: qq
      });

      let json;
      try{
        json = await safeFetch(URL_LIST + '?' + params.toString());
      }catch(e){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:22px; color:var(--muted); font-weight:600;">Gagal memuat data: ${escapeHtml(e.message)}</td></tr>`;
        meta.textContent = 'Gagal';
        if (showToast) toast('error', e.message);
        return;
      }

      if (!json || !json.ok){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:22px; color:var(--muted); font-weight:600;">Gagal memuat data.</td></tr>`;
        meta.textContent = 'Gagal';
        if (showToast) toast('error', json?.message || 'Gagal memuat data');
        return;
      }

      const items = json.items || [];

      meta.textContent =
        `Total: ${items.length}`
        + (st && st !== 'all' ? ` • status=${st}` : '')
        + (df ? ` • dari=${df}` : '')
        + (dt ? ` • sampai=${dt}` : '')
        + (qq ? ` • q="${qq}"` : '');

      if (!items.length){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:22px; color:var(--muted); font-weight:600;"><i class="fa-solid fa-circle-info"></i> Tidak ada data riwayat.</td></tr>`;
        if (showToast) toast('info', 'Tidak ada data');
        return;
      }

      tbody.innerHTML = items.map(r => {
        const room = escapeHtml((r.room_floor ?? '-') + ' - ' + (r.room_name ?? '-'));
        const peminjam = escapeHtml(r.responsible_name ?? '-');
        const detailUrl = `${BASE_DETAIL}/${encodeURIComponent(r.id)}`;

        return `
          <tr>
            <td>
              <div class="mainText">#${escapeHtml(r.id)}</div>
              <div class="subText">${escapeHtml(r.created_at || '')}</div>
            </td>

            <td>
              <div class="mainText">${escapeHtml(r.public_code || '-')}</div>
            </td>

            <td>
              <div class="mainText">${room}</div>
            </td>

            <td>
              <div class="mainText">${peminjam}</div>
              ${r.email ? `<div class="subText">${escapeHtml(r.email)}</div>` : ``}
              ${r.org_name ? `<div class="subText"><i class="fa-solid fa-building" style="margin-right:6px; opacity:.75;"></i>${escapeHtml(r.org_name)}</div>` : ``}
              ${r.phone ? `<div class="subText"><i class="fa-solid fa-phone" style="margin-right:6px; opacity:.75;"></i>${escapeHtml(r.phone)}</div>` : ``}
            </td>

            <td>
              <div class="mainText">${escapeHtml(r.start_time ?? '-')}</div>
              <div class="subText">s/d ${escapeHtml(r.end_time ?? '-')}</div>
            </td>

            <td>${badge(r.kema_status)}</td>

            <td>
              <a class="btn btn-primary btn-action" href="${detailUrl}">
                <i class="fa-solid fa-eye"></i> Detail
              </a>
            </td>
          </tr>
        `;
      }).join('');

      if (showToast) toast('success', 'Riwayat dimuat');
    }

    function resetFilters(){
      kemaStatus.value = 'all';
      dateFrom.value = '';
      dateTo.value = '';
      q.value = '';
      applyFilters(true);
    }

    function showTips(){
      Swal.fire({
        icon:'info',
        title:'Tips Pencarian Riwayat',
        html:`
          <div style="text-align:left;font-size:12px;line-height:1.7;color:#334155;">
            <div style="font-weight:700;margin-bottom:6px">Cara mencari lebih cepat</div>
            <ol style="margin:6px 0 0 18px;padding:0;">
              <li>Pilih status tertentu bila ingin hasil lebih spesifik</li>
              <li>Gunakan rentang tanggal untuk mempersempit data</li>
              <li>Cari berdasarkan nama, email, organisasi, atau kode booking</li>
              <li>Buka detail untuk melihat catatan proses verifikasi</li>
            </ol>
          </div>
        `,
        confirmButtonText:'Oke',
        confirmButtonColor:'#10b981'
      });
    }

    let typingTimer = null;
    q.addEventListener('input', ()=>{
      clearTimeout(typingTimer);
      typingTimer = setTimeout(()=>applyFilters(false), 300);
    });

    applyFilters(false);
  </script>
</body>
</html>