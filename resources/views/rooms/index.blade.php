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
        --shadow-card: 0 2px 8px -2px rgba(26, 41, 66, 0.08);

        --radius-sm: 0.5rem;
        --radius: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.25rem;

        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;

        /* Mapped Base Variables */
        --bg: var(--bg-main); 
        --card: var(--bg-elevated); 
        --text: var(--text-primary); 
        --muted: var(--text-secondary); 
        --border: var(--border-light);
        --shadow2: var(--shadow-md);
        
        --accent: var(--lightblue-500); 
        --accent2: var(--lightblue-500); 
        --accent-soft: var(--lightblue-50);
        
        --baa-top: var(--lightblue-100);
        --baa-text: var(--navy-900);
      }

      * { box-sizing:border-box; }
      html { scroll-behavior: smooth; }
      body {
        margin: 0;
        background: var(--bg);
        color: var(--text);
        font-family: var(--font-sans);
        font-size: 13px;
        line-height: 1.6;
        padding-bottom: 90px;
      }

      /* --- CUSTOM SCROLLBAR --- */
      ::-webkit-scrollbar { width: 6px; height: 6px; }
      ::-webkit-scrollbar-track { background: var(--neutral-50); border-radius: 4px; }
      ::-webkit-scrollbar-thumb { background: var(--lightblue-300); border-radius: 4px; }
      ::-webkit-scrollbar-thumb:hover { background: var(--lightblue-500); }

      /* =========================================
         1. BAA HEADER
         ========================================= */
      .baa-topbar {
        background: var(--baa-top);
        color: var(--baa-text);
        padding: 8px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
      }
      .baa-topbar-left, .baa-topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
      }
      .baa-topbar i { color: var(--navy-600); font-size: 14px; }
      
      .baa-navbar {
        background: var(--bg-elevated);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 40px;
        box-shadow: var(--shadow-sm);
        position: relative;
        z-index: 50;
      }
      .baa-logo {
        font-weight: 900;
        font-size: 18px;
        color: var(--navy-900);
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
      .baa-menu a:hover { color: var(--lightblue-500); }

      /* =========================================
         2. APP HEADER
         ========================================= */
      .app-header {
        background: var(--bg-elevated);
        text-align: center;
        padding: 40px 16px 30px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 30px;
      }
      .app-title {
        font-size: 28px;
        font-weight: 900;
        color: var(--navy-900);
        margin: 0;
        letter-spacing: -0.02em;
      }

      /* =========================================
         3. FLOATING BACK BUTTON
         ========================================= */
      .floating-back-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--lightblue-500);
        color: #ffffff;
        padding: 14px 24px;
        border-radius: 999px;
        display: flex;
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
      .floating-back-btn:hover {
        background: var(--lightblue-500);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 14px 30px rgba(96, 165, 250, 0.5);
        color: var(--navy-900);
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
         4. MAIN CONTENT
         ========================================= */
      .container { max-width: 1140px; margin: 0 auto 24px auto; padding: 0 16px; }

      /* Toolbar */
      .toolbar {
        background: rgba(255,255,255,.9);
        border: 1px solid var(--border); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm);
        padding: 12px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; backdrop-filter: blur(10px);
      }
      .search, .select {
        display: flex; align-items: center; gap: 8px; padding: 10px 12px; border: 1px solid var(--border);
        border-radius: var(--radius-sm); background: var(--neutral-50); transition: all 0.2s;
      }
      .search { min-width: 260px; flex: 1; }
      .search:focus-within, .select:focus-within {
        border-color: var(--lightblue-500); background: #ffffff; box-shadow: 0 0 0 3px var(--lightblue-50);
      }
      .search input { border: none; outline: none; width: 100%; font: inherit; font-weight: 600; font-size: 13px; background: transparent; color: var(--navy-800); }
      .select select { border: none; outline: none; font: inherit; font-weight: 600; font-size: 13px; background: transparent; cursor: pointer; color: var(--navy-800); }
      .search i, .select i { color: var(--text-tertiary); }

      .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border);
        background: #fff; color: var(--navy-800); text-decoration: none; font-weight: 700; font-size: 13px; cursor: pointer; transition: all .2s ease; white-space: nowrap;
      }
      .btn:hover { background: var(--lightblue-50); border-color: var(--lightblue-300); transform: translateY(-1px); color: var(--navy-900); box-shadow: var(--shadow-sm); }
      .btn.primary { background: var(--accent); color: #fff; border-color: var(--accent); box-shadow: var(--shadow-sm); }
      .btn.primary:hover { background: var(--lightblue-500); color: var(--navy-900); border-color: var(--lightblue-500); transform: translateY(-2px); box-shadow: var(--shadow-md); }
      .btn.disabled { opacity: .55; pointer-events: none; transform: none; }

      /* List & Cards */
      .list { margin-top: 12px; display: flex; flex-direction: column; gap: 12px; }
      .row {
        background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm); overflow: hidden; display: grid;
        grid-template-columns: 220px 1fr 220px; transition: transform 0.2s, box-shadow 0.2s;
      }
      .row:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--lightblue-300); }

      .thumb { position: relative; min-height: 150px; background: linear-gradient(135deg, var(--lightblue-50), var(--lightblue-100)); overflow: hidden; display: flex; align-items: center; justify-content: center; cursor: zoom-in; }
      .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; background: var(--neutral-50); }
      .thumb::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0), rgba(15,23,42,.18)); }
      .thumb .noimg { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 8px; color: var(--navy-600); font-weight: 800; z-index: 2; }
      .thumb .pillCount {
        position: absolute; left: 10px; top: 10px; z-index: 3; display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,.95); border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); font-weight: 800; font-size: 11.5px; color: var(--navy-900); user-select: none;
      }

      .content { padding: 16px; }
      .name { margin: 0; font-size: 16px; font-weight: 800; color: var(--navy-900); }
      
      .roomStatus {
        margin-top: 10px; display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 12px; border-radius: 999px; border: 1px solid; font-weight: 700; font-size: 12px; text-transform: capitalize;
      }
      .roomStatus .dot, .modalStatusChip .dot { width: 8px; height: 8px; border-radius: 999px; display: inline-block; }

      /* Chips Colors Adjusted to new Palette */
      .chip-tersedia { background: var(--lightblue-50); border-color: var(--lightblue-300) !important; color: var(--navy-800); }
      .chip-tersedia .dot, .modalStatusChip.chip-tersedia .dot { background: var(--lightblue-500); box-shadow: 0 0 0 3px var(--lightblue-100); }
      
      .chip-segera { background: #FFF7ED; border-color: #FDBA74 !important; color: #9A3412; }
      .chip-segera .dot, .modalStatusChip.chip-segera .dot { background: #F97316; box-shadow: 0 0 0 3px #FFEDD5; }
      
      .chip-digunakan { background: #FEF2F2; border-color: #FDA4AF !important; color: #9F1239; }
      .chip-digunakan .dot, .modalStatusChip.chip-digunakan .dot { background: #E11D48; box-shadow: 0 0 0 3px #FFE4E6; }
      
      .chip-tidak-tersedia { background: var(--neutral-50); border-color: var(--neutral-200) !important; color: var(--text-tertiary); }
      .chip-tidak-tersedia .dot, .modalStatusChip.chip-tidak-tersedia .dot { background: var(--text-secondary); box-shadow: 0 0 0 3px var(--neutral-100); }

      .sub { margin-top: 12px; color: var(--muted); font-weight: 600; font-size: 12px; display: flex; align-items: center; gap: 6px; }
      .sub i { color: var(--lightblue-500); }

      .actions { padding: 16px; border-left: 1px solid var(--border); display: flex; flex-direction: column; gap: 10px; justify-content: center; background: rgba(255,255,255,.6); }
      .actions .btn { width: 100%; }

      @media(max-width: 768px) {
        .baa-navbar { flex-direction: column; gap: 16px; padding: 16px; align-items: flex-start; }
        .baa-menu { flex-wrap: wrap; gap: 12px; }
      }
      @media(max-width: 640px) {
        .app-title { font-size: 22px; }
        .row { grid-template-columns: 1fr; display: flex; gap: 10px; padding: 10px; align-items: center; }
        .thumb { width: 80px; height: 80px; min-height: 80px; border-radius: var(--radius-sm); flex: 0 0 80px; }
        .thumb::after { display: none; }
        .content { padding: 4px 0; flex: 1; }
        .actions { padding: 0; border-left: none; background: transparent; flex: 0 0 110px; width: 110px; }
        .actions .btn { padding: 8px 10px; font-size: 11.5px; border-radius: 8px; }
        .thumb .pillCount { left: 6px; top: 6px; padding: 4px 6px; font-size: 10px; }
      }

      /* Pagination */
      .paginationWrap { margin-top: 14px; display: flex; justify-content: center; }
      .pagination { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; background: rgba(255,255,255,.9); border: 1px solid var(--border); border-radius: 999px; padding: 8px 12px; box-shadow: var(--shadow-sm); backdrop-filter: blur(10px); }
      .pageItem { min-width: 36px; height: 36px; padding: 0 10px; border-radius: 999px; border: 1px solid transparent; background: transparent; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: var(--navy-800); text-decoration: none; transition: all .2s; }
      .pageItem:hover { background: var(--lightblue-50); border-color: var(--lightblue-300); transform: translateY(-1px); }
      .pageItem.active { background: var(--lightblue-500); color: #fff; border-color: var(--lightblue-500); box-shadow: var(--shadow-sm); }
      .pageItem.disabled { opacity: .5; pointer-events: none; transform: none; }

      /* Modals */
      .modalOverlay { position: fixed; inset: 0; display: none; place-items: center; background: rgba(15, 23, 42, 0.4); z-index: 9999; padding: 16px; backdrop-filter: blur(4px); }
      .modalOverlay.show { display: grid; }
      .modal { width: min(520px, 100%); background: var(--bg-elevated); border: 1px solid var(--border-light); border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); overflow: hidden; transform: translateY(10px) scale(0.98); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
      .modalOverlay.show .modal { transform: translateY(0) scale(1); opacity: 1; }
      .modalHead { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border-bottom: 1px solid var(--border-light); background: var(--neutral-50); }
      .modalHead .ttl { font-weight: 800; display: flex; gap: 10px; align-items: center; font-size: 15px; color: var(--navy-900); }
      .modalHead .ttl i { color: var(--lightblue-500); }
      .modalBody { padding: 20px; color: var(--navy-900); }
      .modalFoot { padding: 16px 20px; display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border-light); background: var(--neutral-50); }

      .detailGrid { display: grid; grid-template-columns: 140px 1fr; gap: 18px; align-items: start; }
      .detailThumb { width: 140px; height: 140px; border-radius: var(--radius-sm); overflow: hidden; background: linear-gradient(135deg, var(--lightblue-50), var(--lightblue-100)); border: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center; cursor: zoom-in; box-shadow: var(--shadow-sm); }
      .detailThumb img { width: 100%; height: 100%; object-fit: cover; display: block; background: var(--neutral-50); }
      .detailThumb .ico { font-size: 32px; color: var(--navy-600); opacity: .9; }

      .detailInfo { display: flex; flex-direction: column; gap: 12px; }
      .infoItem { display: flex; align-items: center; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px dashed var(--border-regular); }
      .infoItem:last-child { border-bottom: none; padding-bottom: 0; }
      .infoLabel { color: var(--text-tertiary); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.02em; }
      .infoValue { color: var(--navy-900); font-weight: 800; font-size: 13px; text-align: right; max-width: 65%; }
      .modalStatusChip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-weight: 700; font-size: 11.5px; text-transform: capitalize; border: 1px solid; }

      @media(max-width: 640px) { .detailGrid { grid-template-columns: 1fr; gap: 14px; } .detailThumb { width: 100%; height: 160px; } .infoValue { max-width: 100%; } }

      .warnOverlay { position: fixed; inset: 0; display: none; place-items: center; background: rgba(15, 23, 42, 0.5); z-index: 10000; padding: 16px; backdrop-filter: blur(4px); }
      .warnOverlay.show { display: grid; }
      .warnBox { width: min(560px, 100%); background: var(--bg-elevated); border: 1px solid var(--border-light); border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); overflow: hidden; transform: translateY(10px) scale(0.98); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
      .warnOverlay.show .warnBox { transform: translateY(0) scale(1); opacity: 1; }
      .warnHead { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border-bottom: 1px solid var(--border-light); background: var(--neutral-50); }
      .warnHead .ttl { font-weight: 800; display: flex; gap: 10px; align-items: center; font-size: 14px; color: var(--navy-900); }
      .warnBody { padding: 16px 20px; color: var(--navy-900); font-weight: 600; font-size: 13px; line-height: 1.6; }
      .warnBody .hint { margin-top: 12px; padding: 12px; border: 1px dashed var(--border-regular); border-radius: var(--radius-sm); background: var(--neutral-50); color: var(--text-secondary); font-weight: 600; font-size: 12px; }
      .warnFoot { padding: 16px 20px; display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border-light); background: var(--neutral-50); }
      .btnWarn { background: var(--accent); color: #fff; border-color: transparent; }
      .btnWarn:hover { background: var(--lightblue-500); color: var(--navy-900); transform: translateY(-1px); box-shadow: var(--shadow-sm); }

      .is-loading .list { opacity: .55; pointer-events: none; filter: saturate(.9); }
      .is-loading .paginationWrap { opacity: .6; pointer-events: none; }
      .is-loading .toolbar { pointer-events: none; opacity: .9; }

      .gOverlay { position: fixed; inset: 0; display: none; place-items: center; background: rgba(15, 23, 42, 0.85); z-index: 11000; padding: 16px; backdrop-filter: blur(8px); }
      .gOverlay.show { display: grid; }
      .gBox { width: min(860px, 100%); background: var(--bg-elevated); border: 1px solid var(--border-light); border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); overflow: hidden; transform: translateY(10px) scale(0.98); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
      .gOverlay.show .gBox { transform: translateY(0) scale(1); opacity: 1; }
      .gHead { padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border-bottom: 1px solid var(--border-light); background: var(--neutral-50); }
      .gHead .ttl { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 14px; color: var(--navy-900); min-width: 0; }
      .gHead .ttl i { color: var(--lightblue-500); }
      .gHead .ttl .muted { color: var(--text-tertiary); font-weight: 700; font-size: 12px; }
      .gStage { position: relative; background: var(--navy-900); display: flex; align-items: center; justify-content: center; height: min(62vh, 520px); }
      .gStage img { width: 100%; height: 100%; object-fit: contain; display: block; user-select: none; -webkit-user-drag: none; }
      .gNavBtn { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: var(--radius-sm); border: 1px solid rgba(255,255,255,.2); background: rgba(15, 23, 42, 0.4); color: #fff; display: grid; place-items: center; cursor: pointer; transition: all .2s; backdrop-filter: blur(8px); }
      .gNavBtn:hover { background: rgba(96, 165, 250, 0.8); color: var(--navy-900); }
      .gPrev { left: 16px; } .gNext { right: 16px; }
      .gNavBtn[disabled] { opacity: .35; pointer-events: none; }
      .gFoot { padding: 14px 20px; background: var(--neutral-50); border-top: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
      .gDots { display: flex; align-items: center; gap: 6px; }
      .gDot { width: 8px; height: 8px; border-radius: 999px; background: var(--neutral-300); transition: background 0.2s; }
      .gDot.active { background: var(--lightblue-500); }
      .gThumbs { display: flex; gap: 8px; align-items: center; overflow: auto; max-width: 100%; padding-bottom: 4px; }
      .gThumb { width: 50px; height: 50px; border-radius: var(--radius-sm); border: 2px solid transparent; background: #fff; overflow: hidden; cursor: pointer; flex: 0 0 auto; opacity: .7; transition: all .2s; }
      .gThumb:hover { opacity: 1; transform: translateY(-2px); }
      .gThumb.active { border-color: var(--lightblue-500); opacity: 1; }
      .gThumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
      
      @media(max-width: 640px) { .gStage { height: min(55vh, 420px); } .gNavBtn { width: 38px; height: 38px; } .gThumb { width: 44px; height: 44px; } }
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
      <div style="padding: 24px; border: 1px solid var(--border-light); border-radius: var(--radius-xl); background: var(--bg-elevated); box-shadow: var(--shadow-sm); color: var(--text-tertiary); font-weight: 700; text-align: center;">
        <i class="fa-solid fa-circle-info" style="font-size: 32px; color: var(--lightblue-500); margin-bottom: 12px; display: block;"></i> 
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
        <i class="fa-solid fa-building"></i>
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
      <button class="btn primary btnWarn" type="button" id="warnContinue">Lanjut Ajukan</button>
    </div>
  </div>
</div>

<div class="gOverlay" id="gOverlay" aria-hidden="true">
  <div class="gBox" role="dialog" aria-modal="true" aria-labelledby="gTitle">
    <div class="gHead">
      <div class="ttl" id="gTitle">
        <i class="fa-regular fa-images"></i>
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