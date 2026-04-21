<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengajuan Berhasil</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      /* Elegant Color Palette: Navy Blue, Sage Green, Light Blue */
      --navy-900: #0f172a;
      --navy-800: #1A2942;
      --navy-600: #2B4266;
      --navy-400: #4B638A;
      
      --sage-700: #5C6E60;
      --sage-500: #849B87;
      --sage-300: #A3B1A6;
      --sage-100: #E3E8E4;
      --sage-50:  #F4F7F5;

      --lightblue-500: #60A5FA;
      --lightblue-300: #93C5FD;
      --lightblue-100: #DBEAFE;
      --lightblue-50:  #EFF6FF;

      --neutral-50: #F8FAFC;
      --neutral-100: #F1F5F9;
      --neutral-200: #E2E8F0;
      --neutral-300: #CBD5E1;
      
      --bg-main: #F4F7F6;
      --bg-elevated: #FFFFFF;

      --text-primary: #1A2942;
      --text-secondary: #4B638A;
      --text-tertiary: #64748b; 

      --border-light: #E2E8F0; 
      --border-regular: #CBD5E1;

      --shadow-sm: 0 1px 2px 0 rgba(26, 41, 66, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(26, 41, 66, 0.08), 0 2px 4px -1px rgba(26, 41, 66, 0.04); 
      --shadow-lg: 0 10px 15px -3px rgba(26, 41, 66, 0.1), 0 4px 6px -4px rgba(26, 41, 66, 0.05);

      /* Mapped Variables */
      --bg: var(--bg-main);
      --card: var(--bg-elevated);
      --text: var(--text-primary);
      --muted: var(--text-secondary);
      --border: var(--border-light);

      --shadow: var(--shadow-lg);
      --shadow2: var(--shadow-md);

      --accent: var(--navy-800);
      --accent2: var(--lightblue-500);
      --accentSoft: var(--lightblue-50);

      /* Status Colors */
      --ok: #10b981;
      --okSoft: #ecfdf5;
      --warn: #f59e0b;
      --warnSoft: #fffbeb;
      --danger: #ef4444;
      --dangerSoft: #fef2f2;

      --radius: 1.25rem;
      --radius-sm: 0.75rem;

      --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
    }
    
    * { box-sizing: border-box; }
    
    body {
      margin: 0;
      font-family: var(--font-sans);
      color: var(--text);
      background:
        radial-gradient(900px 320px at 15% -10%, rgba(96,165,250,.15), transparent 55%),
        radial-gradient(900px 320px at 85% -10%, rgba(132,155,135,.15), transparent 55%),
        var(--bg);
      line-height: 1.6;
      font-size: 13px;
      min-height: 100vh;
    }
    
    .wrap { width: 100%; max-width: 980px; margin: 0 auto; padding: 18px 14px 26px; }
    
    .topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(255,255,255,.9); backdrop-filter: blur(14px); box-shadow: var(--shadow2); }
    
    .brand { display: flex; align-items: center; gap: 10px; min-width: 0; font-weight: 800; letter-spacing: .2px; }
    .brand .logo { width: 42px; height: 42px; border-radius: var(--radius-sm); display: grid; place-items: center; background: linear-gradient(135deg, var(--navy-800), var(--navy-600)); box-shadow: var(--shadow-sm); color: #fff; flex: 0 0 auto; border: 1px solid rgba(255,255,255,.35); }
    .brand .t { display: flex; flex-direction: column; line-height: 1.15; min-width: 0; }
    .brand .t span { font-size: 15px; font-weight: 800; color: var(--navy-900); }
    .brand .t small { margin-top: 2px; color: var(--muted); font-weight: 600; font-size: 11.5px; }
    
    .pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; border: 1px solid var(--border); background: #fff; color: var(--text-tertiary); font-weight: 700; font-size: 12px; white-space: nowrap; }
    .pill i { color: var(--lightblue-500); }
    
    .card { margin-top: 12px; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .hero { padding: 20px 20px 16px; background: radial-gradient(900px 240px at 12% 0%, rgba(96,165,250,.1), transparent 60%), var(--card); border-bottom: 1px solid var(--neutral-100); }
    
    .row { display: flex; gap: 16px; align-items: center; }
    .icon { width: 56px; height: 56px; border-radius: var(--radius-sm); display: grid; place-items: center; border: 1px solid rgba(16, 185, 129, 0.2); background: var(--okSoft); color: var(--ok); font-size: 24px; box-shadow: var(--shadow-sm); }
    
    h1 { margin: 0; font-size: 18px; font-weight: 900; letter-spacing: .2px; line-height: 1.2; color: var(--navy-900); }
    .subtitle { margin: 6px 0 0; color: var(--muted); font-weight: 500; font-size: 13px; max-width: 80ch; }
    
    .content { padding: 20px; }
    
    .mini { margin-top: 12px; border: 1px solid var(--border); border-radius: 18px; background: var(--neutral-50); box-shadow: var(--shadow-sm); padding: 16px; }
    .mini h2 { margin: 0 0 12px; font-size: 14px; font-weight: 800; display: flex; align-items: center; gap: 10px; color: var(--navy-900); }
    .mini h2 i { color: var(--lightblue-500); }
    
    .kv { border: 1px dashed var(--border-regular); border-radius: var(--radius-sm); padding: 14px; background: #fff; }
    .k { color: var(--text-tertiary); font-weight: 700; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.02em; }
    .v { margin-top: 8px; font-weight: 900; font-size: 18px; word-break: break-word; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; color: var(--navy-800); }
    
    .copybtn { border: 1px solid var(--border-regular); background: var(--neutral-50); color: var(--navy-800); border-radius: var(--radius-sm); padding: 8px 12px; font-weight: 700; font-size: 12px; cursor: pointer; transition: all 0.2s; box-shadow: var(--shadow-sm); display: inline-flex; align-items: center; gap: 6px; }
    .copybtn:hover { background: var(--lightblue-50); border-color: var(--lightblue-300); color: var(--navy-900); transform: translateY(-1px); }
    
    .actions { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
    
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: 999px; border: 1px solid var(--border-regular); background: #fff; color: var(--navy-800); text-decoration: none; font-weight: 800; font-size: 13px; cursor: pointer; transition: .2s ease; box-shadow: var(--shadow-sm); white-space: nowrap; }
    .btn:hover { transform: translateY(-1px); background: var(--lightblue-50); border-color: var(--lightblue-300); color: var(--navy-900); box-shadow: var(--shadow-md); }
    .btn.primary { background: var(--accent); border-color: var(--accent); color: #fff; box-shadow: var(--shadow-sm); }
    .btn.primary:hover { background: var(--lightblue-500); border-color: var(--lightblue-500); color: var(--navy-900); box-shadow: var(--shadow-md); }
    
    .toast { position: fixed; left: 50%; bottom: 20px; transform: translateX(-50%) translateY(20px); background: var(--navy-900); color: #fff; padding: 10px 16px; border-radius: 999px; font-weight: 700; font-size: 12px; opacity: 0; pointer-events: none; transition: all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: var(--shadow-lg); }
    .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

    /* ===== Exit Reminder Modal ===== */
    .modal { position: fixed; inset: 0; display: none; z-index: 9999; }
    .modal.show { display: block; }
    .modal__backdrop {
      position: absolute; inset: 0;
      background: rgba(15, 23, 42, 0.5);
      backdrop-filter: blur(4px);
    }
    .modal__panel {
      position: relative;
      width: min(450px, calc(100% - 32px));
      margin: 12vh auto 0;
      background: var(--bg-elevated);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-lg);
      padding: 24px;
      animation: modalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    @keyframes modalPop {
        0% { transform: scale(0.95) translateY(10px); opacity: 0; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    .modal__head { display: flex; gap: 14px; align-items: flex-start; }
    .modal__badge {
      width: 48px; height: 48px; border-radius: var(--radius-sm);
      display: grid; place-items: center;
      background: var(--warnSoft);
      border: 1px solid #FDBA74;
      color: var(--warn);
      font-size: 20px;
      flex: 0 0 auto;
    }
    .modal__title { font-weight: 900; font-size: 16px; line-height: 1.2; color: var(--navy-900); }
    .modal__desc { margin-top: 6px; color: var(--muted); font-weight: 500; font-size: 13px; line-height: 1.6; }
    .modal__code {
      margin-top: 20px;
      border: 1px dashed var(--border-regular);
      border-radius: var(--radius-sm);
      background: var(--neutral-50);
      padding: 16px;
      text-align: center;
    }
    .modal__code span {
      font-size: 20px;
      font-weight: 900;
      letter-spacing: 1px;
      color: var(--navy-800);
    }
    .modal__actions {
      margin-top: 20px;
      display: flex;
    }
    .btn.full {
      width: 100%;
      padding: 14px;
      font-size: 14px;
    }
  </style>
</head>

<body>
  @php
    $code  = $code ?? request()->query('code', '');
    $token = $token ?? request()->query('token', '');
  @endphp

  <div class="wrap">
    <div class="topbar">
      <div class="brand">
        <div class="logo"><i class="fa-solid fa-cube"></i></div>
        <div class="t">
          <span>Room Booking</span>
          <small>Pengajuan Peminjaman</small>
        </div>
      </div>

      <div class="pill" title="Waktu server">
        <i class="fa-regular fa-clock"></i>
        {{ now()->format('d-m-Y H:i') }}
      </div>
    </div>

    <section class="card">
      <div class="hero">
        <div class="row">
          <div class="icon">
            <i class="fa-solid fa-circle-check"></i>
          </div>
          <div>
            <h1>Pengajuan berhasil dikirim</h1>
            <div class="subtitle">
              Pengajuan kamu sudah masuk ke sistem. Simpan <b>Kode Booking</b> untuk cek status.
            </div>
          </div>
        </div>
      </div>

      <div class="content">

        <div class="mini">
          <h2><i class="fa-solid fa-ticket"></i> Simpan Kode Booking</h2>
          <div class="kv">
            <div class="k">Kode Booking</div>
            <div class="v">
              <span id="codeTxt">{{ $code ?: '-' }}</span>
              <button class="copybtn" type="button" onclick="copyText('codeTxt')">
                <i class="fa-regular fa-copy"></i> Copy
              </button>
            </div>
          </div>
        </div>

        {{-- OPTIONAL: kalau token masih mau ditampilkan --}}
        @if(!empty($token))
        @endif

        <div class="actions">
          <a class="btn primary" href="{{ route('history.index', ['code' => $code]) }}">
            <i class="fa-solid fa-magnifying-glass"></i> Cek Pengajuan
          </a>
        </div>

      </div>
    </section>
  </div>

  <div class="modal" id="exitModal" aria-hidden="true">
    <div class="modal__backdrop"></div>
    <div class="modal__panel" role="dialog" aria-modal="true" aria-labelledby="exitTitle">
      <div class="modal__head">
        <div class="modal__badge">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
          <div class="modal__title" id="exitTitle">Tunggu Sebentar!</div>
          <div class="modal__desc">
            Silakan <b>salin (copy)</b> Kode Booking Anda terlebih dahulu sebelum keluar halaman agar Anda dapat mengecek status pengajuan nanti.<br><br>
            <i>*Jika lupa, Anda juga dapat mengecek pesan Kode Booking yang dikirimkan ke email yang Anda isi pada formulir.</i>
          </div>
        </div>
      </div>

      <div class="modal__code">
        <span id="codeTxtModal">{{ $code ?: '-' }}</span>
      </div>

      <div class="modal__actions">
        <button class="btn primary full" type="button" onclick="forceCopyAndProceed()">
          <i class="fa-regular fa-copy"></i> Copy Kode & Lanjutkan
        </button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><i class="fa-solid fa-check"></i> Kode berhasil disalin!</div>

  <script>
    function showToast(){
      const t = document.getElementById('toast');
      if (!t) return;
      t.classList.add('show');
      setTimeout(()=> t.classList.remove('show'), 2000);
    }

    function fallbackCopy(text){
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.left = '-9999px';
      document.body.appendChild(ta);
      ta.focus();
      ta.select();
      try { document.execCommand('copy'); } catch(e){}
      document.body.removeChild(ta);
      showToast();
    }

    function copyRaw(text){
      if (!text) return;
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(showToast).catch(()=> fallbackCopy(text));
      } else {
        fallbackCopy(text);
      }
    }

    // ===== Flow Logic & Exit Modal =====
    let pendingNavigation = null;
    let hasCopied = false; // Flag untuk menandai apakah kode sudah disalin

    // Fungsi normal copy text dari halaman utama
    function copyText(id){
      const el = document.getElementById(id);
      if (!el) return;
      copyRaw(el.textContent.trim());
      hasCopied = true; // Jika sudah dicopy dari halaman utama, popup tidak akan muncul
    }

    function openExitModal(nextFn){
      pendingNavigation = nextFn || null;
      const m = document.getElementById('exitModal');
      if (!m) return;
      m.classList.add('show');
      m.setAttribute('aria-hidden', 'false');
    }

    // Fungsi paksa copy dari popup lalu tutup dan lanjutkan navigasi
    function forceCopyAndProceed() {
      const el = document.getElementById('codeTxtModal');
      if (el) {
        copyRaw(el.textContent.trim());
      }
      
      hasCopied = true; // Tandai sudah copy
      
      // Tutup modal
      const m = document.getElementById('exitModal');
      if (m){
        m.classList.remove('show');
        m.setAttribute('aria-hidden', 'true');
      }

      // Jalankan navigasi yang tertunda
      if (typeof pendingNavigation === 'function'){
        const fn = pendingNavigation;
        pendingNavigation = null;
        fn();
      }
    }

    // Tangkap setiap klik link keluar
    document.addEventListener('click', function(e){
      const a = e.target.closest('a');
      if (!a) return;

      const href = a.getAttribute('href') || '';
      if (!href || href === '#' || href.startsWith('javascript:')) return;

      // Jika user sudah pernah copy kode, langsung izinkan pindah halaman
      if (hasCopied) return;

      // Jika belum copy, cegah default link dan munculkan pop up
      e.preventDefault();
      openExitModal(() => { window.location.href = href; });
    });

    // Peringatan saat mencoba refresh/tutup tab langsung
    window.addEventListener('beforeunload', function(e){
      if (hasCopied) return; // Kalau sudah copy, biarkan saja
      e.preventDefault();
      e.returnValue = '';
    });
  </script>
</body>
</html>