<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Peminjaman Ruangan LPKIA')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @yield('head')

  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1e40af;
      --primary-light: #dbeafe;
      --accent: #2e0bf5;
      --bg-body: #f8fafc;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --white: #ffffff;
      --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
      --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
      --radius: 12px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg-body);
      color: var(--text-main);
      line-height: 1.6;
      overflow-x: hidden;
    }

    a { text-decoration: none; transition: all 0.3s ease; }
    ul { list-style: none; }

    .container {
      width: min(1280px, 92%);
      margin: 0 auto;
    }

    /* TOPBAR - Minimalist */
    .topbar {
      background: #0f172a;
      color: #cbd5e1;
      font-size: 12px;
      padding: 8px 0;
    }
    .topbar-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .topbar-info { display: flex; gap: 20px; }
    .topbar-info span { display: flex; align-items: center; gap: 6px; }

    /* HEADER / NAVBAR */
    .header {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(229, 231, 235, 0.5);
      padding: 12px 0;
    }

    .nav-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Logo - Menjadi lebih proporsional */
    .brand img {
      height: 36px; /* Ukuran pas, tidak terlalu besar */
      width: auto;
      display: block;
    }

    /* Menu Utama (3 Menu Request) */
    .main-nav {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .nav-link {
      padding: 10px 18px;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-muted);
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .nav-link:hover {
      color: var(--primary);
      background: var(--primary-light);
    }

    .nav-link.active {
      color: var(--primary);
      background: var(--primary-light);
    }

    /* Dropdown Khusus untuk "Lainnya" (Link BAA) */
    .nav-item-dropdown { position: relative; }
    .dropdown-menu {
      position: absolute;
      top: 120%;
      right: 0;
      width: 240px;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow-md);
      border: 1px solid #f1f5f9;
      padding: 8px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: 0.2s ease;
    }
    .nav-item-dropdown:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .dropdown-menu a {
      display: block;
      padding: 10px 14px;
      font-size: 13px;
      color: var(--text-main);
      border-radius: 8px;
    }
    .dropdown-menu a:hover {
      background: #f1f5f9;
      color: var(--primary);
    }

    /* Mobile Toggle */
    .mobile-toggle {
      display: none;
      font-size: 24px;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text-main);
    }

    /* Mobile Menu */
    .mobile-nav {
      position: fixed;
      top: 0;
      left: -100%;
      width: 80%;
      height: 100vh;
      background: var(--white);
      z-index: 1001;
      padding: 40px 24px;
      transition: 0.4s ease;
      box-shadow: 10px 0 30px rgba(0,0,0,0.1);
    }
    .mobile-nav.open { left: 0; }
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      display: none;
    }
    .overlay.show { display: block; }

    .mobile-nav .nav-link {
      margin-bottom: 12px;
      padding: 14px;
      font-size: 16px;
    }

    @media (max-width: 992px) {
      .main-nav, .topbar { display: none; }
      .mobile-toggle { display: block; }
    }
  </style>
</head>
<body>

  <div class="overlay" id="overlay"></div>

  <div class="topbar">
    <div class="container">
      <div class="topbar-content">
        <div class="topbar-info">
          <span><i class="far fa-clock"></i> Sen-Sab 08:00-16:00</span>
          <span><i class="fa-brands fa-whatsapp"></i> 081222400133</span>
        </div>
        <div class="topbar-social">
          <a href="#" style="color:#fff; margin-left:15px"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" style="color:#fff; margin-left:15px"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
    </div>
  </div>

  <header class="header">
    <div class="container">
      <div class="nav-wrapper">
        <a href="https://baa.lpkia.ac.id" class="brand">
          <img src="{{ asset('assets/download.png') }}" alt="Logo LPKIA">
        </a>

        <nav class="main-nav">
          <a href="{{ route('home') }}" class="nav-link">
            <i class="fa-solid fa-house"></i> Home
          </a>
          
          <a href="{{ route('ruangan.index') }}" class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-check"></i> Ruangan
          </a>

          <a href="{{ route('history.index') }}" class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clock-rotate-left"></i> Cek Pengajuan
          </a>

        </nav>

        <button class="mobile-toggle" id="openMobile">
          <i class="fa-solid fa-bars-staggered"></i>
        </button>
      </div>
    </div>
  </header>

  <aside class="mobile-nav" id="mobileNav">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
      <img src="{{ asset('assets/download.png') }}" alt="Logo" height="30">
      <button id="closeMobile" style="background:none; border:none; font-size:24px;">&times;</button>
    </div>
    <a href="https://baa.lpkia.ac.id" class="nav-link"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('ruangan.index') }}" class="nav-link"><i class="fa-solid fa-calendar-check"></i> Ruangan</a>
    <a href="{{ route('history.index') }}" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> Cek Pengajuan</a>
    <hr style="margin: 20px 0; opacity: 0.1;">
    <a href="https://baa.lpkia.ac.id/jadwal" class="nav-link"><i class="fa-solid fa-info-circle"></i> Jadwal & Lainnya</a>
  </aside>

  <main>
    @yield('content')
  </main>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 800, once: true });

    const openBtn = document.getElementById('openMobile');
    const closeBtn = document.getElementById('closeMobile');
    const mobileNav = document.getElementById('mobileNav');
    const overlay = document.getElementById('overlay');

    openBtn.addEventListener('click', () => {
      mobileNav.classList.add('open');
      overlay.classList.add('show');
    });

    closeBtn.addEventListener('click', () => {
      mobileNav.classList.remove('open');
      overlay.classList.remove('show');
    });

    overlay.addEventListener('click', () => {
      mobileNav.classList.remove('open');
      overlay.classList.remove('show');
    });
  </script>

  @yield('scripts')
</body>
</html>