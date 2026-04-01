{{-- resources/views/admin/rooms/index.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Manajemen Ruangan</title>

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
      --danger:#ef4444;
      --ring: 0 0 0 4px rgba(37,99,235,.12);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background:
        radial-gradient(900px 420px at 15% -10%, rgba(37,99,235,.10), transparent 55%),
        radial-gradient(820px 380px at 95% 0%, rgba(245,158,11,.10), transparent 50%),
        var(--bg);
      color:var(--text);
      font-size:13px;
      line-height:1.55;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* Layout */
    .app{display:flex; min-height:100vh;}
    .main{flex:1; min-width:0;}

    /* Topbar (samakan quick-booking) */
    .topbar{
      position:sticky; top:0; z-index:60;
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
      white-space:nowrap;
    }
    .muted{color:var(--muted); font-weight:500; font-size:12px;}
    .container{max-width:1240px; margin:16px auto 30px; padding:0 16px;}

    /* Button (samakan quick-booking) */
    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:9px;
      padding: 10px 12px;
      border-radius: 12px;
      border:1px solid rgba(231,234,243,.95);
      background:#fff;
      color: var(--text);
      text-decoration:none;
      font-weight:600;
      font-size:12px;
      transition:.15s ease;
      cursor:pointer;
      white-space:nowrap;
      box-shadow: 0 8px 18px rgba(2,6,23,.06);
      user-select:none;
    }
    .btn:hover{transform:translateY(-1px); background:#f7f9ff; border-color: rgba(37,99,235,.20);}
    .btn-primary{
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color:#fff; border-color:transparent;
      box-shadow: 0 16px 34px rgba(37,99,235,.18);
    }
    .btn-danger{
      background:#fff;
      border-color: rgba(239,68,68,.25);
      color:#b91c1c;
      box-shadow:none;
    }
    .btn-danger:hover{background:#fff1f2; border-color: rgba(239,68,68,.30);}
    .btn-mini{padding:8px 10px; border-radius:12px; box-shadow:none; font-size:12px;}
    .btn-page{box-shadow:none; padding:8px 10px; border-radius:12px;}
    .btn-page[disabled]{opacity:.55; cursor:not-allowed;}
    .btn-page.active{
      background: rgba(37,99,235,.10);
      border-color: rgba(37,99,235,.20);
      color:#1d4ed8;
      font-weight:700;
    }

    /* Card (samakan quick-booking) */
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
      flex-wrap:wrap;
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

    /* Tools row */
    .tools{
      display:flex; gap:10px; align-items:center; flex-wrap:wrap;
      padding:12px 14px;
      border-bottom:1px solid #f0f2f7;
      background:#fbfcff;
    }
    .field{
      display:flex; align-items:center; gap:8px;
      padding:10px 12px;
      border:1px solid rgba(231,234,243,.95);
      border-radius:14px;
      background:#fff;
      transition:.15s ease;
      min-width: 260px;
      box-shadow: 0 8px 18px rgba(2,6,23,.04);
    }
    .field:focus-within{
      border-color: rgba(37,99,235,.28);
      box-shadow: var(--ring);
    }
    .field i{color:#64748b}
    .field input{
      border:none; outline:none; width:100%;
      font:inherit; font-weight:600; font-size:12.5px;
      background:transparent;
    }
    .spacer{flex:1; min-width:10px;}

    /* Table */
    .tablewrap{width:100%; overflow:auto;}
    table{width:100%; border-collapse:separate; border-spacing:0; min-width:1040px;}
    thead th{
      background:#f8fafc;
      border-bottom:1px solid rgba(231,234,243,.95);
      padding:10px 12px;
      text-align:left;
      font-size:11.5px;
      font-weight:600;
      color:#334155;
      letter-spacing:.2px;
      white-space:nowrap;
      position:sticky; top:0; z-index:5;
    }
    tbody td{
      padding:10px 12px;
      border-bottom:1px solid #f1f5f9;
      vertical-align:middle;
      font-weight:500;
      color:#111827;
      font-size:12.5px;
      white-space:normal;
      word-break:break-word;
      background:#fff;
    }
    tbody tr:hover td{background:#fbfdff;}

    .idpill{
      display:inline-flex; align-items:center; gap:7px;
      padding:6px 8px;
      border-radius:12px;
      border:1px solid rgba(231,234,243,.95);
      background:#fff;
      font-weight:600;
      white-space:nowrap;
    }
    .sub{color:var(--muted); font-weight:400; font-size:11.5px; margin-top:4px;}
    .cap{
      display:inline-flex; align-items:center; gap:7px;
      padding:6px 10px;
      border-radius:999px;
      font-size:11.8px;
      font-weight:600;
      border:1px solid rgba(37,99,235,.18);
      background: rgba(37,99,235,.08);
      color:#1d4ed8;
      white-space:nowrap;
    }
    .thumb{
      width:64px; height:46px;
      border-radius:12px;
      overflow:hidden;
      border:1px solid #e5e7eb;
      background:#f8fafc;
      display:grid; place-items:center;
      color:#94a3b8;
      flex-shrink:0;
    }
    .thumb img{width:100%;height:100%;object-fit:cover;display:block;}
    .actBtns{display:flex; gap:8px; align-items:center; flex-wrap:wrap;}

    /* Footer pager */
    .footerBar{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      padding:12px 14px;
      border-top:1px solid #f0f2f7;
      background:#fff;
      flex-wrap:wrap;
    }
    .pager{display:flex; gap:6px; align-items:center; flex-wrap:wrap;}

    /* SweetAlert base */
    .swal2-popup{ border-radius:18px !important; padding:18px !important; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif !important; }
    .swIn{
      width:100%;
      padding:10px 12px;
      border-radius:14px;
      border:1px solid #e7eaf3;
      font-weight:600;
      font-size:12.5px;
      outline:none;
    }
    .swIn:focus{ border-color: rgba(37,99,235,.35); box-shadow: var(--ring); }

    @media (max-width: 820px){
      .app{flex-direction:column;}
      .topbar{position:relative;}
      .field{min-width: 100%;}
      table{min-width: 1040px;}
    }
  </style>

  <script>
    // sinkron sidebar collapsed
    document.documentElement.classList.remove('sb-collapsed');
  </script>
</head>

<body>
<div class="app">
  {{-- Sidebar tetap dari partial (style global diikuti lewat class html sb-collapsed) --}}
  @include('partials.sidebar')

  <div class="main">
    <header class="topbar">
      <div class="crumb">
        <div class="pill"><i class="fa-solid fa-building"></i> Manajemen Ruangan</div>
        <div class="muted">Kelola data ruangan, foto, kapasitas, dan fasilitas</div>
      </div>

      <div class="crumb">
        <button id="btnAddRoom" class="btn btn-primary" type="button">
          <i class="fa-solid fa-plus"></i> Tambah Ruangan
        </button>
      </div>
    </header>

    <main class="container">

      {{-- toast session --}}
      @if(session('msg'))
        <script>
          document.addEventListener('DOMContentLoaded', ()=>{
            Swal.fire({toast:true, position:'bottom-end', timer:2600, showConfirmButton:false, icon:'success', title:@json(session('msg'))});
          });
        </script>
      @endif
      @if(session('csvMsg'))
        <script>
          document.addEventListener('DOMContentLoaded', ()=>{
            Swal.fire({toast:true, position:'bottom-end', timer:3200, showConfirmButton:false, icon:'success', title:@json(session('csvMsg'))});
          });
        </script>
      @endif
      @if(session('csvErr'))
        <script>
          document.addEventListener('DOMContentLoaded', ()=>{
            Swal.fire({icon:'error', title:'Gagal', text:@json(session('csvErr')), confirmButtonColor:'#2563eb'});
          });
        </script>
      @endif
      @if($errors->any())
        <script>
          document.addEventListener('DOMContentLoaded', ()=>{
            Swal.fire({icon:'error', title:'Validasi gagal', text:@json($errors->first()), confirmButtonColor:'#2563eb'});
          });
        </script>
      @endif

      <section class="card">
        <div class="cardHead">
          <div class="cardTitle">
            <i class="fa-solid fa-list"></i>
            <span>Daftar Ruangan</span>
          </div>

          <div class="muted">
            Total: <span style="font-weight:600" id="totalAll">{{ $rooms->count() }}</span>
            • Ditampilkan: <span style="font-weight:600" id="totalShown">0</span>
          </div>
        </div>

        <div class="tools">
          <div class="field" style="flex:1; min-width:260px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="q" type="text" placeholder="Cari: nama / lantai / fasilitas...">
          </div>

          <div class="spacer"></div>

          <button id="btnImportCsv" class="btn btn-mini" type="button">
            <i class="fa-solid fa-file-csv"></i> Import CSV
          </button>
        </div>

        <div class="tablewrap">
          <table>
            <thead>
              <tr>
                <th style="width:110px;">ID</th>
                <th style="width:110px;">Foto</th>
                <th style="width:110px;">Lantai</th>
                <th style="min-width:220px;">Nama</th>
                <th style="width:140px;">Kapasitas</th>
                <th style="min-width:260px;">Fasilitas</th>
                <th style="width:220px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="roomTbody">
              @forelse($rooms as $room)
                @php
                  $photo = trim((string)($room->photo ?? ''));
                  $photoUrl = null;
                  if ($photo !== '') {
                    if (preg_match('#^https?://#i', $photo)) $photoUrl = $photo;
                    else {
                      $p = ltrim($photo,'/');
                      if (file_exists(public_path($p))) $photoUrl = asset($p);
                      else {
                        foreach (["uploads/rooms/$p","assets/$p","uploads/$p","storage/$p",$p] as $rel){
                          $rel = ltrim($rel,'/');
                          if (file_exists(public_path($rel))) { $photoUrl = asset($rel); break; }
                        }
                      }
                    }
                  }
                @endphp

                <tr class="roomRow"
                    data-id="{{ $room->id }}"
                    data-floor="{{ e($room->floor) }}"
                    data-name="{{ e($room->name) }}"
                    data-capacity="{{ (int)$room->capacity }}"
                    data-facilities="{{ e($room->facilities ?? '') }}"
                    data-photo="{{ e($room->photo ?? '') }}">
                  <td>
                    <span class="idpill"><i class="fa-solid fa-hashtag"></i> {{ $room->id }}</span>
                  </td>

                  <td>
                    <div class="thumb" title="{{ $room->photo ?? '' }}">
                      @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="{{ $room->name }}">
                      @else
                        <i class="fa-regular fa-image"></i>
                      @endif
                    </div>
                  </td>

                  <td><span style="font-weight:600">Lantai {{ $room->floor }}</span></td>

                  <td>
                    <div style="font-weight:700">{{ $room->name }}</div>
                    <div class="sub">{{ $room->photo ? \Illuminate\Support\Str::limit($room->photo, 42) : '—' }}</div>
                  </td>

                  <td>
                    <span class="cap"><i class="fa-solid fa-users"></i> {{ number_format((int)$room->capacity) }} org</span>
                  </td>

                  <td>{{ $room->facilities ?: '—' }}</td>

                  <td>
                    <div class="actBtns">
                      <button type="button" class="btn btn-mini js-edit">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                      </button>

                      <form id="delForm{{ $room->id }}" method="POST" action="{{ route('admin.rooms.destroy', $room) }}" style="display:none;">
                        @csrf
                        @method('DELETE')
                      </form>

                      <button type="button"
                              class="btn btn-mini btn-danger js-delete"
                              data-delete-id="{{ $room->id }}"
                              data-delete-name="{{ e($room->name) }}">
                        <i class="fa-regular fa-trash-can"></i> Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="muted" style="padding:16px;">Belum ada ruangan.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="footerBar">
          <div class="muted" id="pageInfo">—</div>
          <div class="pager">
            <button class="btn btn-page" type="button" id="btnPrev"><i class="fa-solid fa-chevron-left"></i></button>
            <div id="pageNums" class="pager"></div>
            <button class="btn btn-page" type="button" id="btnNext"><i class="fa-solid fa-chevron-right"></i></button>
          </div>
        </div>
      </section>

      {{-- hidden forms --}}
      <form id="roomForm" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="_method" id="roomMethod" value="POST">
        <input type="text" name="floor" id="fFloor">
        <input type="text" name="name" id="fName">
        <input type="number" name="capacity" id="fCap">
        <input type="text" name="facilities" id="fFac">
        <input type="file" name="photo" id="fPhoto" accept="image/*">
      </form>

      <form id="csvForm" method="POST" action="{{ route('admin.rooms.importCsv') }}" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="file" name="csv_file" id="csvFile" accept=".csv">
      </form>

    </main>
  </div>
</div>

<script>
  /* ===================== SIDEBAR COLLAPSE (global) ===================== */
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

  function escapeHtml(s){
    return String(s ?? '').replace(/[&<>"']/g, (m)=>( {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m] ));
  }

  /* ==========================
     ✅ PAGINATION (6/page) + SEARCH
  ========================== */
  const rowsAll = Array.from(document.querySelectorAll('.roomRow'));
  const q = document.getElementById('q');

  const totalShownEl = document.getElementById('totalShown');
  const pageInfoEl = document.getElementById('pageInfo');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const pageNums = document.getElementById('pageNums');

  let state = { query:'', page:1, perPage:6 };

  function clamp(n,a,b){ return Math.max(a, Math.min(b,n)); }

  function getFilteredRows(){
    const val = (state.query || '').toLowerCase().trim();
    if (!val) return rowsAll;

    return rowsAll.filter(tr=>{
      const text = (String(tr.dataset.floor||'') + ' ' + String(tr.dataset.name||'') + ' ' + String(tr.dataset.facilities||'')).toLowerCase();
      return text.includes(val);
    });
  }

  function pageBtn(p, active=false){
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'btn btn-page' + (active ? ' active' : '');
    b.textContent = String(p);
    b.addEventListener('click', ()=>{
      state.page = p;
      applyView();
    });
    return b;
  }

  function dots(){
    const s = document.createElement('span');
    s.className = 'muted';
    s.style.padding = '0 6px';
    s.textContent = '…';
    return s;
  }

  function renderPagination(totalItems){
    const perPage = state.perPage;
    const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
    state.page = clamp(state.page, 1, totalPages);

    btnPrev.disabled = state.page <= 1;
    btnNext.disabled = state.page >= totalPages;

    pageNums.innerHTML = '';
    const maxBtns = 7;

    let start = Math.max(1, state.page - Math.floor(maxBtns/2));
    let end = start + maxBtns - 1;
    if (end > totalPages){
      end = totalPages;
      start = Math.max(1, end - maxBtns + 1);
    }

    if (start > 1){
      pageNums.appendChild(pageBtn(1));
      if (start > 2) pageNums.appendChild(dots());
    }
    for (let p = start; p <= end; p++){
      pageNums.appendChild(pageBtn(p, p === state.page));
    }
    if (end < totalPages){
      if (end < totalPages - 1) pageNums.appendChild(dots());
      pageNums.appendChild(pageBtn(totalPages));
    }

    const from = totalItems === 0 ? 0 : (state.page - 1) * perPage + 1;
    const to = Math.min(totalItems, state.page * perPage);
    pageInfoEl.textContent = `Menampilkan ${from}-${to} dari ${totalItems} (per halaman: ${perPage})`;
  }

  function applyView(){
    const filtered = getFilteredRows();
    const total = filtered.length;

    if (totalShownEl) totalShownEl.textContent = String(total);

    const perPage = state.perPage;
    const totalPages = Math.max(1, Math.ceil(total / perPage));
    state.page = clamp(state.page, 1, totalPages);

    rowsAll.forEach(r=> r.style.display = 'none');

    const startIdx = (state.page - 1) * perPage;
    const endIdx = startIdx + perPage;
    filtered.slice(startIdx, endIdx).forEach(r=> r.style.display = '');

    renderPagination(total);
  }

  q?.addEventListener('input', ()=>{
    state.query = (q.value || '');
    state.page = 1;
    applyView();
  });

  btnPrev?.addEventListener('click', ()=>{
    state.page = Math.max(1, state.page - 1);
    applyView();
  });
  btnNext?.addEventListener('click', ()=>{
    state.page = state.page + 1;
    applyView();
  });

  applyView();

  /* ==========================
     ✅ DELETE (SweetAlert)
  ========================== */
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('.js-delete');
    if (!btn) return;

    const id = btn.getAttribute('data-delete-id');
    const name = btn.getAttribute('data-delete-name') || 'Ruangan';

    Swal.fire({
      title: 'Hapus ruangan?',
      html: `<div style="font-weight:700">${escapeHtml(name)}</div><div style="margin-top:6px; opacity:.85">Aksi ini tidak bisa dibatalkan.</div>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#ef4444',
    }).then((r)=>{
      if (!r.isConfirmed) return;
      const f = document.getElementById('delForm'+id);
      if (f) f.submit();
    });
  });

  /* ==========================
     ✅ ADD / EDIT (SweetAlert modal)
  ========================== */
  document.getElementById('btnAddRoom')?.addEventListener('click', ()=> openRoomModal(null));

  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('.js-edit');
    if (!btn) return;
    const tr = btn.closest('.roomRow');
    if (!tr) return;

    openRoomModal({
      id: tr.dataset.id,
      floor: tr.dataset.floor || '',
      name: tr.dataset.name || '',
      capacity: tr.dataset.capacity || 0,
      facilities: tr.dataset.facilities || '',
      photo: tr.dataset.photo || ''
    });
  });

  function openRoomModal(data){
    const isEdit = !!(data && data.id);

    const form = document.getElementById('roomForm');
    const method = document.getElementById('roomMethod');

    if (isEdit){
      form.action = `{{ url('/admin/rooms') }}/${encodeURIComponent(data.id)}`;
      method.value = 'PUT';
    } else {
      form.action = `{{ route('admin.rooms.store') }}`;
      method.value = 'POST';
    }

    document.getElementById('fFloor').value = isEdit ? (data.floor||'') : '';
    document.getElementById('fName').value  = isEdit ? (data.name||'') : '';
    document.getElementById('fCap').value   = isEdit ? (parseInt(data.capacity||0,10)||0) : 0;
    document.getElementById('fFac').value   = isEdit ? (data.facilities||'') : '';
    document.getElementById('fPhoto').value = '';

    const title = isEdit ? `Edit Ruangan #${data.id}` : 'Tambah Ruangan';

    const photoNote = isEdit && data.photo
      ? `<div style="margin-top:6px; font-size:12px; color:#64748b;">Foto saat ini: <span style="font-weight:600">${escapeHtml(data.photo)}</span><br>Upload foto baru untuk mengganti.</div>`
      : `<div style="margin-top:6px; font-size:12px; color:#64748b;">Upload foto opsional.</div>`;

    Swal.fire({
      title,
      html: `
        <div style="display:grid; gap:10px; text-align:left;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <div style="font-weight:600; font-size:12px; margin-bottom:6px;">Lantai <span style="color:#ef4444">*</span></div>
              <input id="swFloor" class="swIn" type="text" placeholder="Contoh: 1" value="${escapeHtml(isEdit?data.floor:'')}">
            </div>
            <div>
              <div style="font-weight:600; font-size:12px; margin-bottom:6px;">Nama Ruangan <span style="color:#ef4444">*</span></div>
              <input id="swName" class="swIn" type="text" placeholder="Contoh: R.101" value="${escapeHtml(isEdit?data.name:'')}">
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <div style="font-weight:600; font-size:12px; margin-bottom:6px;">Kapasitas</div>
              <input id="swCap" class="swIn" type="number" min="0" value="${escapeHtml(String(isEdit?data.capacity:0))}">
            </div>
            <div>
              <div style="font-weight:600; font-size:12px; margin-bottom:6px;">Fasilitas</div>
              <input id="swFac" class="swIn" type="text" placeholder="AC, Proyektor..." value="${escapeHtml(isEdit?data.facilities:'')}">
            </div>
          </div>

          <div>
            <div style="font-weight:600; font-size:12px; margin-bottom:6px;">Foto (jpg/png/webp)</div>
            <input id="swPhoto" type="file" accept="image/*" style="width:100%; font-size:12px;">
            ${photoNote}
          </div>
        </div>
      `,
      showCancelButton: true,
      confirmButtonText: isEdit ? 'Update' : 'Simpan',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2563eb',
      preConfirm: () => {
        const floor = (document.getElementById('swFloor').value || '').trim();
        const name  = (document.getElementById('swName').value || '').trim();
        const cap   = (document.getElementById('swCap').value || '0').trim();
        const fac   = (document.getElementById('swFac').value || '').trim();
        const file  = document.getElementById('swPhoto').files[0] || null;

        if (!floor || !name){
          Swal.showValidationMessage('Lantai & Nama wajib diisi.');
          return false;
        }
        const capNum = parseInt(cap, 10);
        if (Number.isNaN(capNum) || capNum < 0){
          Swal.showValidationMessage('Kapasitas harus angka >= 0');
          return false;
        }

        document.getElementById('fFloor').value = floor;
        document.getElementById('fName').value  = name;
        document.getElementById('fCap').value   = capNum;
        document.getElementById('fFac').value   = fac;

        if (file){
          const dt = new DataTransfer();
          dt.items.add(file);
          document.getElementById('fPhoto').files = dt.files;
        } else {
          document.getElementById('fPhoto').value = '';
        }
        return true;
      }
    }).then((r)=>{
      if (r.isConfirmed) form.submit();
    });
  }

  /* ==========================
     ✅ Import CSV (SweetAlert)
  ========================== */
  document.getElementById('btnImportCsv')?.addEventListener('click', ()=>{
    const csvForm = document.getElementById('csvForm');
    const csvFile = document.getElementById('csvFile');
    csvFile.value = '';

    Swal.fire({
      title: 'Import CSV',
      html: `
        <div style="text-align:left; line-height:1.65;">
          <div style="font-weight:600; margin-bottom:8px;">Format kolom:</div>
          <div style="font-family:ui-monospace, SFMono-Regular, Menlo, monospace; font-size:12px; background:#f8fafc; border:1px solid #e7eaf3; padding:10px; border-radius:14px;">
            floor, name, capacity, facilities, photo
          </div>
          <div style="margin-top:10px;">
            <input id="swCsv" type="file" accept=".csv" style="width:100%; font-size:12px;">
          </div>
        </div>
      `,
      showCancelButton: true,
      confirmButtonText: 'Import',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2563eb',
      preConfirm: ()=>{
        const f = document.getElementById('swCsv').files[0] || null;
        if (!f){
          Swal.showValidationMessage('Pilih file CSV dulu.');
          return false;
        }
        if (!String(f.name||'').toLowerCase().endsWith('.csv')){
          Swal.showValidationMessage('File harus .csv');
          return false;
        }
        const dt = new DataTransfer();
        dt.items.add(f);
        csvFile.files = dt.files;
        return true;
      }
    }).then((r)=>{
      if (r.isConfirmed) csvForm.submit();
    });
  });
</script>

</body>
</html>