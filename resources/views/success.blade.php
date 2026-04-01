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
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --text:#0b1220;
      --muted:#64748b;
      --border:#e6e8ef;

      --shadow: 0 18px 60px rgba(15,23,42,.10);
      --shadow2: 0 10px 30px rgba(15,23,42,.06);

      --accent:#2563eb;
      --accent2:#1d4ed8;
      --accentSoft:#eff6ff;

      --ok:#16a34a;
      --okSoft:#ecfdf5;

      --warn:#f59e0b;
      --warnSoft:#fffbeb;

      --danger:#ef4444;
      --dangerSoft:#fef2f2;

      --radius:22px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      color:var(--text);
      background:
        radial-gradient(900px 320px at 15% -10%, rgba(37,99,235,.16), transparent 55%),
        radial-gradient(900px 320px at 85% -10%, rgba(16,185,129,.12), transparent 55%),
        var(--bg);
      line-height:1.6;
      font-size:13px;
      min-height:100vh;
    }
    .wrap{width:100%;max-width: 980px;margin: 0 auto;padding: 18px 14px 26px;}
    .topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 12px;border-radius: 18px;border:1px solid rgba(226,232,240,.9);background: rgba(255,255,255,.82);backdrop-filter: blur(14px);box-shadow: var(--shadow2);}
    .brand{display:flex; align-items:center; gap:10px;min-width:0;font-weight:950;letter-spacing:.2px;}
    .brand .logo{width:42px;height:42px;border-radius:16px;display:grid;place-items:center;background: linear-gradient(135deg, rgba(37,99,235,.95), rgba(14,165,233,.85));box-shadow: 0 18px 40px rgba(37,99,235,.18);color:#fff;flex:0 0 auto;border:1px solid rgba(255,255,255,.35);}
    .brand .t{display:flex;flex-direction:column;line-height:1.15;min-width:0;}
    .brand .t small{margin-top:2px;color:var(--muted);font-weight:750;font-size:11.5px;}
    .pill{display:inline-flex;align-items:center;gap:8px;padding:9px 10px;border-radius:999px;border:1px solid rgba(226,232,240,.9);background:#fff;color:var(--muted);font-weight:800;font-size:12px;white-space:nowrap;}
    .card{margin-top:12px;background: rgba(255,255,255,.92);border:1px solid rgba(226,232,240,.9);border-radius: var(--radius);box-shadow: var(--shadow);overflow:hidden;}
    .hero{padding:16px 14px 14px;background:radial-gradient(900px 240px at 12% 0%, rgba(37,99,235,.14), transparent 60%),rgba(255,255,255,.96);border-bottom:1px solid rgba(241,245,249,.9);}
    .row{display:flex;gap:12px;align-items:center;}
    .icon{width:52px;height:52px;border-radius:18px;display:grid;place-items:center;border:1px solid rgba(22,163,74,.18);background:var(--okSoft);color:var(--ok);font-size:22px;}
    h1{margin:0;font-size:16px;font-weight:950;letter-spacing:.2px;line-height:1.2;}
    .subtitle{margin:6px 0 0;color:var(--muted);font-weight:650;font-size:12.6px;max-width: 80ch;}
    .content{ padding:14px; }
    .mini{margin-top:12px;border:1px solid rgba(226,232,240,.9);border-radius:18px;background:#fff;box-shadow: var(--shadow2);padding:12px;}
    .mini h2{margin:0 0 10px;font-size:13.2px;font-weight:950;display:flex;align-items:center;gap:10px;}
    .kv{border:1px solid rgba(226,232,240,.95);border-radius:16px;padding:12px;background: rgba(248,250,252,.75);}
    .k{ color:var(--muted); font-weight:800; font-size:12px; }
    .v{ margin-top:6px; font-weight:950; font-size:13px; word-break:break-word; display:flex; gap:10px; align-items:center; flex-wrap:wrap;}
    .copybtn{border:1px solid rgba(226,232,240,.95);background:#fff;border-radius:14px;padding:8px 10px;font-weight:950;cursor:pointer;box-shadow: var(--shadow2);}
    .actions{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;}
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:12px 12px;border-radius:16px;border:1px solid rgba(226,232,240,.95);background:#fff;color:var(--text);text-decoration:none;font-weight:950;font-size:13px;cursor:pointer;transition:.16s ease;box-shadow: var(--shadow2);white-space:nowrap;}
    .btn:hover{transform: translateY(-1px);background: var(--accentSoft);border-color: rgba(37,99,235,.18);}
    .btn.primary{background: linear-gradient(135deg, var(--accent), var(--accent2));border-color: transparent;color:#fff;box-shadow: 0 16px 30px rgba(37,99,235,.18);}
    .toast{position:fixed;left:50%;bottom:18px;transform:translateX(-50%);background:#0b1220;color:#fff;padding:10px 12px;border-radius:999px;font-weight:900;font-size:12px;opacity:0;pointer-events:none;transition:.2s;}
    .toast.show{opacity:1;}

    /* ===== Exit Reminder Modal ===== */
    .modal{ position:fixed; inset:0; display:none; z-index:9999; }
    .modal.show{ display:block; }
    .modal__backdrop{
      position:absolute; inset:0;
      background: rgba(2,6,23,.55);
      backdrop-filter: blur(6px);
    }
    .modal__panel{
      position:relative;
      width: min(450px, calc(100% - 26px));
      margin: 12vh auto 0;
      background: rgba(255,255,255,.96);
      border:1px solid rgba(226,232,240,.9);
      border-radius: 22px;
      box-shadow: var(--shadow);
      padding: 18px;
    }
    .modal__head{ display:flex; gap:12px; align-items:flex-start; }
    .modal__badge{
      width:46px;height:46px;border-radius:16px;
      display:grid;place-items:center;
      background: var(--warnSoft);
      border:1px solid rgba(245,158,11,.22);
      color: var(--warn);
      font-size:18px;
      flex:0 0 auto;
    }
    .modal__title{ font-weight:950; font-size:15px; line-height:1.2; color: var(--text); }
    .modal__desc{ margin-top:6px; color:var(--muted); font-weight:500; font-size:12.6px; line-height: 1.5; }
    .modal__code{
      margin-top:16px;
      border:1px dashed rgba(226,232,240,.95);
      border-radius:14px;
      background: rgba(248,250,252,.75);
      padding:14px;
      text-align: center;
    }
    .modal__code span {
      font-size: 18px;
      font-weight: 900;
      letter-spacing: 1px;
      color: var(--accent2);
    }
    .modal__actions{
      margin-top:16px;
      display:flex;
    }
    .btn.full{
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
          <h2><i class="fa-solid fa-ticket" style="color:var(--accent)"></i> Simpan Kode Booking</h2>
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

  <div class="toast" id="toast">Tersalin!</div>

  <script>
    function showToast(){
      const t = document.getElementById('toast');
      if (!t) return;
      t.classList.add('show');
      setTimeout(()=> t.classList.remove('show'), 900);
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