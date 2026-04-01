<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ruangan - Peminjaman LPKIA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
      :root{
        --bg:#f6f7fb; --card:#fff; --text:#0f172a; --muted:#64748b; --border:#e6e8ef;
        --shadow2:0 10px 26px rgba(15,23,42,.06);
        --accent:#2563eb; --accent2:#1d4ed8; --accent-soft:#eff6ff;
        
        /* BAA Colors */
        --baa-top:#e0e7ff;
        --baa-text:#334155;
      }
      *{box-sizing:border-box}
      body{
        margin:0;
        background:var(--bg);
        color:var(--text);
        font-family:'Inter', sans-serif;
        font-size:12.5px;
        line-height:1.55;
        padding-bottom: 90px; /* Ruang agar konten terbawah tidak tertutup tombol kembali */
      }

      /* =========================================
         1. BAA HEADER (Simulasi Master Layout)
         ========================================= */
      .baa-topbar {
        background: var(--baa-top);
        color: var(--baa-text);
        padding: 8px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 600;
      }
      .baa-topbar-left, .baa-topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
      }
      .baa-topbar i { color: #64748b; font-size: 14px; }
      
      .baa-navbar {
        background: #ffffff;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 40px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        position: relative;
        z-index: 50;
      }
      .baa-logo {
        font-weight: 900;
        font-size: 18px;
        color: #1e3a8a;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .baa-logo img { height: 32px; }
      .baa-menu {
        display: flex;
        gap: 24px;
        list-style: none;
        margin: 0; padding: 0;
      }
      .baa-menu a {
        text-decoration: none;
        color: var(--baa-text);
        font-weight: 600;
        font-size: 14px;
        transition: color 0.2s;
      }
      .baa-menu a:hover { color: var(--accent); }

      /* =========================================
         2. APP HEADER
         ========================================= */
      .app-header {
        background: #ffffff;
        text-align: center;
        padding: 40px 16px 30px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 30px;
      }
      .app-title {
        font-size: 28px;
        font-weight: 900;
        color: var(--text);
        margin: 0;
        letter-spacing: -0.02em;
      }

      /* =========================================
         3. FLOATING BACK BUTTON (Kanan Bawah)
         ========================================= */
      .floating-back-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--accent);
        color: #ffffff;
        padding: 14px 24px;
        border-radius: 999px;
        display: flex;
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
      .floating-back-btn:hover {
        background: var(--accent2);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.5);
        color: #ffffff;
      }
      .floating-back-btn i {
        font-size: 16px;
        transition: transform 0.3s ease;
      }
      .floating-back-btn:hover i {
        transform: translateX(-4px);
      }

      @media(max-width: 640px) {
        .floating-back-btn {
          bottom: 20px;
          right: 20px;
          padding: 12px 20px;
          font-size: 13px;
        }
      }

      /* =========================================
         4. MAIN CONTENT (Ruangan)
         ========================================= */
      .container{ max-width:1140px; margin: 0 auto 24px auto; padding:0 16px }

      /* Toolbar */
      .toolbar{
        background:rgba(255,255,255,.86);
        border:1px solid var(--border);border-radius:18px;box-shadow:var(--shadow2);
        padding:12px;display:flex;gap:10px;align-items:center;flex-wrap:wrap;backdrop-filter:blur(10px)
      }
      .search{
        display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid var(--border);
        border-radius:14px;min-width:260px;background:#fff;flex:1
      }
      .search input{border:none;outline:none;width:100%;font:inherit;font-weight:800;font-size:12.6px;background:transparent}
      .select{
        display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid var(--border);
        border-radius:14px;background:#fff
      }
      select{border:none;outline:none;font:inherit;font-weight:900;font-size:12.4px;background:transparent; cursor:pointer;}

      .btn{
        display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 12px;border-radius:14px;border:1px solid var(--border);
        background:#fff;color:#111;text-decoration:none;font-weight:900;font-size:12.2px;cursor:pointer;transition:all .2s ease;white-space:nowrap
      }
      .btn:hover{background:var(--accent-soft);transform:translateY(-1px)}
      .btn.primary { background: var(--accent); color: #fff; border-color: transparent; box-shadow: 0 2px 6px rgba(37,99,235,0.25); }
      .btn.primary:hover { background: var(--accent2); transform: translateY(-2px); box-shadow: 0 6px 14px rgba(37,99,235,0.35); }
      .btn.disabled{opacity:.55;pointer-events:none;transform:none}

      /* List & Cards */
      .list{margin-top:12px;display:flex;flex-direction:column;gap:12px;}
      .row{
        background:var(--card); border:1px solid var(--border); border-radius:18px;
        box-shadow:var(--shadow2); overflow:hidden; display:grid;
        grid-template-columns: 220px 1fr 220px; transition: transform 0.2s, box-shadow 0.2s;
      }
      .row:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(15,23,42,.1); }

      .thumb{ position:relative; min-height:150px; background:linear-gradient(135deg,#eef2ff,#e0e7ff); overflow:hidden; display:flex; align-items:center; justify-content:center; cursor: zoom-in; }
      .thumb img{ width:100%; height:100%; object-fit:cover; display:block; background:#f8fafc; }
      .thumb::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.18))}
      .thumb .noimg{ position:absolute;inset:0;display:flex;align-items:center;justify-content:center;gap:8px; color:rgba(255,255,255,.92);font-weight:950;z-index:2; }
      .thumb .pillCount{
        position:absolute;left:10px;top:10px;z-index:3; display:inline-flex;align-items:center;gap:7px;
        padding:7px 9px;border-radius:999px; background: rgba(255,255,255,.92); border:1px solid rgba(226,232,240,.9);
        box-shadow: 0 10px 26px rgba(15,23,42,.08); font-weight:950;font-size:11.5px;color:#0f172a; user-select:none;
      }

      .content{padding:16px;}
      .name{margin:0;font-size:15px;font-weight:950;color:#0f172a;}
      .roomStatus{
        margin-top:10px; display:inline-flex; align-items:center; gap:8px;
        padding:6px 12px; border-radius:999px; border:1px solid; font-weight:900; font-size:11.8px; text-transform: capitalize;
      }
      .roomStatus .dot, .modalStatusChip .dot{width:8px;height:8px;border-radius:999px;display:inline-block;}

      .chip-tersedia { background: #ecfdf5; border-color: #a7f3d0 !important; color: #047857; }
      .chip-tersedia .dot, .modalStatusChip.chip-tersedia .dot { background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.2); }
      .chip-segera { background: #fffbeb; border-color: #fde68a !important; color: #b45309; }
      .chip-segera .dot, .modalStatusChip.chip-segera .dot { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.2); }
      .chip-digunakan { background: #fff1f2; border-color: #fecdd3 !important; color: #be123c; }
      .chip-digunakan .dot, .modalStatusChip.chip-digunakan .dot { background: #e11d48; box-shadow: 0 0 0 3px rgba(225,29,72,0.2); }
      .chip-tidak-tersedia { background: #f8fafc; border-color: #e2e8f0 !important; color: #475569; }
      .chip-tidak-tersedia .dot, .modalStatusChip.chip-tidak-tersedia .dot { background: #64748b; box-shadow: 0 0 0 3px rgba(100,116,139,0.2); }

      .sub{ margin-top:12px; color:var(--muted); font-weight:700; font-size:12px; display:flex; align-items:center; gap:6px; }
      .sub i { color: #5d0bf5; }

      .actions{ padding:16px; border-left:1px solid var(--border); display:flex;flex-direction:column;gap:10px;justify-content:center; background: rgba(255,255,255,.6); }
      .actions .btn{width:100%}

      @media(max-width:768px){
        .baa-navbar { flex-direction: column; gap: 16px; padding: 16px; align-items: flex-start; }
        .baa-menu { flex-wrap: wrap; gap: 12px; }
      }
      @media(max-width:640px){
        .app-title { font-size: 22px; }
        .row{ grid-template-columns: 1fr; display:flex; gap:10px; padding:10px; align-items:center; }
        .thumb{ width:80px;height:80px;min-height:80px; border-radius:14px; flex:0 0 80px; }
        .thumb::after{display:none}
        .content{padding:4px 0;flex:1}
        .actions{ padding:0;border-left:none;background:transparent; flex:0 0 110px; width:110px; }
        .actions .btn{padding:8px 10px;font-size:11.5px;border-radius:12px}
        .btn{transform:none}
        .thumb .pillCount{left:6px;top:6px;padding:4px 6px;font-size:10px}
      }

      /* Pagination */
      .paginationWrap{margin-top:14px;display:flex;justify-content:center;}
      .pagination{ display:flex;gap:8px;flex-wrap:wrap;align-items:center; background: rgba(255,255,255,.86); border:1px solid var(--border); border-radius:999px; padding:10px 12px; box-shadow: var(--shadow2); backdrop-filter: blur(10px); }
      .pageItem{ min-width:36px;height:34px;padding:0 10px;border-radius:999px;border:1px solid var(--border); background:#fff;display:inline-flex;align-items:center;justify-content:center; font-weight:950;font-size:12.2px;color:#0f172a;text-decoration:none;transition:.16s; }
      .pageItem:hover{background:var(--accent-soft);transform:translateY(-1px)}
      .pageItem.active{background: var(--accent);color:#fff;border-color:transparent;}
      .pageItem.disabled{opacity:.5;pointer-events:none;transform:none}

      /* Modals */
      .modalOverlay{position:fixed; inset:0;display:none; place-items:center;background:rgba(2,6,23,.55);z-index:9999; padding:16px; backdrop-filter:blur(4px);}
      .modalOverlay.show{ display:grid; }
      .modal{ width:min(520px, 100%); background:rgba(255,255,255,.96); border:1px solid rgba(226,232,240,.9); border-radius:22px; box-shadow:0 22px 70px rgba(15,23,42,.25); overflow:hidden; backdrop-filter: blur(10px); transform: translateY(8px); opacity:0; transition:.18s ease; }
      .modalOverlay.show .modal{transform: translateY(0);opacity: 1;}
      .modalHead{ padding:14px 18px; display:flex;align-items:center;justify-content:space-between;gap:10px; border-bottom:1px solid rgba(226,232,240,.9); background:rgba(255,255,255,.8); }
      .modalHead .ttl{font-weight:950;display:flex;gap:10px;align-items:center;font-size:14px;}
      .modalBody{padding:18px;color:#0f172a;}
      .modalFoot{padding:14px 18px;display:flex;gap:10px;justify-content:flex-end;border-top:1px solid rgba(226,232,240,.9);background:rgba(255,255,255,.8);}

      .detailGrid{ display:grid; grid-template-columns: 140px 1fr; gap:18px; align-items:start; }
      .detailThumb{ width:140px;height:140px;border-radius:18px;overflow:hidden; background:linear-gradient(135deg,#eef2ff,#e0e7ff); border:1px solid rgba(226,232,240,.9); display:flex;align-items:center;justify-content:center; cursor: zoom-in; box-shadow:0 4px 12px rgba(15,23,42,.05); }
      .detailThumb img{ width:100%; height:100%; object-fit:cover; display:block; background:#f8fafc; }
      .detailThumb .ico{font-size:32px;color:#1d4ed8;opacity:.9}

      .detailInfo { display: flex; flex-direction: column; gap: 12px; }
      .infoItem { display: flex; align-items: center; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px dashed rgba(226,232,240,.8); }
      .infoItem:last-child { border-bottom: none; padding-bottom: 0; }
      .infoLabel { color: var(--muted); font-weight: 800; font-size: 12.2px; }
      .infoValue { color: #0f172a; font-weight: 900; font-size: 12.8px; text-align: right; max-width: 65%; }
      .modalStatusChip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-weight: 900; font-size: 11.5px; text-transform: capitalize; border: 1px solid; }

      @media(max-width:640px){ .detailGrid{grid-template-columns: 1fr; gap:14px;} .detailThumb{width:100%; height:160px; border-radius:16px;} .infoValue {max-width: 100%;} }

      .warnOverlay{position:fixed; inset:0;display:none; place-items:center;background:rgba(2,6,23,.55);z-index:10000; padding:16px; backdrop-filter:blur(4px);}
      .warnOverlay.show{display:grid}
      .warnBox{ width:min(560px, 100%); background:rgba(255,255,255,.94); border:1px solid rgba(226,232,240,.9); border-radius:22px; box-shadow:0 22px 70px rgba(15,23,42,.25); overflow:hidden; backdrop-filter: blur(10px); transform: translateY(8px); opacity:0; transition:.18s ease; }
      .warnOverlay.show .warnBox{transform:translateY(0);opacity:1}
      .warnHead{ padding:12px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px; border-bottom:1px solid rgba(226,232,240,.9);background:rgba(255,255,255,.75); }
      .warnHead .ttl{font-weight:950;display:flex;gap:10px;align-items:center;font-size:13px;}
      .warnBody{padding:12px 14px;color:#0f172a;font-weight:800;font-size:12.2px;line-height:1.55;}
      .warnBody .hint{margin-top:10px;padding:10px;border:1px dashed rgba(226,232,240,.95);border-radius:14px;background:#fff;color:#475569;font-weight:800;font-size:11.8px}
      .warnFoot{padding:12px 14px;display:flex;gap:10px;justify-content:flex-end;border-top:1px solid rgba(226,232,240,.9);background:rgba(255,255,255,.75);}
      .btnWarn{background:var(--accent);color:#fff;border-color:transparent}
      .btnWarn:hover{background:var(--accent2);transform:translateY(-1px);}

      .is-loading .list{opacity:.55;pointer-events:none;filter:saturate(.9)}
      .is-loading .paginationWrap{opacity:.6;pointer-events:none}
      .is-loading .toolbar{pointer-events:none;opacity:.9}

      .gOverlay{position:fixed; inset:0;display:none; place-items:center;background:rgba(2,6,23,.75);z-index:11000; padding:16px; backdrop-filter:blur(8px);}
      .gOverlay.show{display:grid}
      .gBox{ width:min(860px, 100%); background:rgba(255,255,255,.92); border:1px solid rgba(226,232,240,.9); border-radius:22px; box-shadow:0 22px 70px rgba(15,23,42,.28); overflow:hidden; backdrop-filter: blur(10px); transform: translateY(10px); opacity:0; transition:.18s ease; }
      .gOverlay.show .gBox{transform:translateY(0);opacity:1}
      .gHead{ padding:10px 12px; display:flex;align-items:center;justify-content:space-between;gap:10px; border-bottom:1px solid rgba(226,232,240,.9); background:rgba(255,255,255,.75); }
      .gHead .ttl{ display:flex;align-items:center;gap:10px; font-weight:950;font-size:12.8px;color:#0f172a; min-width:0; }
      .gHead .ttl .muted{color:var(--muted);font-weight:900;font-size:12px}
      .gStage{ position:relative; background: #0b1220; display:flex;align-items:center;justify-content:center; height: min(62vh, 520px); }
      .gStage img{ width:100%;height:100%; object-fit:contain; display:block; user-select:none; -webkit-user-drag:none; }
      .gNavBtn{ position:absolute;top:50%;transform:translateY(-50%); width:40px;height:40px;border-radius:14px; border:1px solid rgba(226,232,240,.25); background:rgba(255,255,255,.14); color:#fff; display:grid;place-items:center; cursor:pointer; transition:.15s; backdrop-filter: blur(8px); }
      .gNavBtn:hover{background:rgba(255,255,255,.22)}
      .gPrev{left:12px} .gNext{right:12px}
      .gNavBtn[disabled]{opacity:.35;pointer-events:none}
      .gFoot{ padding:10px 12px; background:rgba(255,255,255,.75); border-top:1px solid rgba(226,232,240,.9); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
      .gDots{display:flex;align-items:center;gap:6px}
      .gDot{ width:7px;height:7px;border-radius:999px; background:rgba(15,23,42,.18); }
      .gDot.active{background:var(--accent)}
      .gThumbs{ display:flex;gap:8px;align-items:center; overflow:auto; max-width:100%; padding-bottom:2px; }
      .gThumb{ width:46px;height:46px;border-radius:14px; border:1px solid rgba(226,232,240,.9); background:#fff; overflow:hidden; cursor:pointer; flex:0 0 auto; opacity:.8; transition:.14s; }
      .gThumb:hover{opacity:1;transform:translateY(-1px)}
      .gThumb.active{outline:2px solid var(--accent);opacity:1}
      .gThumb img{width:100%;height:100%;object-fit:cover;display:block}
      @media(max-width:640px){ .gStage{height: min(55vh, 420px);} .gNavBtn{width:38px;height:38px;border-radius:14px} .gThumb{width:44px;height:44px;border-radius:14px} }
    </style>
</head>
<body>

<main class="container">
  <form class="toolbar" method="get" action="{{ route('ruangan.index') }}">
    <div class="search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama ruangan / lantai / fasilitas...">
    </div>

    <div class="select">
      <i class="fa-solid fa-filter"></i>
      <select name="filter" onchange="this.form.submit()">
        <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
        <option value="segera_digunakan" {{ ($filter ?? '') === 'segera_digunakan' ? 'selected' : '' }}>Ruangan Segera Digunakan</option>
        <option value="sedang_digunakan" {{ ($filter ?? '') === 'sedang_digunakan' ? 'selected' : '' }}>Ruangan Sedang Digunakan</option>
        <option value="tidak_tersedia" {{ ($filter ?? '') === 'tidak_tersedia' ? 'selected' : '' }}>Ruangan Tidak Tersedia</option>
      </select>
    </div>

    <button class="btn primary" type="submit"><i class="fa-solid fa-wand-magic-sparkles"></i> Terapkan</button>
    <a class="btn" href="{{ route('ruangan.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset</a>
  </form>

  <section class="list">
    @forelse($rooms as $row)
      @php
        $pinjamHref = route('pinjam.create', $row->id);

        $photos = [];
        if (!empty($row->photo_urls) && is_array($row->photo_urls)) $photos = $row->photo_urls;
        elseif (!empty($row->photos) && is_array($row->photos)) $photos = $row->photos;
        elseif (!empty($row->photo_url)) $photos = [$row->photo_url];

        $photos = array_values(array_filter($photos, function($x){
          return !empty($x);
        }));

        $photoSrc = !empty($photos) ? $photos[0] : null;
        $photosAttr = htmlspecialchars(json_encode($photos), ENT_QUOTES, 'UTF-8');

        $chipClass = $row->chipClass ?? 'chip-tersedia';
        $chipText  = $row->chipText ?? 'ruangan tersedia';

        $subText = '';
        if (!empty($row->warnMsg) && trim(strtolower($row->warnMsg)) !== trim(strtolower($chipText))) {
            $subText = $row->warnMsg;
        }

        $warnForAjukan = trim((string)($row->warnMsgAjukan ?? ''));
        if ($warnForAjukan === '' && !empty($row->warnMsgToday)) {
          $warnForAjukan = trim((string)$row->warnMsgToday);
        }
      @endphp

      <article class="row">
        <div class="thumb js-open-gallery"
             role="button"
             tabindex="0"
             aria-label="Lihat foto ruangan"
             data-name="{{ e($row->name ?? '-') }}"
             data-photos="{{ $photosAttr }}"
             data-start="0">
          @if($photoSrc)
            <img src="{{ $photoSrc }}" alt="Foto ruangan">
            @if(count($photos) > 1)
              <div class="pillCount" title="Jumlah foto">
                <i class="fa-regular fa-images"></i> {{ count($photos) }}
              </div>
            @endif
          @else
            <div class="noimg">
              <i class="fa-solid fa-building"></i> No Photo
            </div>
          @endif
        </div>

        <div class="content">
          <h3 class="name">{{ $row->name ?? '-' }}</h3>

          <div class="roomStatus {{ $chipClass }}">
            <span class="dot"></span>
            <span>{{ $chipText }}</span>
          </div>

          @if($subText)
            <div class="sub">
              <i class="fa-solid fa-circle-info"></i> {{ $subText }}
            </div>
          @endif
        </div>

        <div class="actions">
          <a class="btn primary btn-ajukan {{ !empty($row->disableAjukan) ? 'disabled' : '' }}"
             href="{{ !empty($row->disableAjukan) ? 'javascript:void(0)' : $pinjamHref }}"
             data-href="{{ $pinjamHref }}"
             data-warn="{{ e($warnForAjukan) }}">
            <i class="fa-solid fa-paper-plane"></i> Ajukan
          </a>

          <button
            type="button"
            class="btn btn-detail"
            data-id="{{ $row->id }}"
            data-name="{{ e($row->name ?? '-') }}"
            data-floor="{{ e($row->floor ?? '-') }}"
            data-capacity="{{ (int)($row->capacity ?? 0) }}"
            data-facilities="{{ e($row->facilities ?? '-') }}"
            data-photo="{{ e($photoSrc ?? '') }}"
            data-photos="{{ $photosAttr }}"
            data-status="{{ e($chipText) }}"
            data-chip-class="{{ $chipClass }}"
          >
            <i class="fa-solid fa-circle-info"></i> Detail
          </button>
        </div>
      </article>
    @empty
      <div style="padding:18px;border:1px solid var(--border);border-radius:18px;background:#fff;box-shadow:var(--shadow2);color:var(--muted);font-weight:800; text-align:center;">
        <i class="fa-solid fa-circle-info" style="font-size:24px; color:var(--accent); margin-bottom:8px; display:block;"></i> 
        Tidak ada ruangan yang ditemukan sesuai filter.
      </div>
    @endforelse
  </section>

  @if($rooms instanceof \Illuminate\Pagination\LengthAwarePaginator && $rooms->lastPage() > 1)
    @php $rooms->appends(request()->query()); @endphp
    <div class="paginationWrap">
      <nav class="pagination" aria-label="Pagination">
        @if($rooms->onFirstPage())
          <span class="pageItem disabled"><i class="fa-solid fa-chevron-left"></i></span>
        @else
          <a class="pageItem" href="{{ $rooms->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
        @endif

        @php
          $current = $rooms->currentPage();
          $last = $rooms->lastPage();
          $start = max(1, $current - 2);
          $end = min($last, $current + 2);
        @endphp

        @if($start > 1)
          <a class="pageItem" href="{{ $rooms->url(1) }}">1</a>
          @if($start > 2) <span class="pageItem disabled">…</span> @endif
        @endif

        @for($p = $start; $p <= $end; $p++)
          @if($p == $current)
            <span class="pageItem active">{{ $p }}</span>
          @else
            <a class="pageItem" href="{{ $rooms->url($p) }}">{{ $p }}</a>
          @endif
        @endfor

        @if($end < $last)
          @if($end < $last - 1) <span class="pageItem disabled">…</span> @endif
          <a class="pageItem" href="{{ $rooms->url($last) }}">{{ $last }}</a>
        @endif

        @if($rooms->hasMorePages())
          <a class="pageItem" href="{{ $rooms->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
        @else
          <span class="pageItem disabled"><i class="fa-solid fa-chevron-right"></i></span>
        @endif
      </nav>
    </div>
  @endif
</main>

<a href="{{ url('/') }}" class="floating-back-btn">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

<div class="modalOverlay" id="detailOverlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
    <div class="modalHead">
      <div class="ttl" id="detailTitle">
        <i class="fa-solid fa-building" style="color:#2563eb;"></i>
        <span id="m_name">Detail Ruangan</span>
      </div>
      <button class="btn" style="padding:6px 10px;" type="button" id="detailCloseX">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="modalBody">
      <div class="detailGrid">
        <div class="detailThumb js-open-gallery" id="m_thumb"
             role="button" tabindex="0"
             aria-label="Lihat foto ruangan"
             data-name=""
             data-photos="[]"
             data-start="0">
          <span class="ico"><i class="fa-solid fa-building"></i></span>
        </div>

        <div class="detailInfo">
          <div class="infoItem">
            <div class="infoLabel">Status Ruangan</div>
            <div class="infoValue">
              <span id="m_status" class="modalStatusChip"></span>
            </div>
          </div>
          <div class="infoItem">
            <div class="infoLabel">Lantai</div>
            <div class="infoValue" id="m_floor">—</div>
          </div>
          <div class="infoItem">
            <div class="infoLabel">Kapasitas</div>
            <div class="infoValue" id="m_capacity">—</div>
          </div>
          <div class="infoItem">
            <div class="infoLabel">Fasilitas</div>
            <div class="infoValue" id="m_facilities">—</div>
          </div>
        </div>
      </div>
    </div>

    <div class="modalFoot">
      <button class="btn" type="button" id="detailCloseBtn">Tutup</button>
    </div>
  </div>
</div>

<div class="warnOverlay" id="warnOverlay" aria-hidden="true">
  <div class="warnBox" role="dialog" aria-modal="true" aria-labelledby="warnTitle">
    <div class="warnHead">
      <div class="ttl" id="warnTitle">
        <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;"></i>
        <span>Peringatan</span>
      </div>
      <button class="btn" style="padding:6px 10px;" type="button" id="warnCloseX">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="warnBody" id="warnBody">—</div>
    <div class="warnFoot">
      <button class="btn" type="button" id="warnCancel">Batal</button>
      <button class="btn btnWarn" type="button" id="warnContinue">Lanjut Ajukan</button>
    </div>
  </div>
</div>

<div class="gOverlay" id="gOverlay" aria-hidden="true">
  <div class="gBox" role="dialog" aria-modal="true" aria-labelledby="gTitle">
    <div class="gHead">
      <div class="ttl" id="gTitle">
        <i class="fa-regular fa-images" style="color:var(--accent);"></i>
        <span id="gRoomName" style="min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Foto Ruangan</span>
        <span class="muted" id="gCounter">1/1</span>
      </div>
      <button class="btn" style="padding:6px 10px;" type="button" id="gCloseX"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div class="gStage" id="gStage">
      <button class="gNavBtn gPrev" type="button" id="gPrev" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
      <img id="gImg" src="" alt="Foto ruangan">
      <button class="gNavBtn gNext" type="button" id="gNext" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <div class="gFoot">
      <div class="gDots" id="gDots"></div>
      <div class="gThumbs" id="gThumbs"></div>
    </div>
  </div>
</div>

<script>
function esc(str){
  return String(str || '').replace(/[&<>"']/g, function(m){
    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[m];
  });
}
function safeJson(val){
  try {
    var s = String(val || '[]');
    s = s.replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&amp;/g, '&');
    return JSON.parse(s);
  } catch(e){
    return [];
  }
}

var __ajaxBusy = false;

function setLoading(on){
  var root = document.body;
  if (on) root.classList.add('is-loading');
  else root.classList.remove('is-loading');
}

function replaceDomFromHtml(html){
  var parser = new DOMParser();
  var doc = parser.parseFromString(html, 'text/html');

  var newList = doc.querySelector('section.list');
  var curList = document.querySelector('section.list');
  if (newList && curList){
    curList.outerHTML = newList.outerHTML;
  }

  var newPag = doc.querySelector('.paginationWrap');
  var curPag = document.querySelector('.paginationWrap');

  if (newPag && curPag){
    curPag.outerHTML = newPag.outerHTML;
  } else if (newPag && !curPag){
    var listNow = document.querySelector('section.list');
    if (listNow && listNow.parentNode){
      listNow.insertAdjacentHTML('afterend', newPag.outerHTML);
    }
  } else if (!newPag && curPag){
    curPag.remove();
  }

  var newForm = doc.querySelector('form.toolbar');
  var curForm = document.querySelector('form.toolbar');
  if (newForm && curForm){
    var newQ = newForm.querySelector('input[name="q"]');
    var curQ = curForm.querySelector('input[name="q"]');
    if (newQ && curQ) curQ.value = newQ.value;

    var newFilter = newForm.querySelector('select[name="filter"]');
    var curFilter = curForm.querySelector('select[name="filter"]');
    if (newFilter && curFilter) curFilter.value = newFilter.value;
  }
}

function ajaxLoad(url, push){
  if (__ajaxBusy) return;
  __ajaxBusy = true;
  setLoading(true);

  fetch(url, {
    method: 'GET',
    headers: {'X-Requested-With': 'XMLHttpRequest','Accept': 'text/html'},
    credentials: 'same-origin'
  })
  .then(function(res){
    if (!res.ok) throw new Error('HTTP ' + res.status);
    return res.text();
  })
  .then(function(html){
    replaceDomFromHtml(html);

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

(function(){
  var form = document.querySelector('form.toolbar');
  if (!form) return;

  form.addEventListener('submit', function(e){
    e.preventDefault();
    var action = form.getAttribute('action') || window.location.pathname;
    var params = new URLSearchParams(new FormData(form));

    var q = (params.get('q') || '').trim();
    if (!q) params.delete('q');

    var filter = (params.get('filter') || '').trim();
    if (!filter || filter === 'all') params.delete('filter');

    var url = action + (params.toString() ? ('?' + params.toString()) : '');
    ajaxLoad(url, true);
  });

  var filterSel = form.querySelector('select[name="filter"]');
  if (filterSel){
    filterSel.addEventListener('change', function(){
      try { form.requestSubmit ? form.requestSubmit() : form.submit(); } catch(e){}
    });
  }
})();

document.addEventListener('click', function(e){
  var a = e.target.closest('.pagination a.pageItem');
  if (!a) return;
  var href = a.getAttribute('href');
  if (!href) return;
  e.preventDefault();
  e.stopPropagation();
  ajaxLoad(href, true);
});

window.addEventListener('popstate', function(ev){
  var url = (ev && ev.state && ev.state.url) ? ev.state.url : window.location.href;
  ajaxLoad(url, false);
});

var gOverlay   = document.getElementById('gOverlay');
var gImg       = document.getElementById('gImg');
var gRoomName  = document.getElementById('gRoomName');
var gCounter   = document.getElementById('gCounter');
var gDots      = document.getElementById('gDots');
var gThumbs    = document.getElementById('gThumbs');
var gPrevBtn   = document.getElementById('gPrev');
var gNextBtn   = document.getElementById('gNext');

var __gPhotos = [];
var __gIndex  = 0;

function gSetIndex(idx){
  if (!__gPhotos.length) return;

  idx = Math.max(0, Math.min(__gPhotos.length - 1, idx));
  __gIndex = idx;

  gImg.src = __gPhotos[__gIndex] || '';
  gCounter.textContent = (String(__gIndex + 1) + '/' + String(__gPhotos.length));

  var dots = gDots.querySelectorAll('.gDot');
  for (var i=0; i<dots.length; i++){
    dots[i].classList.toggle('active', i === __gIndex);
  }

  var thumbs = gThumbs.querySelectorAll('.gThumb');
  for (var j=0; j<thumbs.length; j++){
    thumbs[j].classList.toggle('active', j === __gIndex);
  }

  gPrevBtn.disabled = (__gIndex === 0);
  gNextBtn.disabled = (__gIndex === __gPhotos.length - 1);

  var activeThumb = gThumbs.querySelector('.gThumb.active');
  if (activeThumb && activeThumb.scrollIntoView){
    activeThumb.scrollIntoView({behavior:'smooth', block:'nearest', inline:'nearest'});
  }
}

function openGallery(photos, startIndex, roomName){
  __gPhotos = (photos || []).filter(Boolean);
  if (!__gPhotos.length) return;

  gRoomName.textContent = roomName || 'Foto Ruangan';

  var dHtml = '';
  var tHtml = '';
  for (var i=0; i<__gPhotos.length; i++){
    dHtml += `<span class="gDot ${i===0?'active':''}"></span>`;
    tHtml += `
      <button type="button" class="gThumb ${i===0?'active':''}" data-idx="${i}" aria-label="Foto ${i+1}">
        <img src="${esc(__gPhotos[i])}" alt="Thumbnail ${i+1}">
      </button>
    `;
  }
  gDots.innerHTML = dHtml;
  gThumbs.innerHTML = tHtml;

  gOverlay.classList.add('show');
  gOverlay.setAttribute('aria-hidden','false');

  gSetIndex(Number(startIndex || 0));

  try { document.getElementById('gCloseX').focus(); } catch(e){}
}

function closeGallery(){
  gOverlay.classList.remove('show');
  gOverlay.setAttribute('aria-hidden','true');
  __gPhotos = [];
  __gIndex = 0;
  gImg.src = '';
  gDots.innerHTML = '';
  gThumbs.innerHTML = '';
}

gPrevBtn.addEventListener('click', function(){ gSetIndex(__gIndex - 1); });
gNextBtn.addEventListener('click', function(){ gSetIndex(__gIndex + 1); });
document.getElementById('gCloseX').addEventListener('click', closeGallery);

gThumbs.addEventListener('click', function(e){
  var b = e.target.closest('.gThumb');
  if (!b) return;
  var idx = Number(b.getAttribute('data-idx') || 0);
  gSetIndex(idx);
});

gOverlay.addEventListener('click', function(e){
  if (e.target && e.target.id === 'gOverlay') closeGallery();
});

(function(){
  var stage = document.getElementById('gStage');
  if (!stage) return;

  var startX = 0, startY = 0, moved = false;

  stage.addEventListener('touchstart', function(e){
    if (!e.touches || e.touches.length !== 1) return;
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    moved = false;
  }, {passive:true});

  stage.addEventListener('touchmove', function(e){
    moved = true;
  }, {passive:true});

  stage.addEventListener('touchend', function(e){
    if (!moved) return;
    var t = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0] : null;
    if (!t) return;

    var dx = t.clientX - startX;
    var dy = t.clientY - startY;

    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 35){
      if (dx < 0) gSetIndex(__gIndex + 1);
      else gSetIndex(__gIndex - 1);
    }
  }, {passive:true});
})();

function openDetailModal(btn){
  var name       = btn.getAttribute('data-name') || '—';
  var status     = btn.getAttribute('data-status') || '—';
  var chipClass  = btn.getAttribute('data-chip-class') || 'chip-tersedia';
  var floor      = btn.getAttribute('data-floor') || '—';
  var capacity   = btn.getAttribute('data-capacity') || '0';
  var facilities = btn.getAttribute('data-facilities') || '—';
  var photo      = btn.getAttribute('data-photo') || '';
  var photos     = safeJson(btn.getAttribute('data-photos')) || [];

  document.getElementById('m_name').textContent       = name;
  document.getElementById('m_floor').textContent      = 'Lt. ' + floor;
  document.getElementById('m_capacity').textContent   = capacity + ' org';
  document.getElementById('m_facilities').textContent = facilities;

  var mStatus = document.getElementById('m_status');
  mStatus.className = 'modalStatusChip ' + chipClass;
  mStatus.innerHTML = '<span class="dot"></span> <span>' + esc(status) + '</span>';

  var thumb = document.getElementById('m_thumb');

  thumb.setAttribute('data-name', name);
  thumb.setAttribute('data-photos', JSON.stringify(photos || []));
  thumb.setAttribute('data-start', '0');

  if (photo){
    thumb.innerHTML = `<img src="${esc(photo)}" alt="Foto ruangan">`;
  } else {
    thumb.innerHTML = `<span class="ico"><i class="fa-solid fa-building"></i></span>`;
  }

  var overlay = document.getElementById('detailOverlay');
  overlay.classList.add('show');
  overlay.setAttribute('aria-hidden','false');
}

function closeDetailModal(){
  var overlay = document.getElementById('detailOverlay');
  overlay.classList.remove('show');
  overlay.setAttribute('aria-hidden','true');
}

var warnOverlay = document.getElementById('warnOverlay');
var warnBody    = document.getElementById('warnBody');
var nextHref    = null;

function openWarn(message, href){
  nextHref = href || null;

  warnBody.innerHTML = `
    <div>${esc(message || '—')}</div>
    <div class="hint">
      Klik <b>Lanjut Ajukan</b> untuk menuju form peminjaman.
    </div>
  `;

  warnOverlay.classList.add('show');
  warnOverlay.setAttribute('aria-hidden','false');
}
function closeWarn(){
  warnOverlay.classList.remove('show');
  warnOverlay.setAttribute('aria-hidden','true');
  nextHref = null;
}

document.addEventListener('click', function(e){
  var detailBtn = e.target.closest('.btn-detail');
  if (detailBtn){
    openDetailModal(detailBtn);
    return;
  }

  var ajukanBtn = e.target.closest('.btn-ajukan');
  if (ajukanBtn){
    if (ajukanBtn.classList.contains('disabled')) {
      e.preventDefault();
      e.stopPropagation();
      return;
    }

    var warnMsg = (ajukanBtn.getAttribute('data-warn') || '').trim();
    if (!warnMsg) return;
    e.preventDefault();
    e.stopPropagation();
    var href = ajukanBtn.getAttribute('data-href') || ajukanBtn.getAttribute('href');
    openWarn(warnMsg, href);
    return;
  }

  var gBtn = e.target.closest('.js-open-gallery');
  if (gBtn){
    var photos = safeJson(gBtn.getAttribute('data-photos')) || [];
    if (!photos.length) return;

    var roomName = gBtn.getAttribute('data-name') || 'Foto Ruangan';
    var start = Number(gBtn.getAttribute('data-start') || 0);

    e.preventDefault?.();
    openGallery(photos, start, roomName);
    return;
  }
});

document.addEventListener('keydown', function(e){
  if (e.key !== 'Enter' && e.key !== ' ') return;
  var active = document.activeElement;
  if (!active || !active.classList || !active.classList.contains('js-open-gallery')) return;

  var photos = safeJson(active.getAttribute('data-photos')) || [];
  if (!photos.length) return;

  e.preventDefault();
  openGallery(photos, Number(active.getAttribute('data-start') || 0), active.getAttribute('data-name') || 'Foto Ruangan');
});

document.getElementById('detailCloseX')?.addEventListener('click', closeDetailModal);
document.getElementById('detailCloseBtn')?.addEventListener('click', closeDetailModal);

document.getElementById('detailOverlay')?.addEventListener('click', function(e){
  if (e.target.id === 'detailOverlay') closeDetailModal();
});

document.getElementById('warnCloseX')?.addEventListener('click', closeWarn);
document.getElementById('warnCancel')?.addEventListener('click', closeWarn);

document.getElementById('warnContinue')?.addEventListener('click', function(){
  if (nextHref) window.location.assign(nextHref);
  else closeWarn();
});

warnOverlay?.addEventListener('click', function(e){
  if (e.target.id === 'warnOverlay') closeWarn();
});

document.addEventListener('keydown', function(e){
  if (e.key === 'Escape'){
    closeDetailModal();
    closeWarn();
    closeGallery();
  }

  if (gOverlay.classList.contains('show')){
    if (e.key === 'ArrowLeft') gSetIndex(__gIndex - 1);
    if (e.key === 'ArrowRight') gSetIndex(__gIndex + 1);
  }
});
</script>
</body>
</html>