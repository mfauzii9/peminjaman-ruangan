{{-- resources/views/admin/pengajuan_confirm.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Konfirmasi Pengajuan</title>

  @php
    $status = strtolower((string)($data->status ?? 'menunggu'));

    $statusClass = 'b-wait';
    $statusIcon  = 'fa-hourglass-half';
    if ($status === 'disetujui') { $statusClass = 'b-ok';   $statusIcon = 'fa-circle-check'; }
    elseif ($status === 'ditolak'){ $statusClass = 'b-no';  $statusIcon = 'fa-circle-xmark'; }
    elseif ($status === 'selesai'){ $statusClass = 'b-done';$statusIcon = 'fa-flag-checkered'; }
    elseif ($status === 'hangus') { $statusClass = 'b-exp'; $statusIcon = 'fa-clock-rotate-left'; }

    $roomName  = $data->room->name ?? '-';
    $roomFloor = $data->room->floor ?? '-';

    $kemaStatus = strtolower((string)($data->kema_status ?? 'menunggu'));
    $kemaBadge = 'b-wait'; $kemaIcon='fa-hourglass-half';
    if ($kemaStatus === 'disetujui'){ $kemaBadge='b-ok'; $kemaIcon='fa-circle-check'; }
    elseif ($kemaStatus === 'ditolak'){ $kemaBadge='b-no'; $kemaIcon='fa-circle-xmark'; }
    elseif ($kemaStatus === 'hangus'){ $kemaBadge='b-exp'; $kemaIcon='fa-clock-rotate-left'; }

    // expired?
    $isExpired = false;
    if (!empty($data->end_time)) {
      try { $isExpired = \Carbon\Carbon::parse($data->end_time)->lt(now()); } catch (\Exception $e) { $isExpired = false; }
    }

    $canApprove = ($kemaStatus === 'disetujui') && ($status === 'menunggu') && (!$isExpired);
    $approveDisabledReason = '';
    if ($kemaStatus !== 'disetujui') $approveDisabledReason = 'Kemahasiswaan belum menyetujui.';
    elseif ($status !== 'menunggu') $approveDisabledReason = 'Pengajuan sudah diproses admin.';
    elseif ($isExpired) $approveDisabledReason = 'Sudah lewat waktu (hangus).';

    $canReject = ($status === 'menunggu') && (!$isExpired);
    $rejectDisabledReason = '';
    if ($status !== 'menunggu') $rejectDisabledReason = 'Pengajuan sudah diproses admin.';
    elseif ($isExpired) $rejectDisabledReason = 'Sudah lewat waktu (hangus).';
  @endphp

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --bg:#f6f7fb; --card:#fff; --text:#0f172a; --muted:#64748b; --border:#e6e8ef;
      --shadow: 0 16px 55px rgba(15,23,42,.10);
      --shadow2: 0 10px 26px rgba(15,23,42,.07);
      --radius:18px;
      --accent:#2563eb; --accent2:#1d4ed8;
      --danger:#ef4444; --danger2:#dc2626;
    }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family: Inter, system-ui, sans-serif; background: var(--bg); color: var(--text); font-size: 12.8px; line-height: 1.55; }
    .app{ display:flex; min-height:100vh; }
    .main{ flex:1; min-width:0; }
    .topbar{ position:sticky; top:0; z-index:40; background: rgba(246,247,251,.86); backdrop-filter: blur(14px); border-bottom: 1px solid var(--border); padding: 12px 16px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .crumb{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .pill{ display:inline-flex; align-items:center; gap:10px; padding: 8px 12px; border-radius:999px; background: rgba(37,99,235,.10); border:1px solid rgba(37,99,235,.18); color:#1d4ed8; font-weight:950; font-size:12px; }
    .mutedTop{ color: var(--muted); font-weight:750; }
    .container{ max-width:1100px; margin:16px auto 30px; padding:0 16px; }
    .wrap{ max-width:1100px; margin:0 auto; padding:0; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow2); overflow:hidden; }
    .head{ padding:14px 16px; display:flex; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid #f0f2f7; }
    .title{ display:flex; align-items:center; gap:10px; font-weight:950; letter-spacing:.2px; }
    .title i{ width:34px;height:34px;border-radius:14px; display:grid; place-items:center; background: rgba(37,99,235,.08); border:1px solid rgba(37,99,235,.18); color:#1d4ed8; }
    .body{ padding:16px; background:rgba(246,247,251,.55); }
    .alert{ padding:12px 14px; border-radius:16px; border:1px solid var(--border); display:flex; gap:10px; align-items:flex-start; font-weight:750; margin-bottom:12px; }
    .alert i{ margin-top:2px; }
    .alert-ok{ background:#ecfdf5; border-color:#bbf7d0; color:#166534; }
    .alert-err{ background:#fef2f2; border-color:#fecaca; color:#991b1b; }
    .alert-info{ background:#eff6ff; border-color:#bfdbfe; color:#1e3a8a; }
    .grid{ display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:12px; }
    @media(max-width:900px){ .grid{ grid-template-columns:1fr; } }
    .field{ background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow2); padding:12px; }
    .label{ font-weight:950; color:#334155; font-size:12px; display:flex; align-items:center; gap:8px; }
    .value{ font-weight:900; margin-top:6px; font-size:13px; }
    .muted{ color:var(--muted); font-weight:650; font-size:12px; margin-top:4px; line-height:1.4; }
    .mono{ font-family:monospace; }
    .badge{ display:inline-flex; align-items:center; gap:7px; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:950; border:1px solid var(--border); background:#fff; white-space:nowrap; }
    .b-wait{color:#92400e; background:#fffbeb; border-color:#fde68a;}
    .b-ok{color:#166534; background:#ecfdf5; border-color:#bbf7d0;}
    .b-no{color:#991b1b; background:#fef2f2; border-color:#fecaca;}
    .b-done{color:#1e3a8a; background:#eff6ff; border-color:#bfdbfe;}
    .b-exp{color:#7c2d12; background:#fff7ed; border-color:#fed7aa;}
    .actions{ margin-top:14px; display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
    @media(max-width:900px){ .actions{ grid-template-columns:1fr; } }
    .box{ background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow2); padding:12px; }
    
 /* ----- PERBAIKAN CSS BUTTON ----- */
    .btn{ 
      display:inline-flex; align-items:center; justify-content:center; gap:8px; 
      padding:10px 12px; border-radius:14px; border:1px solid #bfdbfe; 
      background:#dbeafe; /* Warna awal biru muda */
      color:#1e40af; /* Warna text biru gelap agar jelas dibaca */
      text-decoration:none; 
      font-weight:900; font-size:12.6px; transition:all 0.25s ease; cursor:pointer; white-space:nowrap; 
    }
    /* Hover jadi biru agak gelap dengan animasi shadow melayang */
    .btn:hover { 
      background:#93c5fd; 
      color:#1e3a8a; 
      border-color:#60a5fa; 
      transform:translateY(-2px); 
      box-shadow: 0 4px 12px rgba(37,99,235,0.15); 
    }
    /* Saat diklik kembali turun */
    .btn:active { 
      background:#60a5fa; 
      color:#1e3a8a; 
      transform:translateY(0); 
      box-shadow: none; 
    }
    
    .btn-primary{ background: linear-gradient(135deg, var(--accent), var(--accent2)); color:#fff; border-color: transparent; box-shadow: 0 18px 40px rgba(37,99,235,.18); transition:all 0.25s ease; }
    .btn-primary:hover{ opacity:.96; color:#ffffff; transform:translateY(-2px); box-shadow: 0 6px 16px rgba(37,99,235,.25); }
    .btn-primary:active{ transform:translateY(0); opacity:1; box-shadow: 0 2px 8px rgba(37,99,235,.15); }
    
    .btn-danger{ background: linear-gradient(135deg, var(--danger), var(--danger2)); color:#fff; border-color: transparent; box-shadow: 0 18px 40px rgba(239,68,68,.18); transition:all 0.25s ease; }
    .btn-danger:hover{ opacity:.96; color:#ffffff; transform:translateY(-2px); box-shadow: 0 6px 16px rgba(239,68,68,.25); }
    .btn-danger:active{ transform:translateY(0); opacity:1; box-shadow: 0 2px 8px rgba(239,68,68,.15); }
    /* -------------------------------- */

    textarea{ width:100%; min-height:78px; resize:vertical; border:1px solid var(--border); border-radius:14px; padding:10px 12px; font:inherit; font-weight:750; outline:none; background:#fff; }
    textarea:focus{ border-color: rgba(37,99,235,.25); box-shadow: 0 0 0 4px rgba(37,99,235,.08); }
    .row{ display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .divider{ height:1px; background:#eef2f7; margin:10px 0; }
  </style>

  <script> document.documentElement.classList.remove('sb-collapsed'); </script>
</head>

<body>
  <div class="app">
    @include('partials.sidebar')

    <div class="main">
      <header class="topbar">
        <div class="crumb">
          <div class="pill"><i class="fa-solid fa-circle-info"></i> Konfirmasi Pengajuan</div>
          <div class="mutedTop">Detail pengajuan & aksi approve/reject</div>
        </div>
      </header>

      <div class="container">
        <div class="wrap">
          <div class="card">

            <div class="head">
              <div class="title"><i class="fa-solid fa-circle-info"></i> <span>Konfirmasi Pengajuan</span></div>
              <div class="row">
                <span class="badge {{ $kemaBadge }}"><i class="fa-solid {{ $kemaIcon }}"></i> kema: {{ $kemaStatus }}</span>
                <span class="badge {{ $statusClass }}"><i class="fa-solid {{ $statusIcon }}"></i> admin: {{ $status }}</span>
              </div>
            </div>

            <div class="body">
              {{-- INFOS DISABLED --}}
              @if(!$canApprove)
                <div class="alert alert-info"><i class="fa-solid fa-lock"></i> <div>Tombol <b>Approve</b> dinonaktifkan: {{ $approveDisabledReason }}</div></div>
              @endif

              @if(!$canReject)
                <div class="alert alert-info"><i class="fa-solid fa-lock"></i> <div>Tombol <b>Reject</b> dinonaktifkan: {{ $rejectDisabledReason }}</div></div>
              @endif

              <div class="grid">
                <div class="field">
                  <div class="label"><i class="fa-solid fa-hashtag"></i> ID Pengajuan</div>
                  <div class="value mono">#{{ $data->id }}</div>
                  <div class="muted">Dibuat: {{ $data->created_at ?? '-' }}</div>
                </div>

                <div class="field">
                  <div class="label"><i class="fa-solid fa-door-open"></i> Ruangan</div>
                  <div class="value">{{ $roomFloor }} - {{ $roomName }}</div>
                  <div class="muted">Kapasitas: {{ $data->room->capacity ?? '-' }}</div>
                </div>

                <div class="field">
                  <div class="label"><i class="fa-solid fa-user"></i> Peminjam</div>
                  <div class="value">{{ $data->responsible_name ?? '-' }}</div>
                  <div class="muted">{{ $data->email ?? '-' }} • {{ $data->phone ?? '-' }}</div>
                </div>

                <div class="field">
                  <div class="label"><i class="fa-solid fa-building"></i> Organisasi</div>
                  <div class="value">{{ $data->org_name ?? '-' }}</div>
                  <div class="muted">
                    Kema: <b>{{ $kemaStatus }}</b>
                    @if(!empty($data->kema_approved_at)) • at: {{ $data->kema_approved_at }} @endif
                  </div>
                </div>

                <div class="field">
                  <div class="label"><i class="fa-regular fa-clock"></i> Waktu</div>
                  <div class="value">{{ $data->start_time ?? '-' }}</div>
                  <div class="muted">s/d {{ $data->end_time ?? '-' }} @if($isExpired) • <b>expired</b> @endif</div>
                </div>

                <div class="field">
                  <div class="label"><i class="fa-solid fa-file"></i> Surat</div>
                  <div class="value">
                    @if(!empty($data->letter_file))
                      <a class="btn" href="{{ asset($data->letter_file) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Surat</a>
                    @else - @endif
                  </div>
                </div>

                <div class="field" style="grid-column:1/-1;">
                  <div class="label"><i class="fa-solid fa-user-gear"></i> Catatan Admin (tersimpan)</div>
                  <div class="value">{{ $data->admin_note ?? '-' }}</div>
                  <div class="muted">
                    @if(!empty($data->approved_at)) Approved at: {{ $data->approved_at }} @endif
                  </div>
                </div>
              </div>

              <div class="actions">
                {{-- FORM APPROVE --}}
                <div class="box">
                  <div class="row" style="margin-bottom:8px;">
                    <div style="font-weight:950;"><i class="fa-solid fa-circle-check"></i> Approve</div>
                    <span class="muted">Opsional</span>
                  </div>

                  <form id="approveForm" action="{{ route('admin.pengajuan.approve', $data->id) }}">
                    @csrf
                    <textarea name="admin_note" placeholder="Catatan admin (opsional)"></textarea>
                    <div class="divider"></div>
                    <button type="submit" class="btn btn-primary" {{ $canApprove ? '' : 'disabled' }} style="{{ $canApprove ? '' : 'opacity:.6; cursor:not-allowed;' }}">
                      <i class="fa-solid fa-circle-check"></i> Approve
                    </button>
                  </form>
                </div>

                {{-- FORM REJECT --}}
                <div class="box">
                  <div class="row" style="margin-bottom:8px;">
                    <div style="font-weight:950;"><i class="fa-solid fa-circle-xmark"></i> Reject</div>
                    <span class="muted">Wajib diisi</span>
                  </div>

                  <form id="rejectForm" action="{{ route('admin.pengajuan.reject', $data->id) }}">
                    @csrf
                    <textarea id="rejectNote" name="admin_note" placeholder="Alasan penolakan (wajib)"></textarea>
                    <div class="divider"></div>
                    <button type="submit" class="btn btn-danger" {{ $canReject ? '' : 'disabled' }} style="{{ $canReject ? '' : 'opacity:.6; cursor:not-allowed;' }}">
                      <i class="fa-solid fa-circle-xmark"></i> Reject
                    </button>
                  </form>
                </div>
              </div>

              <div style="margin-top:12px;">
                <a class="btn" href="{{ route('admin.pengajuan', ['view' => 'history']) }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // AJAX Submit Helper
    function handleAjaxForm(form, confirmTitle, confirmText, isReasonRequired = false) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validasi Alasan
        if (isReasonRequired) {
          const reasonInput = form.querySelector('textarea[name="admin_note"]');
          if (!reasonInput.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Alasan Wajib Diisi', text: 'Silakan isi alasan penolakan terlebih dahulu.' });
            return;
          }
        }

        Swal.fire({
          icon: 'question',
          title: confirmTitle,
          text: confirmText,
          showCancelButton: true,
          confirmButtonText: 'Ya, Lanjutkan',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          allowOutsideClick: false,
        }).then((result) => {
          if (!result.isConfirmed) return;

          Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          // Eksekusi AJAX (Fetch API)
          const formData = new FormData(form);
          fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, confirmButtonText: 'OK' })
              .then(() => location.reload()); // Reload halaman untuk update data
            } else {
              Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
          })
          .catch(err => {
            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal terhubung ke server.' });
          });
        });
      });
    }

    const approveForm = document.getElementById('approveForm');
    if (approveForm && !approveForm.querySelector('button').disabled) {
      handleAjaxForm(approveForm, 'Approve pengajuan ini?', 'Aksi ini akan menyetujui pengajuan.', false);
    }

    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm && !rejectForm.querySelector('button').disabled) {
      handleAjaxForm(rejectForm, 'Reject pengajuan ini?', 'Pengajuan akan ditolak. Pastikan alasan sudah sesuai.', true);
    }
  </script>
</body>
</html>