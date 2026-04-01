{{-- resources/views/admin/quick-booking.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Quick Booking</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --bg:#f6f7fb; --card:#ffffff; --text:#0b1220; --muted:#64748b; --border:#e7eaf3;
      --shadow: 0 16px 50px rgba(2,6,23,.08);
      --shadow2: 0 10px 26px rgba(2,6,23,.06);
      --radius:18px; --radius2:14px;
      --accent:#2563eb; --accent2:#1d4ed8;
      --danger:#ef4444; --ok:#16a34a; --warn:#f59e0b;
      --ring: 0 0 0 4px rgba(37,99,235,.12);
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      color:var(--text);
      background:
        radial-gradient(900px 420px at 15% -10%, rgba(37,99,235,.10), transparent 55%),
        radial-gradient(820px 380px at 95% 0%, rgba(245,158,11,.10), transparent 50%),
        var(--bg);
      font-size:13px;
      line-height:1.55;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* Layout */
    .app{display:flex; min-height:100vh;}
    .main{flex:1; min-width:0;}

    .topbar{
      position:sticky; top:0; z-index:50;
      background: rgba(246,247,251,.78);
      backdrop-filter: blur(14px);
      border-bottom:1px solid rgba(231,234,243,.9);
      padding:12px 16px;
      display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
    }
    .crumb{display:flex; align-items:center; gap:10px; flex-wrap:wrap;}
    .pill{
      display:inline-flex; align-items:center; gap:10px;
      padding: 8px 12px;
      border-radius:999px;
      background: rgba(37,99,235,.10);
      border:1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
      font-weight:700;
      font-size:12px;
      letter-spacing:.2px;
    }
    .muted{color:var(--muted); font-weight:500; font-size:12px;}
    .container{max-width:1120px; margin:16px auto 30px; padding:0 16px;}

    /* Card Layout */
    .card{
      background:#fff;
      border:1px solid rgba(231,234,243,.95);
      border-radius: var(--radius);
      box-shadow: 0 12px 28px rgba(2,6,23,.06);
      overflow:hidden;
    }
    .cardHead{
      padding:12px 14px;
      display:flex; align-items:flex-start; justify-content:space-between; gap:10px;
      border-bottom:1px solid #f0f2f7;
      background: linear-gradient(180deg, #fff, #fcfdff);
    }
    .cardTitle{
      display:flex; align-items:center; gap:10px;
      font-weight:700;
      letter-spacing:.2px;
      font-size:13px;
    }
    .cardTitle i{
      width:34px;height:34px;border-radius:14px;
      display:grid;place-items:center;
      background: rgba(37,99,235,.08);
      border:1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
    }
    .cardBody{padding:12px 14px;}

    /* Room list */
    .roomList{display:flex; flex-direction:column; gap:10px;}
    .roomRow{
      border:1px solid rgba(231,234,243,.95);
      border-radius:16px;
      background:#fff;
      box-shadow: 0 10px 22px rgba(2,6,23,.06);
      padding:12px 12px;
      cursor:pointer;
      position:relative;
      overflow:hidden;
      transition:.16s ease;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }
    .roomRow::before{
      content:""; position:absolute; inset:0;
      background:
        radial-gradient(560px 180px at 18% 0%, rgba(37,99,235,.08), transparent 58%),
        radial-gradient(520px 180px at 95% 10%, rgba(2,6,23,.03), transparent 55%);
      pointer-events:none; opacity:1;
    }
    .roomRow:hover{
      transform:translateY(-2px);
      border-color: rgba(37,99,235,.22);
      box-shadow: 0 18px 44px rgba(2,6,23,.10);
    }
    .roomRow > *{position:relative; z-index:1;}

    .roomLeft{min-width:0}
    .roomName{
      margin:0; font-size:13.5px; font-weight:700;
      display:flex; align-items:center; gap:8px; letter-spacing:.15px;
    }
    .roomName i{color:#1f2937; opacity:.9}
    .meta{
      margin-top:6px; display:flex; gap:10px; flex-wrap:wrap;
      color:var(--muted); font-weight:500; font-size:12px;
    }
    .sub{
      margin-top:8px; color:var(--muted); font-weight:400; font-size:12px;
      white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:820px;
    }
    @media(max-width:800px){ .sub{max-width:420px} }
    @media(max-width:540px){ .sub{max-width:240px} }

    .roomRight{flex-shrink:0; display:flex; align-items:center; gap:8px; flex-wrap:wrap; justify-content:flex-end}
    .chip{
      display:inline-flex; align-items:center; gap:7px;
      padding:6px 10px; border-radius:999px;
      border:1px solid rgba(37,99,235,.18); background: rgba(37,99,235,.08);
      color:#1d4ed8; font-weight:600; font-size:11.5px; white-space:nowrap;
    }
    .chip2{
      display:inline-flex; align-items:center; gap:7px;
      padding:6px 10px; border-radius:999px;
      border:1px solid rgba(231,234,243,.95); background:#fff;
      color:#0f172a; font-weight:600; font-size:11.5px; white-space:nowrap;
    }

    /* Pager */
    .pager{
      margin-top:12px; display:flex; align-items:center; justify-content:space-between;
      gap:10px; flex-wrap:wrap; padding:10px 12px;
      border:1px solid rgba(231,234,243,.95); border-radius:16px;
      background:#fff; box-shadow: 0 10px 22px rgba(2,6,23,.06);
    }
    .pager .pbtn{
      display:inline-flex;align-items:center;gap:8px;
      padding:8px 10px;border-radius:12px; border:1px solid rgba(231,234,243,.95);
      background:#fff; font-weight:600;font-size:12px;cursor:pointer;
      box-shadow: 0 6px 14px rgba(2,6,23,.05);
    }
    .pager .pbtn:hover{border-color: rgba(37,99,235,.18); background:#f7f9ff;}
    .pager .pbtn:disabled{opacity:.45;cursor:not-allowed}
    .pager .info{color:var(--muted);font-weight:500;font-size:12px}

    /* === POPUP QUICK BOOKING (NEW DESIGN) === */
    .swal2-popup.qb-mini{
      border-radius:24px !important;
      padding:0 !important;
      width:520px !important;
      max-width:calc(100% - 32px) !important;
      overflow:visible !important;
      font-family: Inter, system-ui, -apple-system, sans-serif !important;
      box-shadow: 0 30px 80px rgba(2,6,23,.22) !important;
    }
    .swal2-popup.qb-mini .swal2-close{
      width:36px; height:36px;
      border-radius:50%;
      background:#fff;
      border:1px solid rgba(231,234,243,.95);
      color:#0b1220;
      box-shadow: 0 8px 18px rgba(2,6,23,.08);
      position: absolute;
      top: -14px; right: -14px;
      z-index: 10;
      transition: .2s ease;
    }
    .swal2-popup.qb-mini .swal2-close:hover {
      background: var(--danger); color: #fff; border-color: transparent;
      transform: scale(1.05);
    }
    .swal2-popup.qb-mini .swal2-html-container{
      margin:0 !important; padding:0 !important;
      font-size:13px !important; line-height:1.5 !important;
      text-align: left !important;
    }

    /* Popup Box Elements */
    .qbBox { display: flex; flex-direction: column; }
    
    /* Elegant Header */
    .qbHeader {
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color: #fff;
      padding: 24px 28px;
      border-radius: 24px 24px 0 0;
      display: flex; gap: 16px; align-items: center;
      position: relative;
      overflow: hidden;
    }
    .qbHeader::after {
      content:''; position: absolute; right: -20px; top: -40px;
      width: 150px; height: 150px; border-radius: 50%;
      background: rgba(255,255,255,0.08);
      pointer-events: none;
    }
    .qbHeaderIcon {
      width: 48px; height: 48px; border-radius: 16px;
      background: rgba(255,255,255,0.2);
      display: grid; place-items: center;
      font-size: 20px; flex-shrink: 0;
      border: 1px solid rgba(255,255,255,0.3);
      backdrop-filter: blur(8px);
    }
    .qbHeaderTitle { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
    .qbHeaderMeta { font-size: 12.5px; color: rgba(255,255,255,0.8); font-weight: 500; }

    /* Form Body */
    .qbBody { padding: 24px 28px 28px; background: #fff; border-radius: 0 0 24px 24px; }

    .formRow { display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
    .formCol { flex: 1; min-width: 160px; }

    .formLabel {
      display: block; font-size: 12px; font-weight: 600; color: var(--muted);
      margin-bottom: 6px; letter-spacing: 0.2px;
    }
    
    /* Input Styling */
    .inputWrapper { position: relative; }
    .inputWrapper i {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #94a3b8; font-size: 13px; pointer-events: none;
    }
    .qbInput, .qbSelect {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 10px 14px 10px 38px;
      font: inherit; font-weight: 600; font-size: 13px;
      color: var(--text); background: #f8fafc;
      transition: .2s ease; outline: none;
    }
    .qbSelect { cursor: pointer; appearance: none; }
    .qbInput:focus, .qbSelect:focus {
      background: #fff; border-color: rgba(37,99,235,.4); box-shadow: var(--ring);
    }
    .qbInput::placeholder { color: #cbd5e1; font-weight: 500; }

    /* Error Alert */
    .qbConflict {
      display: none; margin-top: 8px; margin-bottom: 20px; padding: 12px 14px;
      border-radius: 14px; border: 1px solid rgba(239,68,68,.25);
      background: rgba(239,68,68,.06); color: #b91c1c;
      font-weight: 600; font-size: 12px; display: flex; align-items: center; gap: 10px;
    }
    .qbConflict i { font-size: 14px; }

    /* Action Button */
    .qbSubmit {
      width: 100%; border: none; border-radius: 14px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color: #fff; font-weight: 600; font-size: 14px; padding: 14px;
      cursor: pointer; box-shadow: 0 12px 24px rgba(37,99,235,.2);
      transition: .2s ease; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .qbSubmit:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(37,99,235,.3); }
    .qbSubmit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

  </style>

  <script>
    document.documentElement.classList.remove('sb-collapsed');
  </script>
</head>

<body>
<div class="app">
  @include('partials.sidebar')

  <div class="main">
    <header class="topbar">
      <div class="crumb">
        <div class="pill"><i class="fa-solid fa-bolt"></i> Quick Booking</div>
        <div class="muted">Pilih ruangan untuk memproses booking dengan cepat</div>
      </div>
    </header>

    <div class="container">
      <section class="card">
        <div class="cardHead">
          <div class="cardTitle">
            <i class="fa-solid fa-door-open"></i>
            <span>Daftar Ruangan</span>
          </div>
          <div class="muted">Klik ruangan untuk booking cepat</div>
        </div>

        <div class="cardBody">
          <div class="roomList" id="roomList">
            @foreach($rooms as $r)
              <article class="roomRow"
                data-room-id="{{ (int)$r->id }}"
                data-room-name="{{ e($r->name ?? '-') }}"
                data-room-floor="{{ e($r->floor ?? '-') }}"
                data-room-cap="{{ (int)($r->capacity ?? 0) }}"
                data-room-fac="{{ e($r->facilities ?? '-') }}"
              >
                <div class="roomLeft">
                  <h3 class="roomName">
                    <i class="fa-solid fa-door-closed"></i>
                    <span class="roomNameTxt">{{ $r->name ?? '-' }}</span>
                  </h3>
                  <div class="meta">
                    <span><i class="fa-solid fa-layer-group"></i> Lantai: {{ $r->floor ?? '-' }}</span>
                    <span><i class="fa-solid fa-users"></i> {{ (int)($r->capacity ?? 0) }} org</span>
                    <span class="chip2"><i class="fa-solid fa-hashtag"></i> ID: {{ (int)$r->id }}</span>
                  </div>
                  <div class="sub"><b style="font-weight:600">Fasilitas:</b> {{ $r->facilities ?? '-' }}</div>
                </div>

                <div class="roomRight">
                  <span class="chip"><i class="fa-solid fa-bolt"></i> Booking</span>
                </div>
              </article>
            @endforeach
          </div>

          <div class="pager" id="pager">
            <button class="pbtn" id="btnPrev" type="button"><i class="fa-solid fa-chevron-left"></i> Prev</button>
            <div class="info" id="pageInfo">—</div>
            <button class="pbtn" id="btnNext" type="button">Next <i class="fa-solid fa-chevron-right"></i></button>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
  /* ===================== SIDEBAR COLLAPSE ===================== */
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

  /* ===================== CONFIG & ROUTING ===================== */
  const CSRF = @json(csrf_token());
  const URL_LIST   = @json(route('admin.room_blocks.list'));
  const URL_CREATE = @json(route('admin.room_blocks.create'));

  const roomListEl = document.getElementById('roomList');
  const roomRows = Array.from(roomListEl.querySelectorAll('.roomRow'));

  /* ===================== PAGINATION ===================== */
  const PAGE_SIZE = 6;
  let page = 1;
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const pageInfo = document.getElementById('pageInfo');

  function totalPages(){ return Math.max(1, Math.ceil(roomRows.length / PAGE_SIZE)); }
  function renderPage(){
    const tp = totalPages();
    if (page < 1) page = 1;
    if (page > tp) page = tp;
    const start = (page - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    roomRows.forEach((row, idx)=> row.style.display = (idx >= start && idx < end) ? '' : 'none');
    btnPrev.disabled = page <= 1;
    btnNext.disabled = page >= tp;
    pageInfo.textContent = `Page ${page} / ${tp} • Total ${roomRows.length} ruangan`;
  }
  btnPrev.addEventListener('click', ()=>{ page--; renderPage(); });
  btnNext.addEventListener('click', ()=>{ page++; renderPage(); });
  renderPage();

  /* ===================== HELPERS ===================== */
  function todayStr(){
    const d = new Date();
    const m = String(d.getMonth()+1).padStart(2,'0');
    const da = String(d.getDate()).padStart(2,'0');
    return `${d.getFullYear()}-${m}-${da}`;
  }
  function escapeHtml(s){
    return String(s ?? '').replace(/[&<>"']/g, (m)=>( {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m] ));
  }
  function isTimeOverlap(aStart, aEnd, bStart, bEnd){
    return aStart < bEnd && bStart < aEnd;
  }

  async function fetchSchedule(roomId, date){
    const params = new URLSearchParams({ room_id: roomId, date });
    const res = await fetch(URL_LIST + '?' + params.toString(), { cache:'no-store' });
    return res.json().catch(()=>({ok:false,message:'Response error'}));
  }

  /* ===================== NEW POPUP QUICK BOOKING ===================== */
  async function openPopupForRoom(room){
    const date = todayStr();

    const html = `
      <div class="qbBox">
        <div class="qbHeader">
          <div class="qbHeaderIcon"><i class="fa-solid fa-door-open"></i></div>
          <div>
            <div class="qbHeaderTitle">${escapeHtml(room.name)}</div>
            <div class="qbHeaderMeta">Lantai ${escapeHtml(room.floor)} • ${escapeHtml(room.cap)} org • ${escapeHtml(room.fac || '-')}</div>
          </div>
        </div>

        <div class="qbBody">
          <div class="formRow">
            <div class="formCol" style="flex: 100%">
              <label class="formLabel">Pilih Tanggal</label>
              <div class="inputWrapper">
                <i class="fa-regular fa-calendar-alt"></i>
                <input id="qb_date" class="qbInput" type="date" value="${date}">
              </div>
            </div>
          </div>

          <div class="formRow">
            <div class="formCol">
              <label class="formLabel">Mulai</label>
              <div class="inputWrapper">
                <i class="fa-regular fa-clock"></i>
                <input id="qb_start" class="qbInput" type="time" value="07:30">
              </div>
            </div>
            <div class="formCol">
              <label class="formLabel">Selesai</label>
              <div class="inputWrapper">
                <i class="fa-regular fa-clock"></i>
                <input id="qb_end" class="qbInput" type="time" value="08:30">
              </div>
            </div>
          </div>

          <div class="formRow">
            <div class="formCol" style="flex: 100%">
              <label class="formLabel">Judul Kegiatan</label>
              <div class="inputWrapper">
                <i class="fa-solid fa-align-left"></i>
                <input id="qb_title" class="qbInput" type="text" placeholder="Contoh: Rapat Dosen / Kelas Tambahan">
              </div>
            </div>
          </div>

          <div class="formRow">
            <div class="formCol">
              <label class="formLabel">Kelas (Opsional)</label>
              <div class="inputWrapper">
                <i class="fa-solid fa-graduation-cap"></i>
                <input id="qb_kelas" class="qbInput" type="text" placeholder="Cth: 4IF">
              </div>
            </div>
            <div class="formCol">
              <label class="formLabel">Dosen (Opsional)</label>
              <div class="inputWrapper">
                <i class="fa-solid fa-user-tie"></i>
                <input id="qb_dosen" class="qbInput" type="text" placeholder="Nama dosen">
              </div>
            </div>
          </div>

          <div class="formRow" style="margin-bottom: 24px;">
            <div class="formCol" style="flex: 100%">
              <label class="formLabel">Sumber Jadwal</label>
              <div class="inputWrapper">
                <i class="fa-solid fa-filter"></i>
                <select id="qb_source" class="qbSelect">
                  <option value="admin">Admin</option>
                  <option value="kemahasiswaan">Kemahasiswaan</option>
                  <option value="pbm">PBM</option>
                  <option value="mahasiswa">Mahasiswa</option>
                  <option value="jadwal">Jadwal Kuliah</option>
                </select>
              </div>
            </div>
          </div>

          <div id="qb_conflict" class="qbConflict" style="display:none;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span id="qb_conflict_msg">Jadwal bentrok atau tidak valid.</span>
          </div>

          <button id="qb_submit" class="qbSubmit" type="button" disabled>
            <i class="fa-solid fa-spinner fa-spin"></i> Memeriksa Ketersediaan...
          </button>
        </div>
      </div>
    `;

    await Swal.fire({
      html,
      showConfirmButton: false,
      showCloseButton: true,
      customClass: { popup: 'qb-mini' },
      didOpen: async () => {
        const popup = Swal.getPopup();

        const dateEl  = popup.querySelector('#qb_date');
        const startEl = popup.querySelector('#qb_start');
        const endEl   = popup.querySelector('#qb_end');
        const titleEl = popup.querySelector('#qb_title');
        const kelasEl = popup.querySelector('#qb_kelas');
        const dosenEl = popup.querySelector('#qb_dosen');
        const sourceEl= popup.querySelector('#qb_source');

        const conflictEl = popup.querySelector('#qb_conflict');
        const conflictMsg = popup.querySelector('#qb_conflict_msg');
        const submitBtn  = popup.querySelector('#qb_submit');

        let scheduleItems = [];

        // Validasi dan set alert bentrok
        function setConflict(msg){
          conflictEl.style.display = 'flex';
          conflictMsg.textContent = msg;
        }
        function clearConflict(){
          conflictEl.style.display = 'none';
        }

        // Fungsi fetch jadwal dilatar belakang agar tetap bisa memvalidasi bentrok
        async function loadBackgroundSchedule(){
          const targetDate = dateEl.value || todayStr();
          
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memeriksa ketersediaan...';
          
          const res = await fetchSchedule(room.id, targetDate);
          if (res && res.ok){
            scheduleItems = Array.isArray(res.items) ? res.items : [];
          } else {
            scheduleItems = [];
          }

          validateForm();
        }

        function validateForm(){
          const date = (dateEl.value || '').trim();
          const st = (startEl.value || '').trim();
          const en = (endEl.value || '').trim();

          let invalid = false;
          let msg = "";

          if (!date || !st || !en) {
            invalid = true;
            msg = "Pastikan tanggal dan waktu terisi penuh.";
          } else if (st && en && en <= st) {
            invalid = true;
            msg = "Waktu selesai harus lebih besar dari waktu mulai.";
          }

          // Cek overlap dari data yang difetch
          if (!invalid && st && en){
            for (const it of scheduleItems){
              const s2 = String(it.start_time || '').substring(11,16);
              const e2 = String(it.end_time || '').substring(11,16);
              if (s2 && e2 && isTimeOverlap(st, en, s2, e2)){
                invalid = true;
                msg = `Bentrok dengan jadwal lain (${s2} - ${e2}).`;
                break;
              }
            }
          }

          if (invalid) {
            setConflict(msg);
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Tidak Tersedia';
          } else {
            clearConflict();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Konfirmasi Booking';
          }
          
          return !invalid;
        }

        // Events Listener
        dateEl.addEventListener('change', loadBackgroundSchedule);
        startEl.addEventListener('input', validateForm);
        endEl.addEventListener('input', validateForm);

        // Action Submit
        submitBtn.addEventListener('click', async ()=>{
          if (!validateForm()) return;

          const title = (titleEl.value || '').trim();
          if (!title){
            setConflict('Judul kegiatan wajib diisi.');
            submitBtn.disabled = true;
            setTimeout(() => validateForm(), 1500);
            return;
          }

          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

          const fd = new FormData();
          fd.append('_token', CSRF);
          fd.append('room_id', room.id);
          fd.append('date', dateEl.value);
          fd.append('start', startEl.value);
          fd.append('end', endEl.value);
          fd.append('title', title);
          fd.append('kelas', (kelasEl.value || '').trim());
          fd.append('dosen', (dosenEl.value || '').trim());
          fd.append('source', (sourceEl.value || 'admin'));

          const res = await fetch(URL_CREATE, { method:'POST', body: fd });
          const json = await res.json().catch(()=>({ok:false, message:'Response error/tidak valid'}));

          if (!json.ok){
             setConflict(json.message || 'Gagal menyimpan booking.');
             submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Konfirmasi Booking';
             submitBtn.disabled = false;
             return;
          }

          Swal.fire({
            toast: true, position: 'top-end', timer: 2000, showConfirmButton: false,
            icon: 'success', title: json.message || 'Booking berhasil ditambahkan'
          });

          titleEl.value = '';
          await loadBackgroundSchedule(); // Refresh bentrok data
        });

        // Trigger load pertama kali
        await loadBackgroundSchedule();
      }
    });
  }

  /* ===================== EVENTS ===================== */
  roomListEl.addEventListener('click', (e)=>{
    const card = e.target.closest('.roomRow');
    if (!card) return;

    const room = {
      id: Number(card.dataset.roomId),
      name: card.dataset.roomName || '-',
      floor: card.dataset.roomFloor || '-',
      cap: card.dataset.roomCap || '0',
      fac: card.dataset.roomFac || '-'
    };

    openPopupForRoom(room);
  });
</script>
</body>
</html>