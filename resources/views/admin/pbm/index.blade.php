{{-- resources/views/admin/pbm/index.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin - Jadwal PBM</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --bg:#f5f7fb;
      --card:#ffffff;
      --text:#0f172a;
      --muted:#64748b;
      --line:#e6edf7;
      --soft:#f8fbff;
      --blue:#2563eb;
      --blue2:#1d4ed8;
      --green:#10b981;
      --amber:#f59e0b;
      --red:#ef4444;
      --shadow:0 16px 38px rgba(15,23,42,.06);
      --radius:20px;
      --fs-xs:11px;
      --fs-sm:12px;
      --fs-md:13px;
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family:"Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
      background:var(--bg);
      color:var(--text);
      font-size:var(--fs-md);
      line-height:1.6;
    }

    .app{display:flex;min-height:100vh}
    .main{flex:1;min-width:0}

    .topbar{
      position:sticky; top:0; z-index:40;
      backdrop-filter:blur(16px);
      background:rgba(245,247,251,.86);
      border-bottom:1px solid var(--line);
      padding:14px 18px;
      display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
    }
    .titleWrap{display:flex; align-items:center; gap:10px; flex-wrap:wrap}
    .titlePill{
      display:inline-flex; align-items:center; gap:10px;
      padding:10px 14px;
      background:rgba(37,99,235,.10);
      border:1px solid rgba(37,99,235,.18);
      color:var(--blue2);
      border-radius:999px;
      font-weight:800;
      font-size:var(--fs-sm);
    }
    .subText{color:var(--muted); font-size:var(--fs-sm)}

    .container{
      max-width:1320px;
      margin:18px auto 30px;
      padding:0 16px;
    }
    .stack{display:grid; gap:16px}

    .card{
      background:var(--card);
      border:1px solid var(--line);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      overflow:hidden;
      min-width:0;
    }
    .cardHead{
      padding:16px 18px;
      display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
      border-bottom:1px solid var(--line);
      background:#fff;
    }
    .cardTitle{
      display:flex; align-items:center; gap:10px;
      font-weight:800;
      font-size:14px;
    }
    .cardIcon{
      width:40px; height:40px; border-radius:14px;
      display:grid; place-items:center;
      background:rgba(37,99,235,.08);
      border:1px solid rgba(37,99,235,.16);
      color:var(--blue2);
      flex:0 0 auto;
    }
    .cardBody{padding:16px 18px}

    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      padding:10px 12px;
      border-radius:14px;
      border:1px solid var(--line);
      background:#fff;
      color:var(--text);
      font-weight:800;
      font-size:var(--fs-sm);
      cursor:pointer;
      transition:.14s ease;
      text-decoration:none;
      white-space:nowrap;
    }
    .btn:hover{transform:translateY(-1px); background:#f8fbff}
    .btn:disabled{opacity:.55; cursor:not-allowed; transform:none !important}
    .btn-primary{
      color:#fff;
      border-color:transparent;
      background:linear-gradient(135deg,var(--blue),var(--blue2));
      box-shadow:0 14px 28px rgba(37,99,235,.18);
    }
    .btn-danger{
      color:#b91c1c;
      background:#fff;
      border-color:rgba(239,68,68,.24);
    }
    .btn-danger:hover{background:#fff5f5}
    .btn-mini{
      padding:8px 10px;
      font-size:var(--fs-xs);
      border-radius:12px;
      box-shadow:none;
    }

    .toolbar{
      display:grid;
      grid-template-columns: 180px 180px 1fr auto;
      gap:10px;
      padding:14px 18px;
      border-bottom:1px solid var(--line);
      background:var(--soft);
      align-items:center;
    }
    .field{
      display:flex; align-items:center; gap:8px;
      border:1px solid var(--line);
      border-radius:14px;
      background:#fff;
      padding:10px 12px;
      min-width:0;
    }
    .field i{color:#64748b; font-size:12px}
    .field input,.field select{
      border:none; outline:none; width:100%;
      background:transparent; font:inherit; color:inherit;
      min-width:0;
    }
    .toolbarRight{
      display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;
    }

    .stats{
      display:grid;
      grid-template-columns:repeat(4,minmax(0,1fr));
      gap:10px;
    }
    .stat{
      border:1px solid var(--line);
      background:#fff;
      border-radius:16px;
      padding:13px 14px;
    }
    .stat .label{
      display:block;
      color:var(--muted);
      font-size:var(--fs-xs);
      margin-bottom:5px;
    }
    .stat .value{
      font-size:20px;
      font-weight:800;
      line-height:1.1;
    }

    .tableScroll,.templateScroll{
      border:1px solid var(--line);
      border-radius:16px;
      overflow:auto;
      background:#fff;
    }
    .tableScroll{max-height:560px;}
    .templateScroll{max-height:440px;}

    .tableScroll::-webkit-scrollbar,
    .templateScroll::-webkit-scrollbar{
      width:10px;
      height:10px;
    }
    .tableScroll::-webkit-scrollbar-thumb,
    .templateScroll::-webkit-scrollbar-thumb{
      background:#cfd8e3;
      border-radius:999px;
    }
    .tableScroll::-webkit-scrollbar-track,
    .templateScroll::-webkit-scrollbar-track{
      background:#f3f6fb;
      border-radius:999px;
    }

    table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      min-width:1180px;
      background:#fff;
    }
    thead th{
      position:sticky; top:0; z-index:2;
      background:#f8fafc;
      color:#334155;
      border-bottom:1px solid var(--line);
      padding:12px 14px;
      text-align:left;
      font-size:10.5px;
      letter-spacing:.11em;
      text-transform:uppercase;
      font-weight:800;
      white-space:nowrap;
    }
    tbody td{
      border-bottom:1px solid #f1f5f9;
      padding:14px;
      vertical-align:top;
      font-size:var(--fs-sm);
      color:#111827;
      background:#fff;
    }
    tbody tr:hover td{background:#fcfdff}

    .badge{
      display:inline-flex; align-items:center; gap:7px;
      padding:6px 10px;
      border-radius:999px;
      font-size:var(--fs-xs);
      font-weight:800;
      border:1px solid var(--line);
      background:#fff;
      white-space:nowrap;
    }
    .badge.ok{
      border-color:rgba(16,185,129,.22);
      background:rgba(16,185,129,.10);
      color:#047857;
    }
    .badge.warn{
      border-color:rgba(245,158,11,.24);
      background:rgba(245,158,11,.12);
      color:#92400e;
    }
    .badge.info{
      border-color:rgba(37,99,235,.22);
      background:rgba(37,99,235,.08);
      color:#1d4ed8;
    }

    .rowActions{
      display:flex;
      gap:8px;
      flex-wrap:wrap;
      min-width:260px;
    }

    .meta{color:var(--muted); font-size:var(--fs-xs)}
    .footerBar{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      padding:12px 18px;
      border-top:1px solid var(--line);
      background:#fff;
      flex-wrap:wrap;
    }

    details.clean{border-top:1px solid var(--line);}
    details.clean > summary{
      list-style:none;
      cursor:pointer;
      user-select:none;
      padding:14px 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      background:#fff;
    }
    details.clean > summary::-webkit-details-marker{display:none}
    .summaryLeft{display:flex; align-items:center; gap:10px}
    .summaryNote{color:var(--muted); font-size:var(--fs-sm)}

    .helper{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      border:1px dashed #dbe4f0;
      background:#f8fafc;
      border-radius:14px;
      padding:10px 12px;
      margin-bottom:10px;
      line-height:1.7;
    }
    .helper code{font-weight:700}

    .swal2-popup{border-radius:20px !important; padding:18px !important}
    .swal2-title{font-size:14px !important; font-weight:800 !important}
    .swal2-html-container{font-size:12px !important}
    .swal2-input,.swal2-select,.swal2-textarea{
      border-radius:12px !important;
      border:1px solid #e5eaf2 !important;
      box-shadow:none !important;
      font-size:12px !important;
    }
    .swal2-input,.swal2-select{height:40px !important}
    .swal2-input:focus,.swal2-select:focus,.swal2-textarea:focus{
      border-color:rgba(37,99,235,.35) !important;
      box-shadow:0 0 0 4px rgba(37,99,235,.10) !important;
    }
    .swal-wide{width:980px !important; max-width:calc(100vw - 24px) !important}
    .swal-scroll{max-height:70vh; overflow:auto; padding-right:4px}

    @media (max-width:1100px){
      .toolbar{grid-template-columns:1fr 1fr;}
      .toolbarRight{grid-column:1 / -1; justify-content:flex-start;}
    }
    @media (max-width:860px){
      .app{flex-direction:column}
      .topbar{position:relative}
      .stats{grid-template-columns:1fr 1fr}
      .toolbar{grid-template-columns:1fr}
    }
    @media (max-width:560px){
      .stats{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <div class="app">
    @include('partials.sidebar')

    <div class="main">
      <header class="topbar">
        <div class="titleWrap">
          <div class="titlePill"><i class="fa-solid fa-calendar-days"></i> Jadwal PBM</div>
          <div class="subText">Kelola occurrence harian dan template mingguan. Template tetap, occurrence bisa dipindah per tanggal.</div>
        </div>

        <button class="btn" type="button" onclick="openPBMHelp()">
          <i class="fa-solid fa-circle-question"></i> Info
        </button>
      </header>

      <main class="container">
        <div class="stack">

          <section class="card">
            <div class="cardHead">
              <div>
                <div class="cardTitle">
                  <div class="cardIcon"><i class="fa-solid fa-calendar-check"></i></div>
                  <span>Jadwal PBM Harian</span>
                </div>
                <div class="subText" style="margin-top:4px;">
                  Reschedule hanya untuk occurrence tanggal tertentu, template tetap.
                </div>
              </div>
            </div>

            <div class="cardBody">
              <div class="stats">
                <div class="stat">
                  <span class="label">Total Jadwal</span>
                  <div class="value" id="statTotal">0</div>
                </div>
                <div class="stat">
                  <span class="label">Approved</span>
                  <div class="value" id="statApproved">0</div>
                </div>
                <div class="stat">
                  <span class="label">Draft</span>
                  <div class="value" id="statDraft">0</div>
                </div>
                <div class="stat">
                  <span class="label">Terlihat</span>
                  <div class="value" id="selectedEventsCount">0</div>
                </div>
              </div>
            </div>

            <div class="toolbar">
              <div class="field">
                <i class="fa-solid fa-calendar-day"></i>
                <input id="evDate" type="date">
              </div>

              <div class="field">
                <i class="fa-solid fa-door-open"></i>
                <select id="evRoom">
                  <option value="">Semua Ruangan</option>
                </select>
              </div>

              <div class="field">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="evQ" type="text" placeholder="Cari mata kuliah, kelas, dosen, atau ruangan">
              </div>

              <div class="toolbarRight">
                <div class="field" style="min-width:170px; flex:0 0 auto;">
                  <i class="fa-solid fa-filter"></i>
                  <select id="evStatus">
                    <option value="all" selected>Semua Status</option>
                    <option value="approved">Approved</option>
                    <option value="draft">Draft</option>
                  </select>
                </div>

                <button class="btn" type="button" onclick="loadEvents()">
                  <i class="fa-solid fa-rotate"></i> Refresh
                </button>
                <button class="btn btn-danger" type="button" onclick="deleteAllEvents()">
                  <i class="fa-solid fa-trash-can"></i> Hapus Semua Jadwal
                </button>
              </div>
            </div>

            <div class="cardBody" style="padding-top:10px;">
              <div class="tableScroll">
                <table>
                  <thead>
                    <tr>
                      <th style="width:52px; text-align:center;">#</th>
                      <th style="width:130px;">Tanggal</th>
                      <th style="width:190px;">Ruangan</th>
                      <th style="width:150px;">Jam</th>
                      <th>Detail PBM</th>
                      <th style="width:140px;">Status</th>
                      <th style="width:290px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tbEvents">
                    <tr>
                      <td colspan="7" style="text-align:center; padding:28px;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="footerBar">
              <div class="meta">Tanggal aktif: <b id="dateLabel">-</b></div>
              <div class="meta">Jadwal yang dipindah hanya berlaku pada kejadian tersebut. Minggu berikutnya tetap normal dari template.</div>
            </div>
          </section>

          <section class="card">
            <details class="clean">
              <summary>
                <div class="summaryLeft">
                  <div class="cardIcon"><i class="fa-solid fa-layer-group"></i></div>
                  <div>
                    <div style="font-weight:800; font-size:13px;">Template Jadwal Mingguan</div>
                    <div class="summaryNote">Sumber jadwal rutin PBM.</div>
                  </div>
                </div>
                <div class="summaryNote">Buka / Tutup</div>
              </summary>

              <div class="cardBody" style="padding-top:0;">
                <div class="helper">
                  <div style="font-size:12px; color:#334155;">
                  </div>
                  <button class="btn btn-mini" type="button" onclick="downloadCSVSamplePBM()">
                    <i class="fa-solid fa-download"></i> Download Sample
                  </button>
                </div>
              </div>

              <div class="toolbar" style="border-top:1px solid var(--line);">
                <div class="field">
                  <i class="fa-solid fa-door-open"></i>
                  <select id="fRoom">
                    <option value="">Semua Ruangan</option>
                  </select>
                </div>

                <div class="field">
                  <i class="fa-solid fa-toggle-on"></i>
                  <select id="fAktif">
                    <option value="">Semua</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                  </select>
                </div>

                <div class="field">
                  <i class="fa-solid fa-magnifying-glass"></i>
                  <input id="fQ" type="text" placeholder="Cari template mingguan">
                </div>

                <div class="toolbarRight">
                  <button class="btn btn-primary" type="button" onclick="openCSVUploadPBM()">
                    <i class="fa-solid fa-upload"></i> Upload CSV
                  </button>
                  <button class="btn" type="button" onclick="openTemplateManager()">
                    <i class="fa-solid fa-gear"></i> Kelola Template
                  </button>
                  <button class="btn btn-danger" type="button" onclick="deleteAllTemplates()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Semua Template
                  </button>
                  <button class="btn" type="button" onclick="loadTemplates()">
                    <i class="fa-solid fa-rotate"></i> Refresh Template
                  </button>
                </div>
              </div>

              <div class="cardBody" style="padding-top:10px;">
                <div class="templateScroll">
                  <table>
                    <thead>
                      <tr>
                        <th style="width:90px;">Hari</th>
                        <th style="width:190px;">Ruangan</th>
                        <th style="width:130px;">Jam</th>
                        <th>Detail Template</th>
                        <th style="width:120px;">Status</th>
                        <th style="width:180px;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="tbTmpl">
                      <tr>
                        <td colspan="6" style="text-align:center; padding:28px;">
                          <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="footerBar">
                <div class="meta">Template mingguan membentuk occurrence saat tanggal dibuka.</div>
                <div class="meta">Occurrence bisa dipindah per tanggal tanpa mengubah template.</div>
              </div>
            </details>
          </section>

        </div>
      </main>
    </div>
  </div>

<script>
const $ = (id) => document.getElementById(id);
const CSRF = @json(csrf_token());

const URL_ROOMS           = @json(route('admin.pbm.rooms'));
const URL_TMPL_LIST       = @json(route('admin.pbm.templates'));
const URL_TMPL_GET        = @json(route('admin.pbm.template.get', ['id'=>999999]));
const URL_TMPL_SAVE       = @json(route('admin.pbm.template.save'));
const URL_TMPL_DELETE     = @json(route('admin.pbm.template.delete', ['id'=>999999]));
const URL_TMPL_DELETE_ALL = @json(route('admin.pbm.template.delete_all'));
const URL_TMPL_UPLOAD_CSV = @json(route('admin.pbm.templates.upload_csv'));
const URL_TMPL_SAMPLE_CSV = @json(route('admin.pbm.templates.sample_csv'));

const URL_EV_LIST         = @json(route('admin.pbm.events'));
const URL_EV_RESCHEDULE   = @json(route('admin.pbm.events.reschedule', ['id'=>999999]));
const URL_EV_DELETE       = @json(route('admin.pbm.events.delete', ['id'=>999999]));
const URL_EV_DELETE_ALL   = @json(route('admin.pbm.events.delete_all'));

let ROOMS = [];
let EVENTS = [];
let TEMPLATES = [];

function urlWithId(tpl, id){ return tpl.replace('999999', String(id)); }

async function api(url, opt = {}) {
  try {
    const r = await fetch(url, opt);
    return await r.json();
  } catch (e) {
    console.error(e);
    return { ok:false, message:'Network error' };
  }
}

function escapeHtml(s){
  return String(s ?? '').replace(/[&<>"']/g, m => (
    {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]
  ));
}

function pad2(x){ return String(x).padStart(2,'0'); }
function hmFromDT(dt){ return String(dt || '').substring(11,16); }
function tShort(t){ return String(t || '').substring(0,5); }

function toast(icon, title){
  return Swal.fire({
    toast:true,
    position:'bottom-end',
    timer:2600,
    showConfirmButton:false,
    icon,
    title,
    heightAuto:false
  });
}

function debounce(fn, ms=350){
  let t = null;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), ms);
  };
}

function statusBadge(st){
  st = String(st || '').toLowerCase();
  if (st === 'approved') return `<span class="badge ok"><i class="fa-solid fa-circle-check"></i> Approved</span>`;
  if (st === 'draft') return `<span class="badge warn"><i class="fa-solid fa-hourglass-half"></i> Draft</span>`;
  if (st === 'rescheduled') return `<span class="badge info"><i class="fa-solid fa-arrow-right-arrow-left"></i> Dipindah</span>`;
  if (st === 'cancelled') return `<span class="badge warn"><i class="fa-solid fa-ban"></i> Dibatalkan</span>`;
  return `<span class="badge info"><i class="fa-solid fa-circle"></i> ${escapeHtml(st || '-')}</span>`;
}

function tmplBadge(aktif){
  return Number(aktif) === 1
    ? `<span class="badge ok"><i class="fa-solid fa-circle-check"></i> Aktif</span>`
    : `<span class="badge warn"><i class="fa-solid fa-circle"></i> Nonaktif</span>`;
}

function updateStats(rows){
  $('statTotal').textContent = rows.length;
  $('statApproved').textContent = rows.filter(x => String(x.status).toLowerCase() === 'approved').length;
  $('statDraft').textContent = rows.filter(x => String(x.status).toLowerCase() === 'draft').length;
}

function updateSelectedEventsCount(){
  $('selectedEventsCount').textContent = EVENTS.length;
}

function roomOptionsHtml(selectedId){
  return (ROOMS || []).map(r => {
    const val = String(r.id);
    const label = `${escapeHtml(r.floor)} - ${escapeHtml(r.name)}`;
    return `<option value="${val}" ${String(selectedId || '') === val ? 'selected' : ''}>${label}</option>`;
  }).join('');
}

function eventFormHtml(item){
  const it = item || {};
  return `
    <div class="swal-scroll" style="text-align:left">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Tanggal</label>
          <input id="m_date" type="date" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(it.occ_date || '')}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Ruangan</label>
          <select id="m_room_id" class="swal2-select" style="width:100%;margin-top:0;">
            ${roomOptionsHtml(it.room_id)}
          </select>
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Jam Mulai</label>
          <input id="m_start" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(hmFromDT(it.start_time || ''))}" placeholder="08:00">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Jam Selesai</label>
          <input id="m_end" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(hmFromDT(it.end_time || ''))}" placeholder="10:00">
        </div>
      </div>

      <div class="meta" style="margin-top:10px;">
        Sistem akan cek bentrok ke PBM lain, booking mahasiswa, dan quick booking.
      </div>
    </div>
  `;
}

function tmplFormHtml(item){
  const it = item || {};
  const DAY_ORDER = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
  const DAY_LABEL = { senin:'Senin', selasa:'Selasa', rabu:'Rabu', kamis:'Kamis', jumat:'Jumat', sabtu:'Sabtu', minggu:'Minggu' };
  const hari = String(it.hari || 'senin').toLowerCase();

  return `
    <div class="swal-scroll" style="text-align:left">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Ruangan</label>
          <select id="m_room_id_t" class="swal2-select" style="width:100%;margin-top:0;">
            ${roomOptionsHtml(it.room_id)}
          </select>
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Hari</label>
          <select id="m_hari_t" class="swal2-select" style="width:100%;margin-top:0;">
            ${DAY_ORDER.map(d => `<option value="${d}" ${d===hari?'selected':''}>${DAY_LABEL[d]}</option>`).join('')}
          </select>
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Jam Mulai</label>
          <input id="m_start_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(tShort(it.start_time || ''))}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Jam Selesai</label>
          <input id="m_end_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(tShort(it.end_time || ''))}">
        </div>

        <div style="grid-column:1/-1">
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Mata Kuliah</label>
          <input id="m_mk_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(it.mata_kuliah || '')}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Kelas</label>
          <input id="m_kelas_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(it.kelas || '')}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Dosen</label>
          <input id="m_dosen_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(it.dosen || '')}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Semester</label>
          <input id="m_semester_t" class="swal2-input" style="margin:0; width:100%" value="${escapeHtml(it.semester || '')}">
        </div>

        <div>
          <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">Aktif</label>
          <select id="m_aktif_t" class="swal2-select" style="width:100%;margin-top:0;">
            <option value="1" ${String(it.aktif ?? 1)==='1'?'selected':''}>Aktif</option>
            <option value="0" ${String(it.aktif ?? 1)==='0'?'selected':''}>Nonaktif</option>
          </select>
        </div>
      </div>
    </div>
  `;
}

function openPBMHelp(){
  Swal.fire({
    icon:'info',
    title:'Info Jadwal PBM',
    html: `
      <div class="swal-scroll" style="text-align:left; line-height:1.8">
        <b>Template</b> adalah jadwal rutin mingguan.<br><br>
        <b>Occurrence</b> adalah kejadian jadwal pada tanggal tertentu.<br><br>
        Saat occurrence dipindah, <b>template tetap</b>. Jadi minggu berikutnya jadwal kembali normal dari template.
      </div>
    `,
    confirmButtonColor:'#2563eb',
    heightAuto:false
  });
}

/* ===================== ROOMS ===================== */
async function loadRooms(){
  const j = await api(URL_ROOMS, { cache:'no-store' });
  if (!j.ok) return;

  ROOMS = j.items || [];
  const opts = ROOMS.map(x => `<option value="${x.id}">${escapeHtml(x.floor)} - ${escapeHtml(x.name)}</option>`).join('');
  $('evRoom').innerHTML = `<option value="">Semua Ruangan</option>` + opts;
  $('fRoom').innerHTML = `<option value="">Semua Ruangan</option>` + opts;
}

/* ===================== EVENTS ===================== */
async function loadEvents(){
  const d = ($('evDate').value || '').trim();
  $('dateLabel').textContent = d || '-';

  if (!d){
    $('tbEvents').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:28px;">Pilih tanggal terlebih dahulu</td></tr>`;
    EVENTS = [];
    updateStats([]);
    updateSelectedEventsCount();
    return;
  }

  $('tbEvents').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:28px;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>`;

  const params = new URLSearchParams({
    date: d,
    status: $('evStatus').value || 'all',
    q: ($('evQ').value || '').trim()
  });

  const j = await api(URL_EV_LIST + '?' + params.toString(), { cache:'no-store' });
  if (!j.ok){
    $('tbEvents').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:28px; color:#dc2626;">${escapeHtml(j.message || 'Gagal memuat jadwal')}</td></tr>`;
    EVENTS = [];
    updateStats([]);
    updateSelectedEventsCount();
    return;
  }

  let rows = j.items || [];
  const roomFilter = $('evRoom').value || '';
  if (roomFilter) {
    rows = rows.filter(r => String(r.room_id) === String(roomFilter));
  }

  EVENTS = rows;
  updateStats(rows);

  if (rows.length === 0){
    $('tbEvents').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:26px;">Tidak ada jadwal PBM untuk filter ini.</td></tr>`;
    updateSelectedEventsCount();
    return;
  }

  $('tbEvents').innerHTML = rows.map((r, idx) => {
    const room = `${escapeHtml(r.room_floor)} - ${escapeHtml(r.room_name)}`;
    const jam = `${hmFromDT(r.start_time)} - ${hmFromDT(r.end_time)}`;

    return `
      <tr>
        <td style="text-align:center;">${idx + 1}</td>
        <td>${escapeHtml(r.occ_date)}</td>
        <td>${room}</td>
        <td><span class="badge info"><i class="fa-solid fa-clock"></i> ${jam}</span></td>
        <td>
          <div style="font-weight:800; margin-bottom:4px;">${escapeHtml(r.mata_kuliah || '-')}</div>
          <div class="meta">
            Kelas: <b>${escapeHtml(r.kelas || '-')}</b> • Dosen: <b>${escapeHtml(r.dosen || '-')}</b> • Semester: <b>${escapeHtml(r.semester || '-')}</b>
          </div>
        </td>
        <td>${statusBadge(r.status)}</td>
        <td>
          <div class="rowActions">
            <button class="btn btn-mini btn-primary" type="button" onclick="openRescheduleEvent(${r.id})">
              <i class="fa-solid fa-arrow-right-arrow-left"></i> Reschedule
            </button>
            <button class="btn btn-mini btn-danger" type="button" onclick="deleteEvent(${r.id})">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');

  updateSelectedEventsCount();
}

function getEventById(id){
  return (EVENTS || []).find(x => String(x.id) === String(id)) || null;
}

async function openRescheduleEvent(id){
  const item = getEventById(id);
  if (!item) return toast('error', 'Data event tidak ditemukan');

  const res = await Swal.fire({
    title:'Reschedule Jadwal PBM',
    customClass:{ popup:'swal-wide' },
    html: eventFormHtml(item),
    showCancelButton:true,
    confirmButtonText:'Pindahkan',
    cancelButtonText:'Batal',
    confirmButtonColor:'#2563eb',
    heightAuto:false,
    preConfirm: async () => {
      const date = document.getElementById('m_date')?.value || '';
      const room_id = document.getElementById('m_room_id')?.value || '';
      const start_hm = (document.getElementById('m_start')?.value || '').trim();
      const end_hm = (document.getElementById('m_end')?.value || '').trim();

      if (!date || !room_id || !start_hm || !end_hm) {
        Swal.showValidationMessage('Tanggal, ruangan, jam mulai, dan jam selesai wajib diisi.');
        return false;
      }

      const fd = new FormData();
      fd.append('_token', CSRF);
      fd.append('date', date);
      fd.append('room_id', room_id);
      fd.append('start_hm', start_hm);
      fd.append('end_hm', end_hm);

      const j = await api(urlWithId(URL_EV_RESCHEDULE, id), { method:'POST', body: fd });
      if (!j.ok) {
        Swal.showValidationMessage(j.message || 'Gagal reschedule');
        return false;
      }
      return j;
    }
  });

  if (res.isConfirmed && res.value) {
    toast('success', res.value.message || 'Jadwal berhasil dipindahkan');
    await loadEvents();
  }
}

async function deleteEvent(id){
  const ok = await Swal.fire({
    icon:'warning',
    title:'Sembunyikan jadwal ini?',
    text:'Occurrence pada tanggal ini akan dibatalkan, template tetap.',
    showCancelButton:true,
    confirmButtonText:'Sembunyikan',
    cancelButtonText:'Batal',
    confirmButtonColor:'#ef4444',
    heightAuto:false
  });
  if (!ok.isConfirmed) return;

  const fd = new FormData();
  fd.append('_token', CSRF);
  fd.append('_method', 'DELETE');

  const j = await api(urlWithId(URL_EV_DELETE, id), { method:'POST', body: fd });
  if (!j.ok) return toast('error', j.message || 'Gagal hapus');

  toast('success', j.message || 'Jadwal disembunyikan');
  await loadEvents();
}

async function deleteAllEvents(){
  const d = ($('evDate').value || '').trim();
  if (!d) {
    return Swal.fire({
      icon:'info',
      title:'Tanggal belum dipilih',
      text:'Pilih tanggal dulu.',
      confirmButtonColor:'#2563eb',
      heightAuto:false
    });
  }

  const ok = await Swal.fire({
    icon:'warning',
    title:'Sembunyikan semua jadwal tanggal ini?',
    html:`Tanggal: <b>${escapeHtml(d)}</b><br>Template tetap, occurrence tanggal ini akan dibatalkan.`,
    showCancelButton:true,
    confirmButtonText:'Sembunyikan Semua',
    cancelButtonText:'Batal',
    confirmButtonColor:'#ef4444',
    heightAuto:false
  });
  if (!ok.isConfirmed) return;

  const fd = new FormData();
  fd.append('_token', CSRF);
  fd.append('date', d);

  const j = await api(URL_EV_DELETE_ALL, { method:'POST', body: fd });
  if (!j.ok) return toast('error', j.message || 'Gagal hapus semua jadwal');

  toast('success', j.message || `Berhasil ubah ${j.deleted || 0} jadwal`);
  await loadEvents();
}

/* ===================== TEMPLATE ===================== */
async function loadTemplates(){
  $('tbTmpl').innerHTML = `<tr><td colspan="6" style="text-align:center; padding:28px;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>`;

  const params = new URLSearchParams({
    room_id: $('fRoom').value || '',
    aktif: $('fAktif').value || '',
    q: ($('fQ').value || '').trim()
  });

  const j = await api(URL_TMPL_LIST + '?' + params.toString(), { cache:'no-store' });
  if (!j.ok){
    $('tbTmpl').innerHTML = `<tr><td colspan="6" style="text-align:center; padding:28px; color:#dc2626;">${escapeHtml(j.message || 'Gagal memuat template')}</td></tr>`;
    return;
  }

  const rows = j.items || [];
  TEMPLATES = rows;

  if (rows.length === 0){
    $('tbTmpl').innerHTML = `<tr><td colspan="6" style="text-align:center; padding:26px;">Belum ada template.</td></tr>`;
    return;
  }

  const dayLabel = {
    senin:'Senin', selasa:'Selasa', rabu:'Rabu', kamis:'Kamis',
    jumat:'Jumat', sabtu:'Sabtu', minggu:'Minggu'
  };

  $('tbTmpl').innerHTML = rows.map(r => {
    const room = `${escapeHtml(r.room_floor)} - ${escapeHtml(r.room_name)}`;
    const jam = `${tShort(r.start_time)} - ${tShort(r.end_time)}`;

    return `
      <tr>
        <td>${escapeHtml(dayLabel[String(r.hari || '').toLowerCase()] || r.hari || '-')}</td>
        <td>${room}</td>
        <td><span class="badge info"><i class="fa-solid fa-clock"></i> ${jam}</span></td>
        <td>
          <div style="font-weight:800; margin-bottom:3px;">${escapeHtml(r.mata_kuliah || '-')}</div>
          <div class="meta">
            Kelas: <b>${escapeHtml(r.kelas || '-')}</b> • Dosen: <b>${escapeHtml(r.dosen || '-')}</b> • Semester: <b>${escapeHtml(r.semester || '-')}</b>
          </div>
        </td>
        <td>${tmplBadge(r.aktif)}</td>
        <td>
          <div class="rowActions">
            <button class="btn btn-mini" type="button" onclick="editTemplate(${r.id})">
              <i class="fa-solid fa-pen"></i> Edit
            </button>
            <button class="btn btn-mini btn-danger" type="button" onclick="deleteTemplate(${r.id})">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

function downloadCSVSamplePBM(){
  window.location.href = URL_TMPL_SAMPLE_CSV;
}

function openCSVUploadPBM(){
  Swal.fire({
    title: 'Upload Jadwal PBM (CSV)',
    customClass: { popup: 'swal-wide' },
    html: `
      <div class="swal-scroll" style="text-align:left; line-height:1.75">
        <div class="helper" style="margin-bottom:12px;">
          <div style="font-size:12px; color:#334155;">
            Header: <code>room_id,hari,start_time,end_time,mata_kuliah,kelas,dosen,semester,aktif</code><br>
            <span class="meta">Template masuk ke pbm_templates. Occurrence dibentuk otomatis saat tanggal dibuka.</span>
          </div>
        </div>

        <label style="display:block;margin:8px 0 6px;color:#64748b;font-size:11.2px;">File CSV</label>
        <input id="csvFile" type="file" accept=".csv,text/csv" class="swal2-input" style="margin:0; width:100%">

        <label style="display:block;margin:12px 0 6px;color:#64748b;font-size:11.2px;">Hapus semua template dulu</label>
        <select id="deleteAllFirst" class="swal2-select" style="width:100%; margin-top:0;">
          <option value="0" selected>Tidak</option>
          <option value="1">Ya</option>
        </select>

        <div class="meta" style="margin-top:10px;">
          Jika slot hari-jam-ruangan sama persis sudah ada di template, data lama akan diupdate.
        </div>
      </div>
    `,
    showCancelButton:true,
    confirmButtonText:'Upload',
    cancelButtonText:'Batal',
    confirmButtonColor:'#2563eb',
    heightAuto:false,
    preConfirm: async () => {
      const f = document.getElementById('csvFile')?.files?.[0];
      if (!f) {
        Swal.showValidationMessage('Pilih file CSV dulu.');
        return false;
      }

      const fd = new FormData();
      fd.append('_token', CSRF);
      fd.append('file', f);
      fd.append('delete_all_first', document.getElementById('deleteAllFirst')?.value || '0');

      const j = await api(URL_TMPL_UPLOAD_CSV, { method:'POST', body: fd });
      if (!j.ok) {
        Swal.showValidationMessage(j.message || 'Gagal upload jadwal');
        return false;
      }
      return j;
    }
  }).then(async (res) => {
    if (!res.isConfirmed || !res.value) return;
    const j = res.value;

    await Swal.fire({
      icon:'success',
      title:'Upload selesai',
      customClass:{ popup:'swal-wide' },
      html: `
        <div class="swal-scroll" style="text-align:left; line-height:1.8">
          Inserted: <b>${j.inserted || 0}</b><br>
          Updated: <b>${j.updated || 0}</b><br>
          Skipped: <b>${j.skipped || 0}</b><br>
          Invalid: <b>${j.invalid || 0}</b><br>
          Error: <b>${(j.errors && j.errors.length) ? j.errors.length : 0}</b>
          ${(j.errors && j.errors.length)
            ? `<hr><div style="font-size:12px">${j.errors.map(e => `• ${escapeHtml(e)}`).join('<br>')}</div>`
            : ``}
        </div>
      `,
      confirmButtonColor:'#2563eb',
      heightAuto:false
    });

    await loadTemplates();
    await loadEvents();
  });
}

async function openTemplateManager(){
  const j = await api(URL_TMPL_LIST + '?' + new URLSearchParams({ room_id:'', aktif:'', q:'' }).toString(), { cache:'no-store' });
  const items = (j.ok ? (j.items || []) : []);
  const DAY_LABEL = { senin:'Senin', selasa:'Selasa', rabu:'Rabu', kamis:'Kamis', jumat:'Jumat', sabtu:'Sabtu', minggu:'Minggu' };

  const listHtml = items.map(r => {
    const room = `${escapeHtml(r.room_floor)} - ${escapeHtml(r.room_name)}`;
    const jam = `${tShort(r.start_time)} - ${tShort(r.end_time)}`;
    const hari = DAY_LABEL[String(r.hari || '').toLowerCase()] || escapeHtml(r.hari || '-');

    return `
      <tr>
        <td style="width:86px; font-weight:700">${hari}</td>
        <td style="width:220px">${room}</td>
        <td style="width:150px"><span class="badge info"><i class="fa-solid fa-clock"></i> ${jam}</span></td>
        <td>
          <div style="font-weight:800">${escapeHtml(r.mata_kuliah || '-')}</div>
          <div class="meta">Kelas: <b>${escapeHtml(r.kelas || '-')}</b> • Dosen: <b>${escapeHtml(r.dosen || '-')}</b> • Semester: <b>${escapeHtml(r.semester || '-')}</b></div>
        </td>
        <td style="width:130px">${tmplBadge(r.aktif)}</td>
        <td style="width:180px">
          <div class="rowActions">
            <button class="btn btn-mini" type="button" onclick="editTemplate(${r.id})">
              <i class="fa-solid fa-pen"></i> Edit
            </button>
            <button class="btn btn-mini btn-danger" type="button" onclick="deleteTemplate(${r.id})">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');

  await Swal.fire({
    title:'Kelola Template PBM',
    customClass:{ popup:'swal-wide' },
    html: `
      <div class="swal-scroll" style="text-align:left">
        <div class="helper">
          <div style="font-size:12px; color:#334155;">
            Template mingguan adalah sumber jadwal rutin.<br>
            <span class="meta">Occurrence harian dibentuk terpisah agar bisa dipindah per tanggal tanpa mengubah template.</span>
          </div>
          <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <button type="button" class="btn btn-mini btn-primary" onclick="createTemplate()">
              <i class="fa-solid fa-plus"></i> Tambah
            </button>
            <button type="button" class="btn btn-mini btn-danger" onclick="deleteAllTemplates()">
              <i class="fa-solid fa-trash-can"></i> Hapus Semua
            </button>
          </div>
        </div>

        <div style="max-height:58vh; overflow:auto;">
          <table style="min-width:980px;">
            <thead>
              <tr>
                <th style="width:86px">Hari</th>
                <th style="width:220px">Ruangan</th>
                <th style="width:150px">Jam</th>
                <th>Detail</th>
                <th style="width:130px">Status</th>
                <th style="width:180px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              ${items.length ? listHtml : `<tr><td colspan="6" style="text-align:center; padding:20px;">Belum ada template.</td></tr>`}
            </tbody>
          </table>
        </div>
      </div>
    `,
    showConfirmButton:true,
    confirmButtonText:'Tutup',
    confirmButtonColor:'#2563eb',
    heightAuto:false
  });

  await loadTemplates();
}

function collectTemplateFormPayload(id){
  const room_id = document.getElementById('m_room_id_t')?.value;
  const hari = document.getElementById('m_hari_t')?.value;
  const start_time = (document.getElementById('m_start_t')?.value || '').trim();
  const end_time = (document.getElementById('m_end_t')?.value || '').trim();
  const mata_kuliah = (document.getElementById('m_mk_t')?.value || '').trim();
  const kelas = (document.getElementById('m_kelas_t')?.value || '').trim();
  const dosen = (document.getElementById('m_dosen_t')?.value || '').trim();
  const semester = (document.getElementById('m_semester_t')?.value || '').trim();
  const aktif = document.getElementById('m_aktif_t')?.value ?? '1';

  if (!room_id) { Swal.showValidationMessage('Ruangan wajib dipilih'); return null; }
  if (!hari) { Swal.showValidationMessage('Hari wajib dipilih'); return null; }
  if (!start_time || !end_time) { Swal.showValidationMessage('Jam mulai & selesai wajib diisi'); return null; }

  return { id, room_id, hari, start_time, end_time, mata_kuliah, kelas, dosen, semester, aktif };
}

async function createTemplate(){
  const res = await Swal.fire({
    title:'Tambah Template',
    customClass:{ popup:'swal-wide' },
    html: tmplFormHtml({ aktif:1, hari:'senin' }),
    showCancelButton:true,
    confirmButtonText:'Simpan',
    cancelButtonText:'Batal',
    confirmButtonColor:'#2563eb',
    heightAuto:false,
    preConfirm: async () => {
      const payload = collectTemplateFormPayload(0);
      if (!payload) return false;

      const fd = new FormData();
      fd.append('_token', CSRF);
      Object.entries(payload).forEach(([k,v]) => fd.append(k, v));

      const j = await api(URL_TMPL_SAVE, { method:'POST', body: fd });
      if (!j.ok) {
        Swal.showValidationMessage(j.message || 'Gagal simpan template');
        return false;
      }
      return j;
    }
  });

  if (res.isConfirmed && res.value) {
    toast('success', res.value.message || 'Template tersimpan');
    await loadTemplates();
    await loadEvents();
    await openTemplateManager();
  }
}

async function editTemplate(id){
  const j = await api(urlWithId(URL_TMPL_GET, id), { cache:'no-store' });
  if (!j.ok) return toast('error', j.message || 'Gagal memuat template');

  const res = await Swal.fire({
    title:'Edit Template',
    customClass:{ popup:'swal-wide' },
    html: tmplFormHtml(j.item || {}),
    showCancelButton:true,
    confirmButtonText:'Update',
    cancelButtonText:'Batal',
    confirmButtonColor:'#2563eb',
    heightAuto:false,
    preConfirm: async () => {
      const payload = collectTemplateFormPayload(id);
      if (!payload) return false;

      const fd = new FormData();
      fd.append('_token', CSRF);
      Object.entries(payload).forEach(([k,v]) => fd.append(k, v));

      const jr = await api(URL_TMPL_SAVE, { method:'POST', body: fd });
      if (!jr.ok) {
        Swal.showValidationMessage(jr.message || 'Gagal update template');
        return false;
      }
      return jr;
    }
  });

  if (res.isConfirmed && res.value) {
    toast('success', res.value.message || 'Template diupdate');
    await loadTemplates();
    await loadEvents();
    await openTemplateManager();
  }
}

async function deleteTemplate(id){
  const ok = await Swal.fire({
    icon:'warning',
    title:'Hapus template PBM?',
    html:'Template akan dihapus dan occurrence turunannya ikut dibersihkan.',
    showCancelButton:true,
    confirmButtonText:'Hapus',
    cancelButtonText:'Batal',
    confirmButtonColor:'#ef4444',
    heightAuto:false
  });
  if (!ok.isConfirmed) return;

  const fd = new FormData();
  fd.append('_token', CSRF);
  fd.append('_method', 'DELETE');

  const j = await api(urlWithId(URL_TMPL_DELETE, id), { method:'POST', body: fd });
  if (!j.ok) return toast('error', j.message || 'Gagal hapus template');

  toast('success', j.message || 'Template dihapus');
  await loadTemplates();
  await loadEvents();
}

async function deleteAllTemplates(){
  const ok = await Swal.fire({
    icon:'warning',
    title:'Hapus semua template PBM?',
    html:'Semua template dan occurrence PBM akan dihapus.',
    showCancelButton:true,
    confirmButtonText:'Hapus Semua',
    cancelButtonText:'Batal',
    confirmButtonColor:'#ef4444',
    heightAuto:false
  });
  if (!ok.isConfirmed) return;

  const fd = new FormData();
  fd.append('_token', CSRF);

  const j = await api(URL_TMPL_DELETE_ALL, { method:'POST', body: fd });
  if (!j.ok) return toast('error', j.message || 'Gagal hapus semua template');

  toast('success', j.message || `Berhasil hapus ${j.deleted || 0} template`);
  await loadTemplates();
  await loadEvents();
}

/* ===================== LISTENERS ===================== */
const debLoadEvents = debounce(loadEvents, 350);
const debLoadTemplates = debounce(loadTemplates, 350);

$('evDate')?.addEventListener('change', loadEvents);
$('evRoom')?.addEventListener('change', loadEvents);
$('evStatus')?.addEventListener('change', loadEvents);
$('evQ')?.addEventListener('input', debLoadEvents);

$('fRoom')?.addEventListener('change', loadTemplates);
$('fAktif')?.addEventListener('change', loadTemplates);
$('fQ')?.addEventListener('input', debLoadTemplates);

/* ===================== INIT ===================== */
(async function init(){
  const now = new Date();
  const today = `${now.getFullYear()}-${pad2(now.getMonth()+1)}-${pad2(now.getDate())}`;

  $('evDate').value = today;
  $('dateLabel').textContent = today;
  $('evStatus').value = 'all';

  await loadRooms();
  await loadEvents();
  await loadTemplates();
  updateSelectedEventsCount();
})();
</script>
</body>
</html>