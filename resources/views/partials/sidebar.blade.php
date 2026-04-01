{{-- resources/views/partials/admin/sidebar.blade.php --}}
@php
  $pending   = (int)($stat_pending ?? 0);
  $adminName = session('admin_username', session('username', 'Admin'));
  $role      = session('role', 'admin');

  $isDashboard = request()->routeIs('admin.dashboard');
  $isPengajuan = request()->routeIs('admin.pengajuan') || request()->routeIs('admin.pengajuan.*');
  $isRooms     = request()->routeIs('admin.rooms.*');
  $isQuick     = request()->routeIs('admin.quick_booking');
  $isPBM       = request()->routeIs('admin.pbm.*');
  $isAkun      = request()->routeIs('admin.users.*');
@endphp

<aside class="sb sidebar" aria-label="Sidebar Admin">
  
  {{-- BRAND / PROFILE FOTO (POSISI ATAS) --}}
  <div class="sb__brand">
    <button class="sb__toggle" type="button" onclick="AdminUI.toggleSidebar()" aria-label="Toggle sidebar">
      <i class="fa-solid fa-bars-staggered"></i>
    </button>

    <div class="sb__logo" aria-hidden="true">
      <img src="https://ui-avatars.com/api/?name={{ urlencode($adminName) }}&background=ffffff&color=2563eb&size=150&bold=true" alt="Profile Foto">
    </div>
    
    <div class="sb__titleMain">Peminjaman Ruangan</div>
    <div class="sb__titleBadge">{{ strtoupper($role === 'kemahasiswaan' ? 'KEMA' : 'ADMIN') }}</div>
  </div>

  {{-- NAVIGATION --}}
  <nav class="sb__nav">
    <div class="sb__section">
      <div class="sb__sectionLabel">RINGKASAN</div>
      <a class="sb__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
        <span class="sb__ico"><i class="fa-solid fa-chart-pie"></i></span>
        <span class="sb__text">Dashboard</span>
      </a>
    </div>

    <div class="sb__section">
      <div class="sb__sectionLabel">MANAJEMEN DATA</div>

      <a class="sb__item {{ $isPengajuan ? 'is-active' : '' }}" href="{{ route('admin.pengajuan', ['view'=>'history','status'=>'menunggu']) }}">
        <span class="sb__ico"><i class="fa-solid fa-file-signature"></i></span>
        <span class="sb__text">Pengajuan</span>
        <span class="sb__badgePremium {{ $pending > 0 ? 'is-on' : '' }}" id="sbPendingBadge" data-count="{{ $pending }}" style="{{ $pending > 0 ? '' : 'display:none' }}" title="Pengajuan menunggu">
          <span class="sbp-num">{{ $pending }}</span>
        </span>
      </a>

      <a class="sb__item {{ $isRooms ? 'is-active' : '' }}" href="{{ route('admin.rooms.index') }}">
        <span class="sb__ico"><i class="fa-solid fa-door-open"></i></span>
        <span class="sb__text">Data Ruangan</span>
      </a>



      <a class="sb__item {{ $isPBM ? 'is-active' : '' }}" href="{{ route('admin.pbm.index') }}">
        <span class="sb__ico"><i class="fa-solid fa-calendar-check"></i></span>
        <span class="sb__text">Jadwal Kuliah</span>
      </a>
    </div>

    @if($role === 'admin' || $role === 'superadmin')
      <div class="sb__section">
        <div class="sb__sectionLabel">SISTEM</div>
        <a class="sb__item {{ $isAkun ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
          <span class="sb__ico"><i class="fa-solid fa-users-gear"></i></span>
          <span class="sb__text">Akun Admin</span>
        </a>
      </div>
    @endif
  </nav>

  {{-- FOOTER / ACCOUNT --}}
  <div class="sb__footer">
    <div class="sb__user">
      <div class="sb__avatar"><i class="fa-solid fa-user-tie"></i></div>
      <div class="sb__userinfo">
        <div class="sb__username">{{ $adminName }}</div>
        <div class="sb__role">{{ $role === 'kemahasiswaan' ? 'Kemahasiswaan' : 'Administrator' }}</div>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.logout') }}" style="width: 100%;">
      @csrf
      <button type="submit" class="sb__logout">
        <span class="sb__ico"><i class="fa-solid fa-right-from-bracket"></i></span> <span class="sb__text">Keluar</span>
      </button>
    </form>
  </div>
</aside>

<style>
  :root {
    --sb-bg-top: #3b82f6;    /* Biru cerah atas */
    --sb-bg-bottom: #1d4ed8; /* Biru tua bawah */
    --sb-text: #f8fafc;
    --sb-muted: #bfdbfe;
    
    --sb-radius: 10px;
    --sb-transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .sidebar.sb {
    width: 240px; /* Ukuran diperkecil dari 270px */
    min-width: 240px;
    height: 100vh;
    position: sticky;
    top: 0;
    background: linear-gradient(180deg, var(--sb-bg-top) 0%, var(--sb-bg-bottom) 100%);
    color: var(--sb-text);
    display: flex;
    flex-direction: column;
    transition: width var(--sb-transition);
    overflow-x: hidden;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0,0,0,0.05);
    z-index: 100;
  }

  /* Custom Scrollbar for Sidebar */
  .sidebar.sb::-webkit-scrollbar { width: 3px; }
  .sidebar.sb::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }

  /* --- BRAND & FOTO PROFIL --- */
  .sb__brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 28px 12px 16px;
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 8px;
  }
  .sb__toggle {
    position: absolute; 
    top: 12px; right: 12px;
    background: transparent; 
    border: none; color: rgba(255,255,255,0.7);
    width: 28px; height: 28px; border-radius: 6px;
    cursor: pointer; display: grid; place-items: center;
    font-size: 15px; transition: all 0.2s;
  }
  .sb__toggle:hover { background: rgba(255,255,255,0.15); color: #fff; }

  .sb__logo {
    width: 64px; height: 64px; /* Foto diperkecil dari 80px */
    border-radius: 16px;
    background: #ffffff;
    display: flex; justify-content: center; align-items: center;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-bottom: 12px;
    transition: all var(--sb-transition);
  }
  .sb__logo img { width: 100%; height: 100%; object-fit: cover; }
  
  .sb__titleMain { 
    font-weight: 800; font-size: 15px; 
    letter-spacing: 0.3px; white-space: nowrap; 
    line-height: 1.2; color: #ffffff;
    margin-bottom: 6px;
  }
  .sb__titleBadge { 
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff; font-size: 9px; font-weight: 800; 
    padding: 3px 10px; border-radius: 999px; 
    letter-spacing: 0.8px; white-space: nowrap;
  }

  /* --- COLLAPSED STATE --- */
  html.sb-collapsed .sidebar.sb { width: 76px; min-width: 76px; }
  html.sb-collapsed .sidebar.sb .sb__text,
  html.sb-collapsed .sidebar.sb .sb__titleMain,
  html.sb-collapsed .sidebar.sb .sb__titleBadge,
  html.sb-collapsed .sidebar.sb .sb__userinfo,
  html.sb-collapsed .sidebar.sb .sb__sectionLabel { opacity: 0; display: none; }
  
  html.sb-collapsed .sidebar.sb .sb__brand { padding: 12px 0; border-bottom: none; }
  html.sb-collapsed .sidebar.sb .sb__toggle { position: static; margin-bottom: 12px; background: rgba(255,255,255,0.1); }
  html.sb-collapsed .sidebar.sb .sb__logo { width: 40px; height: 40px; border-radius: 10px; margin-bottom: 0; }
  
  html.sb-collapsed .sidebar.sb .sb__item { justify-content: center; padding: 10px; }
  html.sb-collapsed .sidebar.sb .sb__ico { margin: 0; font-size: 16px; }
  html.sb-collapsed .sidebar.sb .sb__badgePremium { position: absolute; top: 4px; right: 4px; padding: 2px 5px; font-size: 9px;}
  html.sb-collapsed .sidebar.sb .sb__user { justify-content: center; padding: 10px 0; background: transparent; }
  html.sb-collapsed .sidebar.sb .sb__logout { justify-content: center; padding: 10px; }

  /* --- NAVIGATION --- */
  .sb__nav { display: flex; flex-direction: column; gap: 14px; padding: 8px 14px; flex: 1; }
  .sb__section { display: flex; flex-direction: column; gap: 4px; }
  .sb__sectionLabel {
    font-size: 9.5px; font-weight: 700; color: var(--sb-muted);
    letter-spacing: 0.8px; padding: 0 10px; margin-bottom: 2px;
  }

  .sb__item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: var(--sb-radius);
    text-decoration: none; color: rgba(255,255,255,0.85);
    font-weight: 600; font-size: 13px; /* Diperkecil */
    transition: all 0.2s ease;
    position: relative;
    border: 1px solid transparent;
  }
  .sb__item:hover { background: rgba(255,255,255,0.1); color: #fff; }
  
  .sb__item.is-active {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }
  
  .sb__ico { font-size: 14px; width: 20px; text-align: center; transition: color 0.2s; }
  .sb__item.is-active .sb__ico { color: #ffffff; }
  .sb__text { white-space: nowrap; flex: 1; }

  /* --- BADGE --- */
  .sb__badgePremium {
    background: #ef4444; color: #fff;
    font-size: 10px; font-weight: 800;
    padding: 2px 6px; border-radius: 999px;
    box-shadow: 0 2px 4px rgba(239,68,68,0.3);
  }
  .sb__badgePremium.is-on { animation: pulseBadge 2s infinite; }
  @keyframes pulseBadge { 0% { transform: scale(1); } 50% { transform: scale(1.06); } 100% { transform: scale(1); } }

  /* --- FOOTER --- */
  .sb__footer { padding: 14px; margin-top: auto; display: flex; flex-direction: column; gap: 8px; border-top: 1px solid rgba(255,255,255,0.1); }
  .sb__user {
    display: flex; align-items: center; gap: 10px;
    padding: 10px; background: rgba(0,0,0,0.15);
    border-radius: var(--sb-radius);
  }
  .sb__avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,0.15); color: #fff;
    display: grid; place-items: center; font-size: 14px; flex-shrink: 0;
  }
  .sb__username { font-weight: 700; font-size: 12.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; }
  .sb__role { font-size: 10.5px; color: var(--sb-muted); white-space: nowrap; }

  .sb__logout {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 10px 12px;
    border-radius: var(--sb-radius);
    background: transparent; border: 1px solid transparent;
    color: #fca5a5; font-weight: 600; font-size: 12.5px; cursor: pointer;
    transition: all 0.2s;
  }
  .sb__logout:hover { background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.3); color: #fff; }

  /* Responsive */
  @media (max-width: 768px) {
    .sidebar.sb { position: fixed; z-index: 1000; transform: translateX(0); }
    html.sb-collapsed .sidebar.sb { transform: translateX(-100%); }
  }
</style>

<script>
  window.AdminUI = window.AdminUI || (function () {
    const KEY = "sb_collapsed";
    const SEEN_KEY = "sb_pending_seen";
    const LAST_POPUP_KEY = "sb_pending_last_popup";

    function initSidebar(){
      try{
        const saved = localStorage.getItem(KEY);
        // Otomatis collapse di HP
        if (window.innerWidth <= 768) {
            document.documentElement.classList.add("sb-collapsed");
        } else if (saved === "1") {
            document.documentElement.classList.add("sb-collapsed");
        }
      }catch(e){}
    }

    function toggleSidebar(){
      document.documentElement.classList.toggle("sb-collapsed");
      try{
        localStorage.setItem(KEY, document.documentElement.classList.contains("sb-collapsed") ? "1" : "0");
      }catch(e){}
    }

    function setPendingBadge(n){
      const el = document.getElementById("sbPendingBadge");
      if (!el) return;
      const val = parseInt(n || 0, 10) || 0;

      if (val > 0){
        el.style.display = "";
        const num = el.querySelector(".sbp-num");
        if (num) num.textContent = String(val);
        el.dataset.count = String(val);
        el.classList.add("is-on");
      } else {
        el.style.display = "none";
        el.dataset.count = "0";
        el.classList.remove("is-on");
      }
    }

    function ensureSwal(cb){
      try{
        if (window.Swal && typeof window.Swal.fire === "function") return cb();
        const s = document.createElement("script");
        s.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11";
        s.onload = () => cb();
        s.onerror = () => cb();
        document.head.appendChild(s);
      }catch(e){ cb(); }
    }

    function maybeShowPendingPopup(){
      const badge = document.getElementById("sbPendingBadge");
      if (!badge) return;

      const current = parseInt(badge.dataset.count || "0", 10) || 0;
      const url = String(window.location.pathname || "");
      const isSeeingPengajuan = url.indexOf("pengajuan") !== -1;

      try{
        if (isSeeingPengajuan){
          localStorage.setItem(SEEN_KEY, String(current));
          return;
        }
      }catch(e){}

      let seen = 0;
      try{ seen = parseInt(localStorage.getItem(SEEN_KEY) || "0", 10) || 0; }catch(e){}

      if (seen === 0 && current > 0){
        try{ localStorage.setItem(SEEN_KEY, String(current)); }catch(e){}
        return;
      }

      if (current > seen){
        const diff = current - seen;
        let lastPopupAt = 0;
        try{ lastPopupAt = parseInt(localStorage.getItem(LAST_POPUP_KEY) || "0", 10) || 0; }catch(e){}
        const now = Date.now();
        if (now - lastPopupAt < 1500) return;

        try{ localStorage.setItem(LAST_POPUP_KEY, String(now)); }catch(e){}

        ensureSwal(function(){
          const goUrl = @json(route('admin.pengajuan', ['view'=>'history','status'=>'menunggu']));

          if (window.Swal && typeof window.Swal.fire === "function"){
            Swal.fire({
              icon: "info",
              title: "Pengajuan Masuk",
              html: `<div style="color:#334155;font-size:14px;line-height:1.5">
                      Ada <b>${diff}</b> pengajuan baru masuk.<br>Total menunggu: <b>${current}</b>.
                     </div>`,
              showCancelButton: true,
              confirmButtonText: "Buka Pengajuan",
              cancelButtonText: "Nanti Saja",
              confirmButtonColor: "#2563eb",
              cancelButtonColor: "#94a3b8",
            }).then((r)=>{
              if (r.isConfirmed) window.location.href = goUrl;
              try{ localStorage.setItem(SEEN_KEY, String(current)); }catch(e){}
            });
          } else {
            const go = confirm(`Pengajuan masuk (${diff} baru). Total menunggu: ${current}.\nBuka halaman Pengajuan?`);
            if (go) window.location.href = goUrl;
            try{ localStorage.setItem(SEEN_KEY, String(current)); }catch(e){}
          }
        });
      }
    }

    return { initSidebar, toggleSidebar, setPendingBadge, maybeShowPendingPopup };
  })();

  (function(){
    const run = function(){
      try{ AdminUI.initSidebar(); }catch(e){}
      try{ AdminUI.maybeShowPendingPopup(); }catch(e){}
    };
    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", run);
    else run();
  })();
</script>