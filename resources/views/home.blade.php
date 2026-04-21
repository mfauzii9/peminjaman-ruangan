<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Peminjaman Ruangan LPKIA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
      
      --bg-main: #F4F7F6; /* Soft elegant background */
      --bg-elevated: #FFFFFF;

      --text-primary: #1A2942; /* Navy dominant */
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

      /* Font Sizes */
      --fs-xs: 0.75rem;
      --fs-sm: 0.875rem;
      --fs-base: 1rem;
      --fs-md: 1.125rem;
      --fs-lg: 1.25rem;
      --fs-xl: 1.5rem;
      --fs-2xl: 2rem;
      --fs-3xl: 2.5rem;

      /* Colors for Schedule Cards - Adjusted to Palette */
      --pbm-bg: var(--lightblue-50);     
      --pbm-border: var(--lightblue-500); 
      --pbm-text: var(--navy-800);   

      --mhs-bg: var(--sage-50);     
      --mhs-border: var(--sage-500); 
      --mhs-text: var(--sage-700);   

      --book-bg: #FFF7ED;    
      --book-border: #F97316;
      --book-text: #9A3412;  
      
      --umum-bg: var(--neutral-50);    
      --umum-border: var(--navy-400);
      --umum-text: var(--navy-800);
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
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--neutral-50); border-radius: 4px; }
    ::-webkit-scrollbar-thumb { background: var(--sage-300); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--sage-500); }

    /* --- APP NAVIGATION BAR --- */
    .app-navbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5%;
        z-index: 1000;
        transition: all 0.3s ease;
        background: transparent;
    }
    .app-navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }
    .nav-brand {
        font-weight: 800;
        font-size: var(--fs-md);
        color: var(--navy-800);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    /* --- HERO SECTION --- */
    .hero{
      position:relative;
      min-height: clamp(300px, 35vh, 400px);
      display:flex;
      align-items:center;
      overflow:hidden;
      background: transparent;
    }
    .hero-bg{
      position:absolute;
      inset:0;
      background: linear-gradient(180deg, #ffffff 0%, var(--bg-main) 100%);
      z-index: 1;
    }
    .hero-bg::before {
      content: '';
      position: absolute;
      top: -30%; left: -10%;
      width: 60%; height: 80%;
      background: radial-gradient(circle, var(--lightblue-100) 0%, transparent 70%);
      opacity: 0.5;
      filter: blur(50px);
    }
    .hero-bg::after {
      content: '';
      position: absolute;
      bottom: -10%; right: -10%;
      width: 50%; height: 70%;
      background: radial-gradient(circle, var(--sage-100) 0%, transparent 60%);
      opacity: 0.7;
      filter: blur(40px);
    }

    .hero-content{
      position:relative;
      z-index:3;
      max-width:800px;
      margin: 0 auto;
      padding: 80px 1.5rem 2rem;
      text-align:center;
    }
    .hero-title{
      font-size:var(--fs-3xl);
      line-height:1.2;
      letter-spacing:-.03em;
      font-weight:800;
      color: var(--navy-900);
    }
    .hero-title span{
      color: var(--sage-500);
    }
    .hero-desc{
      margin:1.25rem auto 0;
      max-width:600px;
      font-size:var(--fs-base);
      color:var(--text-secondary);
      font-weight: 400;
    }

    /* --- CENTERED MAIN NAVIGATION (PILLS) --- */
    .hero-nav-pills {
      margin-top: 2.5rem;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1rem;
    }
    .nav-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: 999px;
      font-size: var(--fs-sm);
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
      background: var(--bg-elevated); 
      color: var(--navy-600);
      border: 1px solid var(--border-light);
      box-shadow: var(--shadow-sm);
    }
    .nav-pill i {
      font-size: 1rem;
      color: var(--sage-500);
    }
    .nav-pill:hover {
      border-color: var(--sage-300);
      color: var(--navy-800);
      box-shadow: var(--shadow-md);
    }
    .nav-pill.active {
      background: var(--navy-800);
      color: #ffffff;
      border-color: var(--navy-800);
    }
    .nav-pill.active i {
      color: var(--sage-300);
    }

    /* --- MAIN LAYOUT --- */
    .main-content{
      max-width: 1280px;
      margin: 0 auto 60px;
      padding: 0 5%;
      position: relative;
      z-index: 10;
    }
    .section-header{
      margin-bottom: 2rem;
      text-align: center;
    }
    .section-title h2{ font-size:var(--fs-2xl); font-weight:800; color: var(--navy-900); letter-spacing: -0.02em;}
    .section-subtitle{ font-size:var(--fs-sm); color:var(--text-secondary); margin-top:0.5rem; }

    /* --- ELEGANT SCHEDULE PANEL --- */
    .schedule-panel{
      background:var(--bg-elevated);
      border-radius:var(--radius-xl);
      overflow:hidden;
      box-shadow:var(--shadow-md);
      border: 1px solid var(--border-light);
    }
    
    .schedule-toolbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:1.5rem;
      padding:1.5rem;
      background: var(--bg-elevated);
      border-bottom:1px solid var(--border-light);
      flex-wrap:wrap;
    }
    .toolbar-left{
      display:flex;
      align-items:center;
      gap:0.75rem;
      font-weight:700;
      font-size:var(--fs-base);
      color:var(--navy-800);
    }
    .toolbar-left i { color: var(--sage-500); font-size: 1.25rem; }

    .filter-group{ display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
    .filter-item { position: relative; display: flex; align-items: center; }
    .filter-item i { position: absolute; left: 1rem; color: var(--text-tertiary); font-size: 0.9rem; pointer-events: none; }
    .filter-item select, .filter-item input{
      padding: 0.6rem 1rem 0.6rem 2.5rem;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border-regular);
      background: var(--neutral-50);
      font-family: var(--font-sans);
      font-size: var(--fs-sm);
      font-weight: 500;
      color: var(--navy-800);
      outline: none;
      transition: all 0.2s;
      cursor: pointer;
      appearance: auto;
    }
    .filter-item select:hover, .filter-item input:hover { border-color: var(--sage-300); }
    .filter-item select:focus, .filter-item input:focus{
      border-color: var(--sage-500); background: #ffffff; box-shadow: 0 0 0 3px var(--sage-100);
    }
    .loading-badge{
      display:none; align-items:center; gap:0.5rem; padding:0.5rem 1rem;
      border-radius:var(--radius-sm); background:var(--lightblue-50); color:var(--lightblue-500);
      font-size:var(--fs-sm); font-weight:600;
    }
    .loading-badge.show{ display:inline-flex; }

    /* --- REVISED SCHEDULE TABLE --- */
    .schedule-shell{ transition:opacity 0.2s ease; background: var(--bg-main); }
    .schedule-shell.is-loading{ opacity:0.6; pointer-events:none; }

    .schedule-table-wrap{
      width:100%;
      overflow-x:auto;
      max-height: 600px; 
      overflow-y:auto;
      position:relative;
      background: var(--bg-elevated);
    }
    .schedule-table{
      width:max-content;
      min-width:100%;
      border-collapse:collapse;
    }

    .schedule-table th, .schedule-table td {
      border: 1px solid var(--border-light);
      padding: 0.75rem;
      vertical-align: top;
    }

    .schedule-table thead th{
      position:sticky;
      top: 0;
      z-index:20;
      background: var(--neutral-50); 
      color:var(--navy-800);
      padding:1rem 0.75rem;
      text-align:center;
      box-shadow: 0 1px 0 var(--border-light);
    }
    .room-name { font-size:var(--fs-sm); font-weight:700; letter-spacing: -0.01em;}
    .room-floor { font-size:var(--fs-xs); color:var(--text-tertiary); margin-top:0.25rem; font-weight: 500; }

    .schedule-cell{
      min-width: 180px; 
      width: 180px;
      background: var(--bg-elevated);
      position: relative;
    }

    .schedule-stack{ display:flex; flex-direction:column; gap:0.75rem; }

    /* --- MODERN & CONTRAST SCHEDULE CARDS --- */
    .schedule-card{
      position:relative;
      border-radius: var(--radius-sm);
      padding: 0.75rem;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      cursor: pointer; 
      border-left: 3px solid transparent;
    }
    .schedule-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .schedule-card.pbm{ background:var(--pbm-bg); border-left-color: var(--pbm-border); border-right: 1px solid var(--border-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);}
    .schedule-card.mahasiswa{ background:var(--mhs-bg); border-left-color: var(--mhs-border); border-right: 1px solid var(--border-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);}
    .schedule-card.booking_cepat{ background:var(--book-bg); border-left-color: var(--book-border); border-right: 1px solid var(--border-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);}
    .schedule-card.umum{ background:var(--umum-bg); border-left-color: var(--umum-border); border-right: 1px solid var(--border-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);}

    .schedule-head { display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; }
    
    .schedule-time{
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: -0.01em;
    }
    .schedule-card.pbm .schedule-time { color: var(--pbm-text); }
    .schedule-card.mahasiswa .schedule-time { color: var(--mhs-text); }
    .schedule-card.booking_cepat .schedule-time { color: var(--book-text); }
    .schedule-card.umum .schedule-time { color: var(--umum-text); }

    .schedule-type{
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.15rem 0.4rem;
      border-radius: 4px;
      background: rgba(255,255,255,0.7);
    }
    .schedule-card.pbm .schedule-type { color: var(--pbm-border); }
    .schedule-card.mahasiswa .schedule-type { color: var(--mhs-border); }
    .schedule-card.booking_cepat .schedule-type { color: var(--book-border); }
    .schedule-card.umum .schedule-type { color: var(--umum-border); }

    .schedule-course{
      font-size: var(--fs-sm);
      font-weight: 600;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .schedule-card.pbm .schedule-course { color: var(--pbm-text); }
    .schedule-card.mahasiswa .schedule-course { color: var(--mhs-text); }
    .schedule-card.booking_cepat .schedule-course { color: var(--book-text); }
    .schedule-card.umum .schedule-course { color: var(--umum-text); }

    .click-hint {
        font-size: 0.65rem;
        font-weight: 500;
        opacity: 0.7;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .schedule-card.pbm .click-hint { color: var(--pbm-text); }
    .schedule-card.mahasiswa .click-hint { color: var(--mhs-text); }
    .schedule-card.booking_cepat .click-hint { color: var(--book-text); }
    .schedule-card.umum .click-hint { color: var(--umum-text); }

    .schedule-empty-state{
      text-align:center;
      padding:4rem 1rem;
      color:var(--text-tertiary);
      font-size:var(--fs-sm);
      font-weight: 500;
      background: var(--bg-elevated);
    }
    .schedule-empty-state i {
        font-size: 2rem;
        color: var(--neutral-300);
        margin-bottom: 1rem;
        display: block;
    }

    /* --- INFO FOOTER STRIP --- */
    .info-strip{
      display:flex; flex-wrap:wrap; gap:1.5rem; padding:1.25rem 1.5rem;
      background:var(--neutral-50); border-top:1px solid var(--border-light);
    }
    .info-pill{
      display:inline-flex; align-items:center; gap:0.5rem;
      font-size:var(--fs-sm); color:var(--navy-600); font-weight:600;
    }
    .info-pill i { color: var(--sage-500); }

    /* --- MODAL POP-UP STYLES --- */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden;
        transition: all 0.3s ease;
        padding: 1.5rem;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    
    .modal-content {
        background: var(--bg-elevated);
        width: 100%; max-width: 450px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        transform: translateY(20px) scale(0.98);
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
    .modal-header h3 { font-size: var(--fs-base); font-weight: 700; color: var(--navy-800); margin: 0; }
    .modal-close {
        background: transparent; border: none;
        color: var(--text-tertiary); font-size: 1.25rem;
        cursor: pointer; transition: color 0.2s;
        display: flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%;
    }
    .modal-close:hover { color: var(--navy-900); background: var(--neutral-200); }

    .modal-body { padding: 1.5rem; }
    
    .modal-badge {
        display: inline-block; padding: 0.25rem 0.75rem;
        border-radius: 999px; font-size: var(--fs-xs); font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 1rem;
    }
    .modal-badge.pbm { background: var(--pbm-bg); color: var(--pbm-border); border: 1px solid var(--pbm-border); }
    .modal-badge.mahasiswa { background: var(--mhs-bg); color: var(--mhs-border); border: 1px solid var(--mhs-border); }
    .modal-badge.booking_cepat { background: var(--book-bg); color: var(--book-border); border: 1px solid var(--book-border); }
    .modal-badge.umum { background: var(--umum-bg); color: var(--umum-border); border: 1px solid var(--umum-border); }

    .modal-course { font-size: var(--fs-lg); font-weight: 700; color: var(--navy-900); margin-bottom: 1.5rem; line-height: 1.3; }
    
    .modal-detail-row {
        display: flex; align-items: flex-start; gap: 1rem;
        margin-bottom: 1.25rem; padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-light);
    }
    .modal-detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .modal-icon {
        width: 40px; height: 40px; border-radius: var(--radius-sm);
        background: var(--sage-50); color: var(--sage-500);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .modal-info h5 { font-size: var(--fs-xs); color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.02em; }
    .modal-info p { font-size: var(--fs-sm); color: var(--navy-800); font-weight: 600; margin: 0; }

    /* --- GUIDES (PANDUAN) --- */
    .guide-wrap{ margin-top: 4rem; }
    .guide-title { font-size: var(--fs-xl); font-weight: 800; margin-bottom: 2rem; text-align: center; color: var(--navy-900); }
    .guide-grid{ display:grid; grid-template-columns:repeat(3, 1fr); gap:1.5rem; }
    .step{
      background: var(--bg-elevated); border:1px solid var(--border-light);
      border-radius: var(--radius-xl); padding:2rem 1.5rem; text-align: center;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .step:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--sage-300); }
    .step-icon{
      width: 4rem; height: 4rem; border-radius: 50%; margin: 0 auto 1.25rem;
      display:flex; align-items:center; justify-content:center;
      background: var(--sage-50); color: var(--sage-700); font-size: 1.5rem;
    }
    .step-title{ font-weight: 700; font-size:var(--fs-md); color: var(--navy-800); margin-bottom: 0.75rem; }
    .step-desc{ color:var(--text-secondary); font-size:var(--fs-sm); line-height: 1.6; font-weight: 400; }

    /* --- FOOTER --- */
    .footer{ background: var(--bg-elevated); border-top:1px solid var(--border-light); padding: 4rem 5% 2rem; margin-top: 5rem; }
    .footer-content{ max-width: 1280px; margin:0 auto; display:grid; grid-template-columns: 2fr repeat(3,1fr); gap: 2.5rem; }
    .footer-about p{ margin-top:1rem; font-size:var(--fs-sm); color:var(--text-secondary); line-height: 1.6; font-weight: 400;}
    .footer-links h4{ font-size:var(--fs-base); font-weight:700; color: var(--navy-900); margin-bottom:1.25rem; }
    .footer-links ul{ list-style:none; }
    .footer-links li{ margin-bottom:0.75rem; }
    .footer-links a, .footer-links span{ color:var(--text-secondary); text-decoration:none; font-size:var(--fs-sm); font-weight: 400; transition: color 0.2s; }
    .footer-links a:hover{ color: var(--sage-700); }
    .footer-bottom{
      max-width: 1280px; margin:4rem auto 0; padding-top:1.5rem;
      border-top:1px solid var(--border-light); text-align:center;
      font-size:var(--fs-sm); color:var(--text-tertiary); font-weight: 400;
    }

    /* --- ANIMATIONS --- */
    .reveal{ opacity:0; transform:translateY(15px); transition:opacity 0.5s ease-out, transform 0.5s ease-out; }
    .reveal.visible{ opacity:1; transform:translateY(0); }
    .reveal.delay-1{ transition-delay:0.1s; }
    .reveal.delay-2{ transition-delay:0.2s; }

    /* --- RESPONSIVE MOBILE --- */
    @media (max-width: 1024px){
      .guide-grid{ grid-template-columns:1fr 1fr; }
      .footer-content{ grid-template-columns:repeat(2, 1fr); }
    }
    @media (max-width: 768px){
      .hero-content { padding: 80px 1rem 2rem; }
      .main-content{ padding: 0 1rem; }
      
      .schedule-toolbar { flex-direction: column; align-items: flex-start; gap: 1rem; }
      .filter-group { width: 100%; flex-direction: column; align-items: stretch; gap: 0.75rem; }
      .filter-item { width: 100%; }
      .filter-item select, .filter-item input { width: 100%; }
      
      .schedule-cell { min-width: 150px; width: 150px; }
      .schedule-table thead th { padding: 0.75rem 0.5rem; }

      .guide-grid{ grid-template-columns:1fr; }
      .footer-content{ grid-template-columns:1fr; }
      
      .hero-nav-pills { gap: 0.5rem; }
      .nav-pill { padding: 0.6rem 1.2rem; font-size: var(--fs-xs); }
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

          return 'umum';
      }
  }
@endphp

<section class="hero">
  <div class="hero-bg" id="heroBg"></div>

  <div class="hero-content">
    <h1 class="hero-title reveal">
      Peminjaman Ruangan<br>
      <span>IDE LPKIA</span>
    </h1>

    <p class="hero-desc reveal delay-1">
      Sistem manajemen penggunaan ruang terpadu. Pantau ketersediaan, jadwal kegiatan, dan ajukan peminjaman secara responsif.
    </p>

    <div class="hero-nav-pills reveal delay-2">
      <a href="{{ url('/') }}" class="nav-pill active">
        <i class="fa-solid fa-house"></i> Jadwal Ruangan
      </a>
      <a href="{{ route('ruangan.index') }}" class="nav-pill">
        <i class="fa-solid fa-layer-group"></i> Ajuan Peminjaman
      </a>
      <a href="{{ route('history.index') }}" class="nav-pill">
        <i class="fa-solid fa-clock-rotate-left"></i> Status Pengajuan
      </a>
    </div>
    
  </div>
</section>

<main class="main-content" id="jadwal-ruangan">
    <div class="section-header reveal">
      <div class="section-title">
        <h2>Timeline Ruangan</h2>
        <p class="section-subtitle">Tampilan interaktif. Klik jadwal spesifik untuk melihat detail kegiatan secara komprehensif.</p>
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
                  <th style="font-weight:500; color:var(--text-tertiary);">Ruangan Tidak Ditemukan</th>
                @endforelse
              </tr>
            </thead>
            <tbody>
              @if(count($rooms) > 0)
                <tr>
                  @foreach($rooms as $room)
                    @php
                      $roomItemsList = [];
                      $roomItemsMap = [];
                      
                      foreach($timeSlots as $slot) {
                          $key = $room->id . '|' . $slot['start'] . '|' . $slot['end'];
                          if(isset($scheduleMap[$key])) {
                              foreach($scheduleMap[$key] as $it) {
                                  $uniqueKey = isset($it['id']) ? $it['id'] : ($it['title'] . '_' . $it['start_time'] . '_' . $it['end_time']);
                                  
                                  if(!isset($roomItemsMap[$uniqueKey])) {
                                      $it['normalized_type'] = normalizeScheduleTypeView($it);
                                      $roomItemsMap[$uniqueKey] = true;
                                      $roomItemsList[] = $it;
                                  }
                              }
                          }
                      }

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
                      Tidak ada aktivitas pada tanggal dan lantai yang dipilih.
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
            <i class="fa-solid fa-door-open"></i> {{ count($rooms) }} Ruang
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
          <div class="step-desc">Gunakan filter untuk memeriksa ketersediaan slot waktu yang kosong pada tabel timeline.</div>
        </div>

        <div class="step">
          <div class="step-icon"><i class="fa-regular fa-pen-to-square"></i></div>
          <div class="step-title">2. Ajukan Form</div>
          <div class="step-desc">Pilih ruangan tujuan dan lengkapi form pengajuan peminjaman di menu yang tersedia.</div>
        </div>

        <div class="step">
          <div class="step-icon"><i class="fa-solid fa-check-double"></i></div>
          <div class="step-title">3. Verifikasi</div>
          <div class="step-desc">Tunggu konfirmasi admin. Ruangan siap digunakan setelah status disetujui dalam sistem.</div>
        </div>
      </div>
    </div>
</main>

<footer class="footer">
  <div class="footer-content">
    <div class="footer-about">
      <div style="display:flex;align-items:center;gap:.75rem;">
        <i class="fa-solid fa-layer-group" style="font-size:1.5rem; color:var(--sage-500);"></i>
        <div style="font-weight:800; font-size: 1.125rem; color: var(--navy-900);">Peminjaman Ruangan</div>
      </div>
      <p>Sistem terpadu manajemen dan peminjaman fasilitas ruang Institut Digital Ekonomi LPKIA. Beroperasi secara real-time untuk kebutuhan akademis.</p>
    </div>

    <div class="footer-links">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="{{ route('ruangan.index') }}">Daftar Ruangan</a></li>
        <li><a href="#jadwal-ruangan">Cek Jadwal</a></li>
        <li><a href="{{ route('history.index') }}">Status Peminjaman</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Bantuan</h4>
      <ul>
        <li><a href="#">Panduan Sistem</a></li>
        <li><a href="#">Kebijakan Peminjaman</a></li>
        <li><a href="#">FAQ</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Hubungi Kami</h4>
      <ul>
        <li><span><i class="fa-regular fa-envelope" style="width:20px;"></i> admin.ruang@lpkia.ac.id</span></li>
        <li><span><i class="fa-regular fa-clock" style="width:20px;"></i> Senin - Jumat (08:00 - 17:00)</span></li>
        <li><span><i class="fa-solid fa-location-dot" style="width:20px;"></i> Kampus IDE LPKIA</span></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} Institut Digital Ekonomi LPKIA. by haikal</p>
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
            <h4 id="modalCourse" class="modal-course">-</h4>
            
            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-regular fa-clock"></i></div>
                <div class="modal-info">
                    <h5>Waktu</h5>
                    <p id="modalTime">-</p>
                </div>
            </div>

            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-solid fa-door-open"></i></div>
                <div class="modal-info">
                    <h5>Ruangan & Kelas</h5>
                    <p><span id="modalRoom">-</span> &bull; <span id="modalClass">-</span></p>
                </div>
            </div>

            <div class="modal-detail-row">
                <div class="modal-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div class="modal-info">
                    <h5>Dosen / PIC</h5>
                    <p id="modalLecturer">-</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  
  // Transisi Navbar
  var navbar = document.getElementById('appNavbar');
  if(navbar) {
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) { navbar.classList.add('scrolled'); } 
        else { navbar.classList.remove('scrolled'); }
    });
  }

  // Animasi Muncul
  var els = document.querySelectorAll('.reveal');
  var io = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) {
      if (e.isIntersecting) { e.target.classList.add('visible'); }
    });
  }, { threshold: 0.1 });
  els.forEach(function(el) { io.observe(el); });

  // Parallax
  var bg = document.getElementById('heroBg');
  var ticking = false;
  window.addEventListener('scroll', function(){
    if (!ticking && bg) {
      window.requestAnimationFrame(function(){
        var y = window.scrollY || 0;
        bg.style.transform = 'translateY(' + (y * 0.15) + 'px)';
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });

  // Logika Modal
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

  // AJAX Filter Jadwal
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
    
    return 'umum';
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
      html += '<th style="font-weight:500; color:var(--text-tertiary);">Ruangan Tidak Ditemukan</th>';
    }
    html += '</tr></thead><tbody>';

    if (rooms.length > 0) {
      html += '<tr>';
      rooms.forEach(function(room) {
        var roomItemsMap = {};
        var roomItemsList = [];
        
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

        roomItemsList.sort(function(a, b) {
            return String(a.start_time).localeCompare(String(b.start_time));
        });

        html += '<td class="schedule-cell">' + renderItems(roomItemsList, room.name) + '</td>';
      });
      html += '</tr>';
    } else {
      html += '<tr><td colspan="1"><div class="schedule-empty-state"><i class="fa-regular fa-calendar-xmark"></i> Tidak ada aktivitas pada tanggal dan lantai yang dipilih.</div></td></tr>';
    }

    html += '</tbody></table></div>';

    html += '<div class="info-strip">';
    html += '<div class="info-pill"><i class="fa-regular fa-calendar"></i> ' + escapeHtml(tanggalLabel) + '</div>';
    html += '<div class="info-pill"><i class="fa-solid fa-layer-group"></i> ' + escapeHtml(selectedLantai === 'all' ? 'Semua Lantai' : 'Lantai ' + selectedLantai) + '</div>';
    html += '<div class="info-pill"><i class="fa-solid fa-door-open"></i> ' + escapeHtml(totalRooms) + ' Ruang</div>';
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