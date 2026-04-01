<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Peminjaman Ruangan LPKIA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root{
      /* Color Palette TEMA BIRU */
      --primary-50:#eff6ff; --primary-100:#dbeafe; --primary-200:#bfdbfe; --primary-300:#93c5fd;
      --primary-400:#60a5fa; --primary-500:#3b82f6; --primary-600:#2563eb; --primary-700:#1d4ed8;
      --primary-800:#1e40af; --primary-900:#1e3a8a;

      --neutral-50:#f8fafc; --neutral-100:#f1f5f9; --neutral-200:#e2e8f0; --neutral-300:#cbd5e1;
      --neutral-800:#1e293b; --neutral-900:#0f172a;
      
      --bg-main:#f4f5f8; 
      --bg-elevated:#ffffff;

      --text-primary:#0f172a;
      --text-secondary:#334155;
      --text-tertiary:#475569; 

      --border-light:#e2e8f0; 
      --border-regular:#cbd5e1;

      --shadow-sm:0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md:0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -1px rgb(0 0 0 / 0.06); 
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-card: 0 2px 8px -2px rgba(15, 23, 42, 0.12);

      --radius:0.75rem;
      --radius-xl:1rem;
      --radius-2xl:1.25rem;

      --font-sans:'Inter', system-ui, -apple-system, sans-serif;

      /* Font Sizes */
      --fs-xs:0.7rem;
      --fs-sm:0.85rem;
      --fs-base:0.95rem;
      --fs-md:1.1rem;
      --fs-lg:1.25rem;
      --fs-xl:1.5rem;
      --fs-3xl:2.25rem;

      /* Colors for Schedule Cards */
      --pbm-bg: #e0f2fe;     
      --pbm-border: #0284c7; 
      --pbm-text: #075985;   

      --mhs-bg: #dcfce7;     
      --mhs-border: #16a34a; 
      --mhs-text: #14532d;   

      --book-bg: #ffedd5;    
      --book-border: #ea580c;
      --book-text: #7c2d12;  
      
      --umum-bg: #f3e8ff;    
      --umum-border: #9333ea;
      --umum-text: #581c87;
    }

    *{margin:0;padding:0;box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      font-family:var(--font-sans);
      background:var(--bg-main);
      color:var(--text-primary);
      line-height:1.6;
      font-size:var(--fs-base);
    }

    /* --- CUSTOM SCROLLBAR --- */
    ::-webkit-scrollbar { width: 6px; height: 8px; }
    ::-webkit-scrollbar-track { background: var(--neutral-100); border-radius: 4px; }
    ::-webkit-scrollbar-thumb { background: var(--neutral-300); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--text-tertiary); }

    /* --- APP NAVIGATION BAR --- */
    .app-navbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem;
        z-index: 1000;
        transition: all 0.3s ease;
        background: transparent;
    }
    .app-navbar.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }
    .nav-brand {
        font-weight: 900;
        font-size: var(--fs-md);
        color: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        transition: color 0.3s;
    }
    .app-navbar.scrolled .nav-brand { color: var(--primary-800); }

    /* --- HERO SECTION --- */
    .hero{
      position:relative;
      min-height: clamp(400px, 60vh, 600px);
      display:flex;
      align-items:center;
      overflow:hidden;
      background:#0f172a;
    }
    .hero-bg{
      position:absolute;
      inset:0;
      background-image:url("{{ asset('foto/banner.png') }}");
      background-size:cover;
      background-position:center;
      background-repeat:no-repeat;
      transform:scale(1.05);
      filter: contrast(1.1) saturate(1.1) brightness(0.4);
    }
    .hero-content{
      position:relative;
      z-index:3;
      max-width:900px;
      margin: 0 auto;
      padding: 80px 1.5rem 3rem;
      color:#fff;
      text-align:center;
    }
    .hero-title{
      font-size:var(--fs-3xl);
      line-height:1.2;
      letter-spacing:-.02em;
      font-weight:900;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .hero-title span{
      background: linear-gradient(135deg, #bfdbfe, #60a5fa);
      -webkit-background-clip:text;
      background-clip:text;
      color:transparent;
    }
    .hero-desc{
      margin:1rem auto 0;
      max-width:650px;
      font-size:var(--fs-base);
      color:rgba(255,255,255,0.85);
    }

    /* --- CENTERED MAIN NAVIGATION (PILLS) --- */
    .hero-nav-pills {
      margin-top: 2rem;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1rem;
    }
    .nav-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.75rem 1.75rem;
      border-radius: 999px; /* Bentuk Kapsul */
      font-size: var(--fs-sm);
      font-weight: 800;
      text-decoration: none;
      transition: all 0.3s ease;
      /* Gaya Default (Tidak Aktif) untuk latar gelap */
      background: rgba(255, 255, 255, 0.1); 
      color: #ffffff;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
    }
    .nav-pill i {
      font-size: 1.1rem;
    }
    .nav-pill:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }
    /* Gaya State Aktif */
    .nav-pill.active {
      background: var(--primary-600);
      color: #ffffff;
      border-color: var(--primary-600);
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    }
    .nav-pill.active:hover {
      background: var(--primary-700);
    }

    /* --- MAIN LAYOUT --- */
    .main-content{
      max-width:1280px;
      margin:30px auto 40px; 
      padding:0 1.5rem;
      position: relative;
      z-index: 10;
    }
    .section-header{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:1rem;
      margin-bottom:1.5rem;
    }
    .section-title h2{ font-size:var(--fs-xl); font-weight:900; color: var(--text-primary); }
    .section-subtitle{ font-size:var(--fs-sm); color:var(--text-tertiary); margin-top:0.25rem; }

    /* --- ELEGANT SCHEDULE PANEL --- */
    .schedule-panel{
      background:var(--bg-elevated);
      border-radius:var(--radius-2xl);
      overflow:hidden;
      box-shadow:var(--shadow-md);
      border: 1px solid var(--border-light);
    }
    
    /* Toolbar / Header Panel */
    .schedule-toolbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:1rem;
      padding:1.25rem 1.5rem;
      background: #ffffff;
      border-bottom:1px solid var(--border-light);
      flex-wrap:wrap;
    }
    .toolbar-left{
      display:flex;
      align-items:center;
      gap:0.75rem;
      font-weight:900;
      font-size:var(--fs-base);
      color:var(--text-secondary);
    }
    .toolbar-left i { color: var(--primary-500); font-size: 1.2rem; }

    .filter-group{ display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
    .filter-item { position: relative; display: flex; align-items: center; }
    .filter-item i { position: absolute; left: 12px; color: var(--text-tertiary); font-size: 0.85rem; pointer-events: none; }
    .filter-item select, .filter-item input{
      padding:0.6rem 1rem 0.6rem 2.2rem;
      border-radius:var(--radius);
      border:1px solid var(--border-regular);
      background:var(--neutral-50);
      font-size:var(--fs-sm);
      font-weight: 600;
      color: var(--text-secondary);
      outline:none;
      transition: all 0.2s;
      cursor: pointer;
    }
    .filter-item select:hover, .filter-item input:hover { background: #ffffff; border-color: var(--primary-400); }
    .filter-item select:focus, .filter-item input:focus{
      border-color:var(--primary-600); background: #ffffff; box-shadow:0 0 0 3px var(--primary-100);
    }
    .loading-badge{
      display:none; align-items:center; gap:0.5rem; padding:0.5rem 1rem;
      border-radius:var(--radius); background:var(--primary-50); color:var(--primary-700);
      font-size:var(--fs-sm); font-weight:800;
    }
    .loading-badge.show{ display:inline-flex; }

    /* --- REVISED SCHEDULE TABLE --- */
    .schedule-shell{ transition:opacity 0.2s ease; background: var(--neutral-50); }
    .schedule-shell.is-loading{ opacity:0.5; pointer-events:none; }

    .schedule-table-wrap{
      width:100%;
      overflow-x:auto;
      max-height: 650px; 
      overflow-y:auto;
      position:relative;
      background: #ffffff;
    }
    .schedule-table{
      width:max-content;
      min-width:100%;
      border-collapse:collapse;
      table-layout: fixed; /* Membuat ukuran kolom fiks/kaku */
    }

    /* Table Grid Lines - DENGAN UKURAN FIKS */
    .schedule-table th, .schedule-table td {
      border: 1px solid var(--border-regular);
      padding: 0.6rem;
      vertical-align: top;
      width: 200px; /* Ukuran Lebar Fiks */
      min-width: 200px;
      max-width: 200px;
      overflow: hidden;
      word-wrap: break-word;
    }

    /* Sticky Top Header (Rooms) */
    .schedule-table thead th{
      position:sticky;
      top: 0;
      z-index:20;
      background: var(--neutral-200); 
      color:var(--text-primary);
      padding:1rem 0.75rem;
      text-align:center;
      box-shadow: 0 2px 4px -2px rgba(0,0,0,0.1);
    }
    .room-name { font-size:var(--fs-sm); font-weight:900; }
    .room-floor { font-size:var(--fs-xs); color:var(--text-tertiary); margin-top:0.25rem; font-weight: 600; }

    .schedule-cell{
      background:#ffffff;
      position: relative;
    }
    .schedule-cell:hover { background: var(--neutral-50); }

    .schedule-stack{ display:flex; flex-direction:column; gap:0.6rem; }

    /* --- MODERN & CONTRAST SCHEDULE CARDS --- */
    .schedule-card{
      position:relative;
      border-radius: 6px;
      padding:0.75rem;
      box-shadow: var(--shadow-card);
      transition: all 0.2s;
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
      cursor: pointer; 
    }
    .schedule-card:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow: var(--shadow-md);
      z-index: 2;
      filter: brightness(0.95);
    }

    .schedule-card.pbm{ background:var(--pbm-bg); border-left: 4px solid var(--pbm-border); border-right: 1px solid var(--pbm-bg); border-top: 1px solid var(--pbm-bg); border-bottom: 1px solid var(--pbm-bg);}
    .schedule-card.mahasiswa{ background:var(--mhs-bg); border-left: 4px solid var(--mhs-border); border-right: 1px solid var(--mhs-bg); border-top: 1px solid var(--mhs-bg); border-bottom: 1px solid var(--mhs-bg);}
    .schedule-card.booking_cepat{ background:var(--book-bg); border-left: 4px solid var(--book-border); border-right: 1px solid var(--book-bg); border-top: 1px solid var(--book-bg); border-bottom: 1px solid var(--book-bg);}
    
    /* REVISI WARNA UMUM */
    .schedule-card.umum{ background:var(--umum-bg); border-left: 4px solid var(--umum-border); border-right: 1px solid var(--umum-bg); border-top: 1px solid var(--umum-bg); border-bottom: 1px solid var(--umum-bg);}

    .schedule-head { display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; }
    
    .schedule-time{
      font-size:0.7rem;
      font-weight:900;
    }
    .schedule-card.pbm .schedule-time { color: var(--pbm-text); }
    .schedule-card.mahasiswa .schedule-time { color: var(--mhs-text); }
    .schedule-card.booking_cepat .schedule-time { color: var(--book-text); }
    .schedule-card.umum .schedule-time { color: var(--umum-text); }

    .schedule-type{
      font-size:0.6rem;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:0.04em;
      padding: 0.2rem 0.4rem;
      border-radius: 4px;
      background: rgba(255,255,255,0.8);
    }
    .schedule-card.pbm .schedule-type { color: var(--pbm-border); }
    .schedule-card.mahasiswa .schedule-type { color: var(--mhs-border); }
    .schedule-card.booking_cepat .schedule-type { color: var(--book-border); }
    .schedule-card.umum .schedule-type { color: var(--umum-border); }

    .schedule-course{
      font-size:var(--fs-sm);
      font-weight:800;
      line-height:1.3;
      display:-webkit-box;
      -webkit-line-clamp:3;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }
    .schedule-card.pbm .schedule-course { color: var(--pbm-text); }
    .schedule-card.mahasiswa .schedule-course { color: var(--mhs-text); }
    .schedule-card.booking_cepat .schedule-course { color: var(--book-text); }
    .schedule-card.umum .schedule-course { color: var(--umum-text); }

    .click-hint {
        font-size: 0.6rem;
        font-weight: 600;
        opacity: 0.6;
        margin-top: 0.2rem;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .schedule-card.pbm .click-hint { color: var(--pbm-text); }
    .schedule-card.mahasiswa .click-hint { color: var(--mhs-text); }
    .schedule-card.booking_cepat .click-hint { color: var(--book-text); }
    .schedule-card.umum .click-hint { color: var(--umum-text); }

    .schedule-empty-state{
      text-align:center;
      padding:4rem 1rem;
      color:var(--text-tertiary);
      font-size:var(--fs-base);
      font-weight: 600;
      background: #ffffff;
    }
    .schedule-empty-state i {
        font-size: 2.5rem;
        color: var(--neutral-300);
        margin-bottom: 1rem;
        display: block;
    }

    /* --- INFO FOOTER STRIP --- */
    .info-strip{
      display:flex; flex-wrap:wrap; gap:1rem; padding:1rem 1.5rem;
      background:#ffffff; border-top:1px solid var(--border-light);
    }
    .info-pill{
      display:inline-flex; align-items:center; gap:0.5rem;
      font-size:var(--fs-sm); color:var(--text-secondary); font-weight:700;
    }
    .info-pill i { color: var(--primary-600); }

    /* --- MODAL POP-UP STYLES --- */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden;
        transition: all 0.3s ease;
        padding: 1.5rem;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    
    .modal-content {
        background: #ffffff;
        width: 100%; max-width: 450px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
    }
    .modal-overlay.active .modal-content { transform: translateY(0) scale(1); }

    .modal-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--neutral-50);
    }
    .modal-header h3 { font-size: var(--fs-md); font-weight: 900; color: var(--text-primary); margin: 0; }
    .modal-close {
        background: transparent; border: none;
        color: var(--text-tertiary); font-size: 1.25rem;
        cursor: pointer; transition: color 0.2s;
        display: flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%;
    }
    .modal-close:hover { color: var(--text-primary); background: var(--neutral-200); }

    .modal-body { padding: 1.5rem; }
    
    .modal-badge {
        display: inline-block; padding: 0.3rem 0.75rem;
        border-radius: 999px; font-size: var(--fs-xs); font-weight: 900;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 1rem;
    }
    .modal-badge.pbm { background: var(--pbm-bg); color: var(--pbm-border); border: 1px solid var(--pbm-border); }
    .modal-badge.mahasiswa { background: var(--mhs-bg); color: var(--mhs-border); border: 1px solid var(--mhs-border); }
    .modal-badge.booking_cepat { background: var(--book-bg); color: var(--book-border); border: 1px solid var(--book-border); }
    .modal-badge.umum { background: var(--umum-bg); color: var(--umum-border); border: 1px solid var(--umum-border); }

    .modal-course { font-size: var(--fs-lg); font-weight: 900; color: var(--text-primary); margin-bottom: 1.25rem; line-height: 1.3; }
    
    .modal-detail-row {
        display: flex; align-items: flex-start; gap: 1rem;
        margin-bottom: 1rem; padding-bottom: 1rem;
        border-bottom: 1px dashed var(--border-regular);
    }
    .modal-detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .modal-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--primary-50); color: var(--primary-600);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .modal-info h5 { font-size: var(--fs-xs); color: var(--text-tertiary); font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem; }
    .modal-info p { font-size: var(--fs-base); color: var(--text-primary); font-weight: 600; margin: 0; }

    /* --- GUIDES (PANDUAN) --- */
    .guide-wrap{ margin-top:2.5rem; }
    .guide-title { font-size: var(--fs-lg); font-weight: 900; margin-bottom: 1.5rem; text-align: center; }
    .guide-grid{ display:grid; grid-template-columns:repeat(3, 1fr); gap:1.5rem; }
    .step{
      background:#ffffff; border:1px solid var(--border-light);
      border-radius:var(--radius-2xl); padding:1.5rem; text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .step:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary-300); }
    .step-icon{
      width:3.5rem; height:3.5rem; border-radius:50%; margin: 0 auto 1rem;
      display:flex; align-items:center; justify-content:center;
      background:var(--primary-50); color:var(--primary-600); font-size: 1.5rem;
    }
    .step-title{ font-weight:900; font-size:var(--fs-base); color: var(--text-primary); margin-bottom: 0.5rem; }
    .step-desc{ color:var(--text-tertiary); font-size:var(--fs-sm); line-height: 1.5; font-weight: 500; }

    /* --- FOOTER --- */
    .footer{ background:#ffffff; border-top:1px solid var(--border-light); padding:3rem 1.5rem 1.5rem; margin-top: 4rem; }
    .footer-content{ max-width:1240px; margin:0 auto; display:grid; grid-template-columns:2fr repeat(3,1fr); gap:2rem; }
    .footer-about p{ margin-top:1rem; font-size:var(--fs-sm); color:var(--text-tertiary); line-height: 1.6; font-weight: 500;}
    .footer-links h4{ font-size:var(--fs-sm); font-weight:900; color: var(--text-primary); margin-bottom:1rem; }
    .footer-links ul{ list-style:none; }
    .footer-links li{ margin-bottom:0.75rem; }
    .footer-links a{ color:var(--text-tertiary); text-decoration:none; font-size:var(--fs-sm); font-weight: 500; transition: color 0.2s; }
    .footer-links a:hover{ color:var(--primary-600); }
    .footer-bottom{
      max-width:1240px; margin:3rem auto 0; padding-top:1.5rem;
      border-top:1px solid var(--border-light); text-align:center;
      font-size:var(--fs-sm); color:var(--text-tertiary); font-weight: 500;
    }

    /* --- ANIMATIONS --- */
    .reveal{ opacity:0; transform:translateY(20px); transition:opacity 0.6s ease-out, transform 0.6s ease-out; }
    .reveal.visible{ opacity:1; transform:translateY(0); }
    .reveal.delay-1{ transition-delay:0.1s; }
    .reveal.delay-2{ transition-delay:0.2s; }

    /* --- RESPONSIVE MOBILE --- */
    @media (max-width: 1024px){
      .guide-grid{ grid-template-columns:1fr 1fr; }
      .footer-content{ grid-template-columns:repeat(2, 1fr); }
    }
    @media (max-width: 768px){
      .app-navbar { padding: 0 1rem; }
      .hero-title{ font-size:1.75rem; }
      .hero-content { padding: 80px 1rem 2rem; }
      .main-content{ margin-top: 20px; padding: 0 1rem; }
      
      .schedule-toolbar { flex-direction: column; align-items: flex-start; }
      .filter-group { width: 100%; flex-direction: column; align-items: stretch; gap: 0.75rem; }
      .filter-item { width: 100%; }
      .filter-item select, .filter-item input { width: 100%; }
      
      /* Mengatur ulang ukuran fiks untuk Mobile */
      .schedule-table th, .schedule-table td { width: 160px; min-width: 160px; max-width: 160px; }
      .schedule-table thead th { padding: 0.75rem 0.5rem; }

      .guide-grid{ grid-template-columns:1fr; }
      .footer-content{ grid-template-columns:1fr; }
      
      .hero-nav-pills { gap: 0.5rem; }
      .nav-pill { padding: 0.6rem 1rem; font-size: var(--fs-xs); }
    }
    </style>
</head>
<body>

@php
  if (!function_exists('normalizeScheduleTypeView')) {
      function normalizeScheduleTypeView($item) {
          $rawType = strtolower(trim((string)($item['type'] ?? '')));
          $title = strtolower(trim((string)($item['title'] ?? '')));
          $subtitle = strtolower(trim((string)($item['subtitle'] ?? '')));
          $meta = strtolower(trim((string)($item['meta'] ?? '')));
          $haystack = $rawType . ' ' . $title . ' ' . $subtitle . ' ' . $meta;

          if (str_contains($rawType, 'pbm') || str_contains($haystack, 'pbm')) {
              return 'pbm';
          }

          if (
              str_contains($rawType, 'mahasiswa') ||
              str_contains($haystack, 'mahasiswa') ||
              str_contains($haystack, 'bem') ||
              str_contains($rawType, 'peminjaman_mahasiswa') ||
              str_contains($rawType, 'pengajuan_mahasiswa')
          ) {
              return 'mahasiswa';
          }

          if (
              str_contains($rawType, 'booking_cepat') ||
              (str_contains($haystack, 'booking') && str_contains($haystack, 'cepat'))
          ) {
              return 'booking_cepat';
          }

          return 'umum'; // Fallback menjadi umum agar warna ungunya masuk
      }
  }
@endphp

<section class="hero">
  <div class="hero-bg" id="heroBg"></div>

  <div class="hero-content">
    <h1 class="hero-title reveal">
      Pinjam Ruangan<br>
      <span>Lebih Cepat & Praktis</span>
    </h1>

    <p class="hero-desc reveal delay-1">
      Sistem manajemen penggunaan ruang terpadu Institut Digital Ekonomi LPKIA. Pantau ketersediaan, jadwal, dan ajukan peminjaman secara realtime.
    </p>

    <div class="hero-nav-pills reveal delay-2">
      <a href="{{ url('/') }}" class="nav-pill active">
        <i class="fa-solid fa-house"></i> Jadwal Ruangan
      </a>
      <a href="{{ route('ruangan.index') }}" class="nav-pill">
        <i class="fa-solid fa-layer-group"></i> Ajuan Peminjaman
      </a>
      <a href="{{ route('history.index') }}" class="nav-pill">
        <i class="fa-solid fa-clock-rotate-left"></i>Status Pengajuan
      </a>
    </div>
    
  </div>
</section>

<main class="main-content" id="jadwal-ruangan">
    <div class="section-header reveal">
      <div class="section-title">
        <h2>Timeline Ruangan</h2>
        <p class="section-subtitle">Tampilan interaktif. Klik pada jadwal untuk melihat detail lengkap dosen dan kelas.</p>
      </div>
    </div>

    <div class="schedule-panel reveal delay-1">
      
      <div class="schedule-toolbar">
        <div class="toolbar-left">
          <i class="fa-regular fa-calendar-check"></i> Filter Jadwal
        </div>

        <form id="filterForm" class="filter-group">
          <div class="filter-item">
            <i class="fa-regular fa-calendar"></i>
            <input type="date" name="tanggal" id="tanggal" value="{{ $selectedTanggal }}" aria-label="Tanggal">
          </div>

          <div class="filter-item">
            <i class="fa-solid fa-building"></i>
            <select name="lantai" id="lantai" aria-label="Lantai">
              <option value="all" {{ $selectedLantai == 'all' ? 'selected' : '' }}>Semua Lantai</option>
              @foreach($floors as $floor)
                <option value="{{ $floor }}" {{ (string)$selectedLantai == (string)$floor ? 'selected' : '' }}>
                  Lantai {{ $floor }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="loading-badge" id="loadingBadge">
            <i class="fa-solid fa-circle-notch fa-spin"></i> Memuat...
          </div>
        </form>
      </div>

      <div id="scheduleContainer" class="schedule-shell">
        <div class="schedule-table-wrap">
          <table class="schedule-table">
            <thead>
              <tr>
                @forelse($rooms as $room)
                  <th>
                    <div class="room-name">{{ $room->name }}</div>
                    <div class="room-floor">Lt. {{ $room->floor ? $room->floor : '-' }}</div>
                  </th>
                @empty
                  <th style="font-weight:600; color:var(--text-tertiary);">Ruangan Tidak Ditemukan</th>
                @endforelse
              </tr>
            </thead>
            <tbody>
              @if(count($rooms) > 0)
                <tr>
                  @foreach($rooms as $room)
                    @php
                      // Logika untuk menggabungkan event dan mengurutkan berdasarkan jam mulai
                      $roomItemsList = [];
                      $roomItemsMap = [];
                      
                      foreach($timeSlots as $slot) {
                          $key = $room->id . '|' . $slot['start'] . '|' . $slot['end'];
                          if(isset($scheduleMap[$key])) {
                              foreach($scheduleMap[$key] as $it) {
                                  // Gunakan ID unik, atau kombinasikan title+start+end agar tidak duplikat
                                  $uniqueKey = isset($it['id']) ? $it['id'] : ($it['title'] . '_' . $it['start_time'] . '_' . $it['end_time']);
                                  
                                  if(!isset($roomItemsMap[$uniqueKey])) {
                                      $it['normalized_type'] = normalizeScheduleTypeView($it);
                                      $roomItemsMap[$uniqueKey] = true;
                                      $roomItemsList[] = $it;
                                  }
                              }
                          }
                      }

                      // Mengurutkan jadwal dari waktu paling pagi ke sore
                      usort($roomItemsList, function($a, $b) {
                          return strcmp($a['start_time'], $b['start_time']);
                      });
                    @endphp

                    <td class="schedule-cell">
                      @if(count($roomItemsList) > 0)
                        <div class="schedule-stack">
                          @foreach($roomItemsList as $item)
                            <div class="schedule-card {{ $item['normalized_type'] }}"
                                 data-type="{{ $item['normalized_type'] }}"
                                 data-course="{{ $item['title'] }}"
                                 data-class="{{ $item['subtitle'] }}"
                                 data-lecturer="{{ $item['meta'] }}"
                                 data-time="{{ substr($item['start_time'], 0, 5) }} - {{ substr($item['end_time'], 0, 5) }}"
                                 data-room="{{ $room->name }}">
                                 
                              <div class="schedule-head">
                                <span class="schedule-time">
                                    {{ substr($item['start_time'], 0, 5) }} - {{ substr($item['end_time'], 0, 5) }}
                                </span>
                                <span class="schedule-type">
                                  @if($item['normalized_type'] == 'pbm') PBM
                                  @elseif($item['normalized_type'] == 'mahasiswa') MHS
                                  @elseif($item['normalized_type'] == 'booking_cepat') CEPAT
                                  @else UMUM @endif
                                </span>
                              </div>

                              <div class="schedule-course" title="{{ $item['title'] }}">
                                {{ $item['title'] }}
                              </div>
                              
                              <div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Klik Detail</div>
                            </div>
                          @endforeach
                        </div>
                      @endif
                    </td>
                  @endforeach
                </tr>
              @else
                <tr>
                  <td colspan="1">
                    <div class="schedule-empty-state">
                      <i class="fa-regular fa-calendar-xmark"></i>
                      Tidak ada aktivitas untuk filter tanggal & lantai ini.
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <div class="info-strip">
          <div class="info-pill">
            <i class="fa-regular fa-calendar"></i> {{ $tanggalLabel }}
          </div>
          <div class="info-pill">
            <i class="fa-solid fa-layer-group"></i> {{ $selectedLantai == 'all' ? 'Semua Lantai' : 'Lantai ' . $selectedLantai }}
          </div>
          <div class="info-pill">
            <i class="fa-solid fa-door-open"></i> {{ count($rooms) }} Ruangan Tersedia
          </div>
        </div>
      </div>
    </div>

    <div class="guide-wrap reveal delay-2">
      <h3 class="guide-title">Bagaimana Cara Meminjam?</h3>
      <div class="guide-grid">
        <div class="step">
          <div class="step-icon"><i class="fa-solid fa-magnifying-glass-location"></i></div>
          <div class="step-title">1. Cari Ruangan</div>
          <div class="step-desc">Gunakan filter tanggal dan lantai untuk melihat ketersediaan slot kosong pada tabel jadwal di atas.</div>
        </div>

        <div class="step">
          <div class="step-icon"><i class="fa-regular fa-pen-to-square"></i></div>
          <div class="step-title">2. Ajukan Form</div>
          <div class="step-desc">Klik tombol daftar ruangan, pilih ruang yang kosong, dan isi form tujuan peminjaman Anda dengan lengkap.</div>
        </div>

        <div class="step">
          <div class="step-icon"><i class="fa-solid fa-check-double"></i></div>
          <div class="step-title">3. Tunggu Verifikasi</div>
          <div class="step-desc">Pengajuan akan dikonfirmasi oleh admin. Anda bisa menggunakan ruangan setelah disetujui dalam sistem.</div>
        </div>
      </div>
    </div>
</main>

<footer class="footer">
  <div class="footer-content">
    <div class="footer-about">
      <div style="display:flex;align-items:center;gap:.6rem;">
        <i class="fa-solid fa-layer-group" style="font-size:1.5rem; color:var(--primary-600);"></i>
        <div style="font-weight:900; font-size: 1.1rem; color: var(--text-primary);">Peminjaman Ruangan LPKIA</div>
      </div>
      <p>Sistem manajemen dan peminjaman fasilitas ruang resmi Institut Digital Ekonomi LPKIA. Mempermudah penjadwalan PBM dan kegiatan kemahasiswaan secara real-time.</p>
    </div>

    <div class="footer-links">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="{{ route('ruangan.index') }}">Daftar Ruangan</a></li>
        <li><a href="#jadwal-ruangan">Cek Jadwal</a></li>
        <li><a href="#">Status Peminjaman</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Bantuan</h4>
      <ul>
        <li><a href="#">Panduan Lengkap</a></li>
        <li><a href="#">Kebijakan Penggunaan</a></li>
        <li><a href="#">FAQ</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Hubungi Kami</h4>
      <ul>
        <li><i class="fa-regular fa-envelope" style="width:16px;"></i> admin.ruang@lpkia.ac.id</li>
        <li><i class="fa-regular fa-clock" style="width:16px;"></i> Senin - Jumat (08:00 - 17:00)</li>
        <li><i class="fa-solid fa-location-dot" style="width:16px;"></i> Kampus IDE LPKIA Bandung</li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} Institut Digital Ekonomi LPKIA. Hak Cipta Dilindungi.</p>
  </div>
</footer>

<div id="scheduleModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Detail Kegiatan</h3>
            <button id="closeModal" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <span id="modalType" class="modal-badge pbm">PBM</span>
            <h4 id="modalCourse" class="modal-course">Nama Mata Kuliah / Kegiatan</h4>
            
            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-regular fa-clock"></i></div>
                <div class="modal-info">
                    <h5>Waktu Pelaksanaan</h5>
                    <p id="modalTime">08:00 - 10:00</p>
                </div>
            </div>

            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-solid fa-door-open"></i></div>
                <div class="modal-info">
                    <h5>Ruangan & Kelas</h5>
                    <p><span id="modalRoom">Ruang A</span> &bull; <span id="modalClass">Kelas B</span></p>
                </div>
            </div>

            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div class="modal-info">
                    <h5>Dosen / PIC</h5>
                    <p id="modalLecturer">Nama Dosen</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  
  // Efek Transisi Navbar saat di-scroll
  var navbar = document.getElementById('appNavbar');
  if(navbar) {
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) { navbar.classList.add('scrolled'); } 
        else { navbar.classList.remove('scrolled'); }
    });
  }

  // Animasi Muncul (Reveal) 
  var els = document.querySelectorAll('.reveal');
  var io = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) {
      if (e.isIntersecting) { e.target.classList.add('visible'); }
    });
  }, { threshold: 0.1 });
  els.forEach(function(el) { io.observe(el); });

  // Parallax ringan Background
  var bg = document.getElementById('heroBg');
  var ticking = false;
  window.addEventListener('scroll', function(){
    if (!ticking && bg) {
      window.requestAnimationFrame(function(){
        var y = window.scrollY || 0;
        bg.style.transform = 'scale(1.05) translateY(' + (y * 0.15) + 'px)';
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });

  // --- LOGIKA MODAL POP-UP ---
  var modal = document.getElementById('scheduleModal');
  var btnClose = document.getElementById('closeModal');
  
  function openModal(data) {
      var badge = document.getElementById('modalType');
      badge.className = 'modal-badge ' + data.typeClass;
      badge.textContent = data.typeLabel;

      document.getElementById('modalCourse').textContent = data.course || '-';
      document.getElementById('modalTime').textContent = data.time || '-';
      document.getElementById('modalRoom').textContent = data.room || '-';
      document.getElementById('modalClass').textContent = data.subtitle || '-';
      document.getElementById('modalLecturer').textContent = data.lecturer || '-';

      modal.classList.add('active');
      document.body.style.overflow = 'hidden'; 
  }

  function closeModal() {
      modal.classList.remove('active');
      document.body.style.overflow = '';
  }

  if(btnClose) btnClose.addEventListener('click', closeModal);
  if(modal) {
      modal.addEventListener('click', function(e) {
          if(e.target === modal) closeModal(); 
      });
  }

  var scheduleContainer = document.getElementById('scheduleContainer');
  if(scheduleContainer) {
      scheduleContainer.addEventListener('click', function(e) {
          var card = e.target.closest('.schedule-card');
          if (card) {
              var typeClass = card.getAttribute('data-type');
              var typeLabel = 'UMUM';
              if(typeClass === 'pbm') typeLabel = 'PBM';
              if(typeClass === 'mahasiswa') typeLabel = 'MAHASISWA';
              if(typeClass === 'booking_cepat') typeLabel = 'BOOKING CEPAT';

              openModal({
                  typeClass: typeClass,
                  typeLabel: typeLabel,
                  course: card.getAttribute('data-course'),
                  subtitle: card.getAttribute('data-class'),
                  lecturer: card.getAttribute('data-lecturer'),
                  time: card.getAttribute('data-time'),
                  room: card.getAttribute('data-room')
              });
          }
      });
  }

  // --- LOGIKA AJAX FILTER JADWAL ---
  var tanggal = document.getElementById('tanggal');
  var lantai = document.getElementById('lantai');
  var container = document.getElementById('scheduleContainer');
  var loadingBadge = document.getElementById('loadingBadge');
  var requestController = null;

  function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  function normalizeType(item) {
    var rawType = String(item.type || '').toLowerCase().trim();
    var title = String(item.title || '').toLowerCase().trim();
    var subtitle = String(item.subtitle || '').toLowerCase().trim();
    var meta = String(item.meta || '').toLowerCase().trim();
    var haystack = rawType + ' ' + title + ' ' + subtitle + ' ' + meta;

    if (rawType.indexOf('pbm') !== -1 || haystack.indexOf('pbm') !== -1) return 'pbm';
    if (rawType.indexOf('mahasiswa') !== -1 || haystack.indexOf('mahasiswa') !== -1 || haystack.indexOf('bem') !== -1) return 'mahasiswa';
    if (rawType.indexOf('booking_cepat') !== -1 || (haystack.indexOf('booking') !== -1 && haystack.indexOf('cepat') !== -1)) return 'booking_cepat';
    
    return 'umum'; // Sama dengan backend, fallback ke umum
  }

  function getTypeLabel(type) {
    if (type === 'pbm') return 'PBM';
    if (type === 'mahasiswa') return 'MHS';
    if (type === 'booking_cepat') return 'CEPAT';
    return 'UMUM';
  }

  function renderItems(items, roomName) {
    if (!items || !items.length) return ''; 
    
    var html = '<div class="schedule-stack">';
    items.forEach(function(item) {
      var normalizedType = normalizeType(item);
      var typeLabel = getTypeLabel(normalizedType);
      var startTime = escapeHtml(String(item.start_time).substring(0,5));
      var endTime = escapeHtml(String(item.end_time).substring(0,5));
      var timeText = startTime + ' - ' + endTime;

      html += '<div class="schedule-card ' + escapeHtml(normalizedType) + '" ';
      html += 'data-type="' + escapeHtml(normalizedType) + '" ';
      html += 'data-course="' + escapeHtml(item.title) + '" ';
      html += 'data-class="' + escapeHtml(item.subtitle) + '" ';
      html += 'data-lecturer="' + escapeHtml(item.meta) + '" ';
      html += 'data-time="' + timeText + '" ';
      html += 'data-room="' + escapeHtml(roomName) + '">';
      
      html += '<div class="schedule-head">';
      html += '<span class="schedule-time">' + timeText + '</span>';
      html += '<span class="schedule-type">' + escapeHtml(typeLabel) + '</span>';
      html += '</div>';

      html += '<div class="schedule-course" title="' + escapeHtml(item.title) + '">' + escapeHtml(item.title) + '</div>';
      html += '<div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Klik Detail</div>';
      
      html += '</div>';
    });
    html += '</div>';
    return html;
  }

  function renderSchedule(data) {
    var html = '';
    var rooms = data.rooms || [];
    var timeSlots = data.timeSlots || [];
    var scheduleMap = data.scheduleMap || {};
    var tanggalLabel = data.tanggalLabel || '';
    var selectedLantai = data.selectedLantai || 'all';
    var totalRooms = data.totalRooms || 0;

    html += '<div class="schedule-table-wrap">';
    html += '<table class="schedule-table"><thead><tr>';

    if (rooms.length > 0) {
      rooms.forEach(function(room) {
        html += '<th><div class="room-name">' + escapeHtml(room.name) + '</div><div class="room-floor">Lt. ' + escapeHtml(room.floor || '-') + '</div></th>';
      });
    } else {
      html += '<th style="font-weight:600; color:var(--text-tertiary);">Ruangan Tidak Ditemukan</th>';
    }
    html += '</tr></thead><tbody>';

    if (rooms.length > 0) {
      html += '<tr>';
      rooms.forEach(function(room) {
        var roomItemsMap = {};
        var roomItemsList = [];
        
        // Gabungkan semua item yang terpotong menjadi 1 di setiap ruangan
        timeSlots.forEach(function(slot) {
          var key = room.id + '|' + slot.start + '|' + slot.end;
          var items = scheduleMap[key] || [];
          items.forEach(function(item) {
             var uniqueKey = item.id ? item.id : (item.title + '_' + item.start_time + '_' + item.end_time);
             if (!roomItemsMap[uniqueKey]) {
                 roomItemsMap[uniqueKey] = true;
                 roomItemsList.push(item);
             }
          });
        });

        // Urutkan kartu berdasarkan jam mulai
        roomItemsList.sort(function(a, b) {
           return a.start_time.localeCompare(b.start_time);
        });

        html += '<td class="schedule-cell">' + renderItems(roomItemsList, room.name) + '</td>';
      });
      html += '</tr>';
    } else {
      html += '<tr><td colspan="1"><div class="schedule-empty-state"><i class="fa-regular fa-calendar-xmark"></i> Tidak ada aktivitas untuk filter tanggal & lantai ini.</div></td></tr>';
    }

    html += '</tbody></table></div>';

    html += '<div class="info-strip">';
    html += '<div class="info-pill"><i class="fa-regular fa-calendar"></i> ' + escapeHtml(tanggalLabel) + '</div>';
    html += '<div class="info-pill"><i class="fa-solid fa-layer-group"></i> ' + escapeHtml(selectedLantai === 'all' ? 'Semua Lantai' : 'Lantai ' + selectedLantai) + '</div>';
    html += '<div class="info-pill"><i class="fa-solid fa-door-open"></i> ' + escapeHtml(totalRooms) + ' Ruangan Tersedia</div>';
    html += '</div>';

    return html;
  }

  function loadSchedule() {
    var currentScrollY = window.scrollY;
    var currentWrap = container.querySelector('.schedule-table-wrap');
    var currentScrollLeft = currentWrap ? currentWrap.scrollLeft : 0;

    if (requestController) { requestController.abort(); }
    requestController = new AbortController();

    var params = new URLSearchParams({
      tanggal: tanggal.value,
      lantai: lantai.value
    });

    container.classList.add('is-loading');
    loadingBadge.classList.add('show');

    fetch('{{ url("/") }}?' + params.toString(), {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      signal: requestController.signal
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      container.innerHTML = renderSchedule(data);
      history.replaceState({}, '', '{{ url("/") }}?' + params.toString());
      window.scrollTo(0, currentScrollY);

      var newWrap = container.querySelector('.schedule-table-wrap');
      if (newWrap) newWrap.scrollLeft = currentScrollLeft;
    })
    .catch(function(error) { if (error.name !== 'AbortError') console.error(error); })
    .finally(function() {
      container.classList.remove('is-loading');
      loadingBadge.classList.remove('show');
    });
  }

  if (tanggal) tanggal.addEventListener('change', loadSchedule);
  if (lantai) lantai.addEventListener('change', loadSchedule);
});
</script>
</body>
</html>