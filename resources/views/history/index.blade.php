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

      --font-sans:'Inter', system-ui, -apple-system, sans-serif;
      --fs-xs:0.7rem;
      --fs-sm:0.85rem;
      --fs-base:0.95rem;
      --fs-md:1.1rem;
      --fs-lg:1.25rem;
      --fs-xl:1.5rem;
      --fs-3xl:2.25rem;
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

    .cek-wrap{
      background:
        radial-gradient(900px 320px at 15% -10%, rgba(37,99,235,.16), transparent 55%),
        radial-gradient(900px 320px at 85% -10%, rgba(16,185,129,.12), transparent 55%),
        var(--bg);
      min-height:calc(100vh - 20px);
      padding: 18px 0 90px; 
    }

    .cek-wrap .wrap{width:100%;max-width: 980px;margin: 0 auto;padding: 18px 14px 26px;}
    .cek-wrap .topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;border-radius: 18px;border:1px solid rgba(226,232,240,.9);background: rgba(255,255,255,.82);backdrop-filter: blur(14px);box-shadow: var(--shadow2);}
    
    .cek-wrap .brand{display:flex; align-items:center; gap:10px;min-width:0;font-weight:900;letter-spacing:.2px;}
    .cek-wrap .brand .logo{width:36px;height:36px;border-radius:12px;display:grid;place-items:center;background: linear-gradient(135deg, rgba(37,99,235,.95), rgba(14,165,233,.85));box-shadow: 0 18px 40px rgba(37,99,235,.18);color:#fff;flex:0 0 auto;border:1px solid rgba(255,255,255,.35);}
    .cek-wrap .brand .t{display:flex;flex-direction:column;line-height:1.15;min-width:0;}
    .cek-wrap .brand .t span{white-space:nowrap;overflow:hidden;text-overflow:ellipsis; font-size: 14.5px; font-weight: 800;}
    .cek-wrap .brand .t small{margin-top:2px;color:var(--muted);font-weight:600;font-size: 11px;}
    
    .cek-wrap .pill{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;border:1px solid rgba(226,232,240,.9);background:#fff;color:var(--muted);font-weight:700;font-size:11.5px;white-space:nowrap;}
    
    .cek-wrap .card{margin-top:12px;background: rgba(255,255,255,.92);border:1px solid rgba(226,232,240,.9);border-radius: var(--radius);box-shadow: var(--shadow);overflow:hidden;}
    .cek-wrap .hero{padding:16px 14px 14px;background:radial-gradient(900px 240px at 12% 0%, rgba(37,99,235,.14), transparent 60%),rgba(255,255,255,.96);border-bottom:1px solid rgba(241,245,249,.9);}
    
    .cek-wrap h1{margin:0;font-size: 18px;font-weight:900;letter-spacing:.2px;line-height:1.2;display:flex;gap:8px;align-items:center;}
    .cek-wrap .subtitle{margin:6px 0 0;color:var(--muted);font-weight:500;font-size: 12.5px;max-width: 80ch;}
    
    .cek-wrap .content{ padding:14px; }
    .cek-wrap .searchBox{border:1px solid rgba(226,232,240,.9);border-radius:18px;background:#fff;box-shadow: var(--shadow2);padding:12px;}
    .cek-wrap .searchRow{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
    .cek-wrap .input{flex:1 1 240px;display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid rgba(226,232,240,.95);border-radius:16px;background:#fff;transition:.15s ease;}
    .cek-wrap .input:focus-within{border-color: rgba(37,99,235,.28);box-shadow: 0 0 0 4px rgba(37,99,235,.10);}
    .cek-wrap .input i{ color:rgba(100,116,139,.95); }
    .cek-wrap .input input{width:100%;border:none;outline:none;font:inherit;font-weight:800;background:transparent;font-size:13.5px;letter-spacing:.3px;text-transform: uppercase;}
    
    .cek-wrap .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:999px;border:1px solid rgba(226,232,240,.95);background:#fff;color:var(--text);text-decoration:none;font-weight:800;font-size:12.5px;cursor:pointer;transition:.16s ease;box-shadow: var(--shadow2);white-space:nowrap;}
    .cek-wrap .btn:hover{transform: translateY(-1px);background: var(--accentSoft);border-color: rgba(37,99,235,.18);}
    .cek-wrap .btn.primary{background: linear-gradient(135deg, var(--accent), var(--accent2));border-color: transparent;color:#fff;box-shadow: 0 10px 20px rgba(37,99,235,.18);}
    .cek-wrap .btn.primary:hover{background: var(--accent2); box-shadow: 0 12px 25px rgba(37,99,235,.25);}
    
    .cek-wrap .hint{margin-top:12px;color:var(--muted);font-weight:500;font-size:11.5px;display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
    .cek-wrap .hint code{background: rgba(248,250,252,.9);border:1px solid rgba(226,232,240,.9);padding:2px 8px;border-radius:999px;font-weight:700;font-size:11px;}
    
    .cek-wrap .result{margin-top:16px;border:1px solid rgba(226,232,240,.9);border-radius:18px;background:#fff;box-shadow: var(--shadow2);overflow:hidden;}
    .cek-wrap .result-h{padding:14px 16px;border-bottom:1px solid rgba(241,245,249,.9);background: rgba(248,250,252,.75);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
    .cek-wrap .badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:999px;font-weight:800;font-size:11px;white-space:nowrap;border:1px solid rgba(226,232,240,.9);background:#fff;color:var(--muted); text-transform:uppercase; letter-spacing:0.05em;}
    .cek-wrap .badge.wait{ border-color: rgba(245,158,11,.20); background: var(--warnSoft); color:#92400e; }
    .cek-wrap .badge.ok{ border-color: rgba(22,163,74,.18); background: var(--okSoft); color:#166534; }
    .cek-wrap .badge.err{ border-color: rgba(239,68,68,.18); background: var(--dangerSoft); color:#991b1b; }
    .cek-wrap .badge.soft{ border-color: rgba(37,99,235,.18); background: var(--accentSoft); color: var(--accent2); }
    .cek-wrap .result-b{ padding:16px; }
    
    .cek-wrap .grid{display:grid;grid-template-columns: 1fr;gap:12px;}
    @media(min-width:720px){.cek-wrap .grid{ grid-template-columns: 1fr 1fr; }}
    
    .cek-wrap .kv{border:1px solid rgba(226,232,240,.95);border-radius:16px;padding:14px;background: rgba(248,250,252,.75);}
    .cek-wrap .k{ color:var(--muted); font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:0.03em;}
    .cek-wrap .v{ margin-top:6px; font-weight:800; font-size:13px; word-break:break-word; color: var(--text); }
    
    .cek-wrap .empty{margin-top:16px;padding:18px;border-radius:18px;border:1px dashed rgba(148,163,184,.55);background: rgba(255,255,255,.85);color:var(--muted);font-weight:600; font-size:13px; text-align:center;}
    .cek-wrap .foot{margin-top:16px;display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center;color:var(--muted);font-weight:600;font-size:11.5px;padding: 0 4px;}
    .cek-wrap .tip{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;border:1px solid rgba(226,232,240,.9);background: rgba(255,255,255,.80);box-shadow: var(--shadow2);}

    /* ✅ AJAX loading (tanpa ubah UI lain) */
    .cek-wrap.is-loading .searchBox{opacity:.92;pointer-events:none}
    .cek-wrap.is-loading #hasil{opacity:.65;pointer-events:none}

    /* ✅ FLOATING PILL BUTTON (KEMBALI) - DESAIN BIRU SOLID */
    .fab-pill {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--accent);
        color: #ffffff;
        padding: 14px 24px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .fab-pill:hover {
        background: var(--accent2);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.5);
        color: #ffffff;
    }

    .fab-pill i {
        font-size: 16px;
        color: #ffffff;
        transition: transform 0.3s ease;
    }

    .fab-pill:hover i {
        transform: translateX(-4px);
        color: #ffffff;
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
          
          <div class="subtitle">
            Masukkan <b>Kode Booking</b> lalu klik <b>Cari</b>. Hasil akan muncul di bawah.
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
                  <div style="font-weight:800; font-size:13.5px;">
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
                <i class="fa-solid fa-triangle-exclamation" style="font-size:24px; color:var(--warn); margin-bottom:8px; display:block;"></i>
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

  {{-- ✅ AJAX tanpa refresh (submit / reset / back-forward) --}}
  <script>
  (function(){
    var root = document.getElementById('cekRoot');
    var form = document.getElementById('cekForm');
    if (!root || !form) return;

    var __ajaxBusy = false;

    function setLoading(on){
      if (on) root.classList.add('is-loading');
      else root.classList.remove('is-loading');
    }

    function extractResultHTML(doc){
      // ambil konten result: #hasil + (result/empty jika ada)
      var hasil = doc.getElementById('hasil');
      var box   = doc.getElementById('resultBox');

      var html = '';
      if (hasil) html += hasil.outerHTML;

      // kalau halaman hasil ada resultBox (result / empty), ikut dipindah
      if (box) html += box.outerHTML;

      // fallback: kalau server render result/empty tapi id tidak ada, cari via class
      if (!box){
        var altResult = doc.querySelector('.result');
        var altEmpty  = doc.querySelector('.empty');
        if (altResult) html += altResult.outerHTML;
        else if (altEmpty) html += altEmpty.outerHTML;
      }

      return html;
    }

    function replaceResultFromHtml(html){
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, 'text/html');

      // sync input value
      var newInput = doc.querySelector('#cekForm input[name="code"]');
      var curInput = form.querySelector('input[name="code"]');
      if (newInput && curInput) curInput.value = newInput.value;

      // sync tombol reset (muncul/hilang) tanpa ubah struktur lain
      var newReset = doc.getElementById('cekReset');
      var curReset = document.getElementById('cekReset');
      if (newReset && !curReset){
        // sisipkan setelah tombol cari (di row yang sama)
        var btnCari = form.querySelector('button[type="submit"]');
        if (btnCari && btnCari.parentNode){
          btnCari.insertAdjacentHTML('afterend', "\n" + newReset.outerHTML + "\n");
        }
      } else if (!newReset && curReset){
        curReset.remove();
      }

      // hapus resultBox lama (jika ada)
      var oldBox = document.getElementById('resultBox');
      if (oldBox) oldBox.remove();

      // pastikan #hasil tetap ada (sudah ada di halaman)
      var hasilEl = document.getElementById('hasil');
      if (!hasilEl) return;

      // inject resultBox baru (result / empty) setelah #hasil
      var newBox = doc.getElementById('resultBox') || doc.querySelector('.result') || doc.querySelector('.empty');
      if (newBox){
        hasilEl.insertAdjacentHTML('afterend', "\n" + newBox.outerHTML + "\n");
      }

      // scroll ke hasil
      try { hasilEl.scrollIntoView({behavior:'smooth', block:'start'}); } catch(e){}
    }

    function ajaxLoad(url, push){
      if (__ajaxBusy) return;
      __ajaxBusy = true;
      setLoading(true);

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
        replaceResultFromHtml(html);

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

    // submit ajax
    form.addEventListener('submit', function(e){
      e.preventDefault();

      var action = form.getAttribute('action') || window.location.pathname;
      var params = new URLSearchParams(new FormData(form));

      var code = (params.get('code') || '').trim();
      if (!code){
        // required sudah mencegah, tapi jaga-jaga
        return;
      }

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
      ajaxLoad(href, true);
    });

    // back/forward ajax
    window.addEventListener('popstate', function(ev){
      var url = (ev && ev.state && ev.state.url) ? ev.state.url : window.location.href;
      ajaxLoad(url, false);
    });
  })();
  </script>
</body>
</html>
website peminjaman ruangan di institut digital ekonomi lpkia bandung, dengan fitur cek status pengajuan berdasarkan kode booking yang diberikan oleh admin setelah melakukan peminjaman ruangan. pengguna dapat memasukkan kode booking untuk melihat detail peminjaman, termasuk status admin, catatan, dan informasi terkait lainnya. fitur ini memudahkan pengguna untuk memantau status pengajuan mereka tanpa harus menghubungi admin secara langsung. dan bisa di cek juga di email untuk konfirmasi peminjaman ruangan nya sesuai dengan email yang anda isi di form peminjaman ruangan.