<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Pengajuan (Kode Booking)</title>

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
      --fs-xs: 0.75rem;
      --fs-sm: 0.85rem;
      --fs-base: 0.95rem;
      --fs-md: 1.1rem;
      --fs-lg: 1.25rem;
      --fs-xl: 1.5rem;
      --fs-3xl: 2.25rem;
    }

    *{box-sizing:border-box}
    html{scroll-behavior:smooth;}

    body {
      margin:0;
      font-family: var(--font-sans);
      font-size: var(--fs-base);
      color: var(--text);
      background: var(--bg);
      line-height: 1.6;
      padding-bottom: 90px; 
    }

    .cek-wrap {
      background:
        radial-gradient(900px 320px at 15% -10%, rgba(96,165,250,.15), transparent 55%),
        radial-gradient(900px 320px at 85% -10%, rgba(132,155,135,.15), transparent 55%),
        var(--bg);
      min-height: calc(100vh - 20px);
      padding: 18px 0 90px; 
    }

    .cek-wrap .wrap { width:100%; max-width: 980px; margin: 0 auto; padding: 18px 14px 26px; }
    .cek-wrap .topbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 14px; border-radius: var(--radius-sm); border:1px solid var(--border); background: rgba(255,255,255,.9); backdrop-filter: blur(14px); box-shadow: var(--shadow2); }
    
    .cek-wrap .brand { display:flex; align-items:center; gap:10px; min-width:0; font-weight:900; letter-spacing:.2px; }
    .cek-wrap .brand .logo { width:36px; height:36px; border-radius:8px; display:grid; place-items:center; background: linear-gradient(135deg, var(--navy-800), var(--navy-600)); box-shadow: var(--shadow-md); color:#fff; flex:0 0 auto; border:1px solid rgba(255,255,255,.35); }
    .cek-wrap .brand .t { display:flex; flex-direction:column; line-height:1.15; min-width:0; }
    .cek-wrap .brand .t span { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-size: 14.5px; font-weight: 800; color: var(--navy-900); }
    .cek-wrap .brand .t small { margin-top:2px; color:var(--muted); font-weight:600; font-size: 11px; }
    
    .cek-wrap .pill { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; border:1px solid var(--border); background:#fff; color:var(--muted); font-weight:700; font-size:11.5px; white-space:nowrap; }
    .cek-wrap .pill i { color: var(--lightblue-500); }
    
    .cek-wrap .card { margin-top:12px; background: var(--card); border:1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow:hidden; }
    .cek-wrap .hero { padding:20px 20px 16px; background:radial-gradient(900px 240px at 12% 0%, rgba(96,165,250,.1), transparent 60%), var(--card); border-bottom:1px solid var(--neutral-100); }
    
    .cek-wrap h1 { margin:0; font-size: 18px; font-weight:900; letter-spacing:.2px; line-height:1.2; display:flex; gap:8px; align-items:center; color: var(--navy-900); }
    .cek-wrap .subtitle { margin:6px 0 0; color:var(--muted); font-weight:500; font-size: 12.5px; max-width: 80ch; }
    
    .cek-wrap .content { padding:20px; }
    .cek-wrap .searchBox { border:1px solid var(--border); border-radius: 18px; background: var(--neutral-50); box-shadow: var(--shadow-sm); padding:12px; transition: all 0.2s ease; }
    .cek-wrap .searchRow { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .cek-wrap .input { flex:1 1 240px; display:flex; align-items:center; gap:10px; padding:12px 14px; border:1px solid var(--border-regular); border-radius: var(--radius-sm); background:#fff; transition:.15s ease; }
    .cek-wrap .input:focus-within { border-color: var(--lightblue-500); box-shadow: 0 0 0 4px var(--lightblue-50); }
    .cek-wrap .input i { color: var(--text-tertiary); }
    .cek-wrap .input input { width:100%; border:none; outline:none; font:inherit; font-weight:800; background:transparent; font-size:13.5px; letter-spacing:.3px; text-transform: uppercase; color: var(--navy-800); }
    .cek-wrap .input input::placeholder { color: var(--neutral-300); text-transform: none; font-weight: 500; }
    
    .cek-wrap .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 18px; border-radius:999px; border:1px solid var(--border-regular); background:#fff; color:var(--navy-800); text-decoration:none; font-weight:800; font-size:13px; cursor:pointer; transition:.2s ease; box-shadow: var(--shadow-sm); white-space:nowrap; }
    .cek-wrap .btn:hover { transform: translateY(-1px); background: var(--lightblue-50); border-color: var(--lightblue-300); color: var(--navy-900); box-shadow: var(--shadow-md); }
    .cek-wrap .btn.primary { background: var(--accent); border-color: var(--accent); color:#fff; box-shadow: var(--shadow-sm); }
    .cek-wrap .btn.primary:hover { background: var(--lightblue-500); border-color: var(--lightblue-500); color: var(--navy-900); box-shadow: var(--shadow-md); }
    
    .cek-wrap .hint { margin-top:12px; color:var(--muted); font-weight:500; font-size:11.5px; display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .cek-wrap .hint i { color: var(--lightblue-500); }
    .cek-wrap .hint code { background: var(--neutral-50); border:1px solid var(--border-light); padding:2px 8px; border-radius:999px; font-weight:700; font-size:11px; color: var(--navy-800); }
    
    .cek-wrap .result { margin-top:16px; border:1px solid var(--border); border-radius: 18px; background:#fff; box-shadow: var(--shadow-sm); overflow:hidden; }
    .cek-wrap .result-h { padding:14px 16px; border-bottom:1px solid var(--neutral-100); background: var(--neutral-50); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .cek-wrap .badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; font-weight:800; font-size:11px; white-space:nowrap; border:1px solid var(--border-light); background:#fff; color:var(--muted); text-transform:uppercase; letter-spacing:0.05em; }
    .cek-wrap .badge.wait { border-color: #FDBA74; background: var(--warnSoft); color:#9A3412; }
    .cek-wrap .badge.ok { border-color: #A7F3D0; background: var(--okSoft); color:#065F46; }
    .cek-wrap .badge.err { border-color: #FECDD3; background: var(--dangerSoft); color:#9F1239; }
    .cek-wrap .badge.soft { border-color: var(--lightblue-300); background: var(--accentSoft); color: var(--navy-800); }
    .cek-wrap .result-b { padding:16px; }
    
    .cek-wrap .grid { display:grid; grid-template-columns: 1fr; gap:12px; }
    @media(min-width:720px){.cek-wrap .grid{ grid-template-columns: 1fr 1fr; }}
    
    .cek-wrap .kv { border:1px solid var(--neutral-100); border-radius: var(--radius-sm); padding:14px; background: var(--neutral-50); }
    .cek-wrap .k { color:var(--text-tertiary); font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:0.03em; }
    .cek-wrap .v { margin-top:6px; font-weight:800; font-size:13px; word-break:break-word; color: var(--navy-900); }
    
    .cek-wrap .empty { margin-top:16px; padding:24px 18px; border-radius: 18px; border:1px dashed var(--border-regular); background: var(--neutral-50); color:var(--muted); font-weight:600; font-size:13px; text-align:center; }
    .cek-wrap .foot { margin-top:16px; display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center; color:var(--muted); font-weight:600; font-size:11.5px; padding: 0 4px; }
    .cek-wrap .tip { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; border:1px solid var(--border); background: rgba(255,255,255,.80); box-shadow: var(--shadow-sm); }

    /* AJAX loading (hanya untuk manual submit) */
    .cek-wrap.is-loading .searchBox { opacity:.92; pointer-events:none; }
    .cek-wrap.is-loading #hasil { opacity:.65; pointer-events:none; }

    /* FLOATING PILL BUTTON */
    .fab-pill {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--navy-800);
        color: #ffffff;
        padding: 14px 24px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(26, 41, 66, 0.4);
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .fab-pill:hover {
        background: var(--lightblue-500);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 14px 30px rgba(96, 165, 250, 0.5);
        color: var(--navy-900);
    }

    .fab-pill i {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    .fab-pill:hover i {
        transform: translateX(-4px);
    }

    @media (max-width: 640px) {
        .fab-pill {
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            font-size: 13px;
        }
    }
  </style>
</head>
<body>

  @php
    $code     = $code ?? '';
    $searched = $searched ?? false;
    $detail   = $detail ?? null;
    $errorMsg = $errorMsg ?? null;
    $badge    = $badge ?? 'wait';
  @endphp

  <div class="cek-wrap" id="cekRoot">
    <div class="wrap">
      <div class="topbar">
        <div class="brand">
          <div class="logo"><i class="fa-solid fa-ticket"></i></div>
          <div class="t">
            <span>Cek Pengajuan</span>
            <small>Gunakan Kode Booking</small>
          </div>
        </div>

        <div class="pill" title="Waktu server">
          <i class="fa-regular fa-clock"></i>
          {{ now()->format('d-m-Y H:i') }}
        </div>
      </div>

      <section class="card">
        <div class="hero">
          <h1><i class="fa-solid fa-magnifying-glass-location" style="color:var(--lightblue-500)"></i> Lacak Status Pengajuan</h1>
          <div class="subtitle">
            Masukkan <b>Kode Booking</b> lalu klik <b>Cari</b>. Status pengajuan Anda akan diperbarui secara otomatis secara *real-time*.
          </div>
        </div>

        <div class="content">
          <div class="searchBox">
            <form method="get" action="{{ route('history.index') }}" id="cekForm">
              <div class="searchRow">
                <div class="input">
                  <i class="fa-solid fa-ticket"></i>
                  <input
                    type="text"
                    name="code"
                    value="{{ $code }}"
                    placeholder="Contoh: LPKIA-AB12CD"
                    autocomplete="off"
                    required
                  >
                </div>

                <button class="btn primary" type="submit">
                  <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>

                @if($code !== '')
                  <a class="btn" href="{{ route('history.index') }}" id="cekReset">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                  </a>
                @endif
              </div>

              <div class="hint">
                <i class="fa-solid fa-circle-info"></i>
                Jika lupa kode, silakan hubungi <b>Biro Administrasi Akademik</b>.
                <span style="opacity:.8;">Contoh format: <code>LPKIA-XXXXXX</code></span>
              </div>
            </form>
          </div>

          <div id="hasil"></div>

          @if($searched)
            @if($detail)
              <div class="result" id="resultBox">
                <div class="result-h">
                  <div style="font-weight:800; font-size:13.5px; color: var(--navy-900);">
                    <i class="fa-solid fa-circle-check" style="color:var(--ok)"></i>
                    Data ditemukan: <span style="letter-spacing:.2px;">{{ $detail->public_code }}</span>
                  </div>

                  <span class="badge {{ $badge }}">
                    <i class="fa-solid fa-circle"></i>
                    Status Admin: {{ ucfirst($detail->status ?? 'menunggu') }}
                  </span>
                </div>

                <div class="result-b">
                  <div class="grid">
                    <div class="kv">
                      <div class="k">Ruangan</div>
                      <div class="v">{{ $detail->room_name ?? '-' }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Waktu</div>
                      <div class="v">
                        @php
                          $st = $detail->start_time ?? null;
                          $et = $detail->end_time ?? null;
                        @endphp

                        {{ $st ? \Carbon\Carbon::parse($st)->format('d-m-Y H:i') : '-' }}
                        — {{ $et ? \Carbon\Carbon::parse($et)->format('d-m-Y H:i') : '-' }}
                      </div>
                    </div>

                    <div class="kv">
                      <div class="k">Penanggung Jawab</div>
                      <div class="v">{{ $detail->responsible_name ?? '-' }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Organisasi</div>
                      <div class="v">{{ $detail->org_name ?? '-' }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Status Kemahasiswaan</div>
                      <div class="v">{{ ucfirst($detail->kema_status ?? 'menunggu') }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Catatan Kemahasiswaan</div>
                      <div class="v">{{ $detail->kema_note ?? '-' }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Status Admin</div>
                      <div class="v">{{ ucfirst($detail->status ?? 'menunggu') }}</div>
                    </div>

                    <div class="kv">
                      <div class="k">Catatan Admin</div>
                      <div class="v">{{ $detail->admin_note ?? '-' }}</div>
                    </div>
                  </div>

                  @if(!empty($detail->letter_file))
                    <div style="margin-top:16px;">
                      <a class="btn primary" href="{{ asset($detail->letter_file) }}" target="_blank" rel="noopener">
                        <i class="fa-solid fa-file-arrow-up"></i> Lihat Surat
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            @else
              <div class="empty" id="resultBox">
                <i class="fa-solid fa-triangle-exclamation" style="font-size:32px; color:var(--warn); margin-bottom:12px; display:block;"></i>
                {!! $errorMsg ?: "Data tidak ditemukan. Jika lupa kode, hubungi Biro Administrasi Akademik." !!}
              </div>
            @endif
          @endif

          <div class="foot">
          </div>
        </div>
      </section>
    </div>
  </div>

  <a href="{{ url('/') }}" class="fab-pill" title="Kembali ke Beranda">
      <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>

  @if($searched)
    <script>
      const el = document.getElementById('hasil');
      if (el) el.scrollIntoView({behavior:'smooth', block:'start'});
    </script>
  @endif

  {{-- AJAX dengan Auto-Refresh (Silent Polling) --}}
  <script>
  (function(){
    var root = document.getElementById('cekRoot');
    var form = document.getElementById('cekForm');
    if (!root || !form) return;

    var __ajaxBusy = false;
    var pollInterval = null;
    var currentPollUrl = null; // Menyimpan URL pencarian terakhir yang valid

    function setLoading(on){
      if (on) root.classList.add('is-loading');
      else root.classList.remove('is-loading');
    }

    // Fungsi untuk me-replace HTML tanpa berkedip atau melompat
    function replaceResultFromHtml(html, isSilent = false){
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, 'text/html');

      // Update isi input (abaikan jika ini update background agar tidak mengganggu ketikan)
      var newInput = doc.querySelector('#cekForm input[name="code"]');
      var curInput = form.querySelector('input[name="code"]');
      if (newInput && curInput && !isSilent) {
          curInput.value = newInput.value;
      }

      // Update tombol reset
      var newReset = doc.getElementById('cekReset');
      var curReset = document.getElementById('cekReset');
      if (newReset && !curReset){
        var btnCari = form.querySelector('button[type="submit"]');
        if (btnCari && btnCari.parentNode){
          btnCari.insertAdjacentHTML('afterend', "\n" + newReset.outerHTML + "\n");
        }
      } else if (!newReset && curReset){
        curReset.remove();
      }

      var oldBox = document.getElementById('resultBox');
      var newBox = doc.getElementById('resultBox') || doc.querySelector('.result') || doc.querySelector('.empty');

      // Replace box hasil pencarian
      if (oldBox && newBox) {
         // Hanya ganti jika isinya benar-benar berbeda untuk menghindari kedip
         if (oldBox.innerHTML !== newBox.innerHTML) {
             oldBox.outerHTML = newBox.outerHTML;
         }
      } else if (newBox && !oldBox) {
         var hasilEl = document.getElementById('hasil');
         if (hasilEl) hasilEl.insertAdjacentHTML('afterend', "\n" + newBox.outerHTML + "\n");
      } else if (!newBox && oldBox) {
         oldBox.remove();
      }

      // Auto scroll hanya jika submit manual, abaikan jika background polling
      var hasilEl = document.getElementById('hasil');
      if (hasilEl && !isSilent) {
        try { hasilEl.scrollIntoView({behavior:'smooth', block:'start'}); } catch(e){}
      }
    }

    function ajaxLoad(url, push){
      if (__ajaxBusy) return;
      __ajaxBusy = true;
      setLoading(true);
      stopAutoRefresh(); // Hentikan auto-refresh sementara saat manual fetch

      fetch(url, {
        method: 'GET',
        headers: {'X-Requested-With':'XMLHttpRequest', 'Accept':'text/html'},
        credentials: 'same-origin'
      })
      .then(function(res){
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
      })
      .then(function(html){
        replaceResultFromHtml(html, false);
        currentPollUrl = url; // Simpan URL untuk di-poll
        startAutoRefresh();   // Nyalakan kembali polling

        if (push !== false){
          try { window.history.pushState({url:url}, '', url); } catch(e){}
        }
      })
      .catch(function(){
        window.location.assign(url);
      })
      .finally(function(){
        setLoading(false);
        __ajaxBusy = false;
      });
    }

    // -----------------------------------------------------
    // Fitur Auto Refresh (Silent Polling Background)
    // -----------------------------------------------------
    function startAutoRefresh() {
        stopAutoRefresh();
        if (!currentPollUrl) return;

        pollInterval = setInterval(function() {
            if (__ajaxBusy) return; // Jangan bentrok jika user sedang submit manual

            fetch(currentPollUrl, {
                method: 'GET',
                headers: {'X-Requested-With':'XMLHttpRequest', 'Accept':'text/html'},
                credentials: 'same-origin'
            })
            .then(function(res){ return res.ok ? res.text() : Promise.reject(); })
            .then(function(html){
                replaceResultFromHtml(html, true); // true = update diam-diam (silent)
            })
            .catch(function(e){
                // Abaikan error saat background poll (misal koneksi terputus sesaat)
            });
        }, 10000); // 10000 ms = Cek perubahan setiap 10 Detik
    }

    function stopAutoRefresh() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Cek apakah halaman dibuka dengan menyertakan ?code= dari awal
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('code')) {
        currentPollUrl = window.location.href;
        startAutoRefresh();
    }

    // submit ajax manual
    form.addEventListener('submit', function(e){
      e.preventDefault();

      var action = form.getAttribute('action') || window.location.pathname;
      var params = new URLSearchParams(new FormData(form));

      var code = (params.get('code') || '').trim();
      if (!code) return;

      var url = action + '?' + params.toString();
      ajaxLoad(url, true);
    });

    // reset ajax (delegation)
    document.addEventListener('click', function(e){
      var a = e.target.closest('#cekReset');
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href) return;
      
      e.preventDefault();
      e.stopPropagation();
      
      stopAutoRefresh(); // Matikan auto-refresh karena kode dikosongkan
      currentPollUrl = null; 
      ajaxLoad(href, true);
    });

    // back/forward history ajax
    window.addEventListener('popstate', function(ev){
      var url = (ev && ev.state && ev.state.url) ? ev.state.url : window.location.href;
      
      // Deteksi apakah saat user back, ada parameter code
      var pastParams = new URL(url).searchParams;
      if (pastParams.get('code')) {
          currentPollUrl = url;
      } else {
          currentPollUrl = null;
          stopAutoRefresh();
      }

      ajaxLoad(url, false);
    });

  })();
  </script>
</body>
</html>