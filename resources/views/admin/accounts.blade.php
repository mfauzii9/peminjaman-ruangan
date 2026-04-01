{{-- resources/views/admin/akun/index.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Admin - Akun & Profil</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --text:#0b1220;
      --muted:#64748b;
      --border:#e8ebf3;

      --shadow: 0 18px 60px rgba(2,6,23,.10);
      --shadow2: 0 10px 26px rgba(2,6,23,.06);

      --radius:18px;
      --radius2:14px;

      --accent:#2563eb;
      --accent2:#1d4ed8;
      --danger:#ef4444;
      --success:#10b981;

      --fs-2xs:10.5px;
      --fs-xs:11.2px;
      --fs-sm:12.1px;
      --fs-md:12.8px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      font-size: var(--fs-md);
      line-height: 1.65;
      font-weight: 400;
    }

    .app{ display:flex; min-height:100vh; }
    .main{ flex:1; min-width:0; }

    .main-top{
      position:sticky; top:0; z-index:60;
      background: rgba(246,247,251,.85);
      backdrop-filter: blur(14px);
      border-bottom:1px solid var(--border);
      padding: 12px 16px;
      display:flex; align-items:center; justify-content:space-between; gap:12px;
      flex-wrap:wrap;
    }

    .crumb{
      display:flex; align-items:center; gap:10px; flex-wrap:wrap; min-width: 220px;
    }

    .pill{
      display:inline-flex; align-items:center; gap:10px;
      padding: 8px 12px;
      border-radius:999px;
      background: rgba(37,99,235,.10);
      border:1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
      font-weight: 600;
      font-size: var(--fs-sm);
      white-space: nowrap;
    }

    .muted{
      color: var(--muted);
      font-size: var(--fs-sm);
      font-weight: 400;
    }

    .container{
      max-width: 1040px;
      margin: 16px auto 30px;
      padding: 0 16px;
      min-width:0;
    }

    .stack{ display:grid; gap: 12px; min-width:0; }

    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:9px;
      padding: 10px 12px;
      border-radius: var(--radius2);
      border:1px solid var(--border);
      background:#fff;
      color: var(--text);
      text-decoration:none;
      font-weight: 600;
      font-size: var(--fs-sm);
      transition:.15s ease;
      cursor:pointer;
      white-space: nowrap;
      box-shadow: var(--shadow2);
      user-select:none;
    }
    .btn:hover{ transform: translateY(-1px); background:#f1f5ff; }
    .btn:active{ transform: translateY(0px); }

    .btn-primary{
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color:#fff; border-color:transparent;
      box-shadow: 0 18px 40px rgba(37,99,235,.18);
    }

    .btn-danger{
      background:#fff;
      border-color: rgba(239,68,68,.25);
      color:#b91c1c;
      box-shadow:none;
    }
    .btn-danger:hover{ background:#fff1f2; }

    .btn-mini{
      padding: 8px 10px;
      border-radius: 12px;
      box-shadow: none;
      font-size: var(--fs-xs);
      font-weight: 600;
    }

    .card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow2);
      overflow:hidden;
      min-width:0;
    }

    .cardHead{
      padding: 12px 14px;
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      border-bottom: 1px solid #f0f2f7;
      background:#fff;
      flex-wrap:wrap;
    }

    .cardTitle{
      display:flex; align-items:center; gap:10px;
      font-weight: 600;
      font-size: var(--fs-sm);
    }

    .cardTitle i{
      width:34px;height:34px;border-radius:14px;
      display:grid;place-items:center;
      background: rgba(37,99,235,.08);
      border:1px solid rgba(37,99,235,.18);
      color:#1d4ed8;
    }

    .tools{
      display:flex; gap:10px; align-items:center; flex-wrap:wrap;
      padding: 12px 14px;
      border-top: 1px solid #f0f2f7;
      border-bottom: 1px solid #f0f2f7;
      background:#fbfcff;
    }

    .field{
      display:flex; align-items:center; gap:8px;
      padding: 10px 12px;
      border: 1px solid var(--border);
      border-radius: 14px;
      background:#fff;
      transition:.15s ease;
      min-width: 240px;
      flex: 1;
      max-width: 460px;
    }

    .field:focus-within{
      border-color: rgba(37,99,235,.25);
      box-shadow: 0 0 0 4px rgba(37,99,235,.08);
    }

    .field i{ color:#64748b; font-size: 12px; }

    .field input{
      border:none; outline:none; width:100%;
      font: inherit;
      font-size: var(--fs-sm);
      font-weight: 400;
      background: transparent;
      min-width:0;
    }

    .spacer{ flex:1; min-width: 10px; }

    .tablewrap{ width:100%; overflow:auto; }
    table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      min-width: 920px;
      background:#fff;
    }

    thead th{
      position: sticky;
      top: 0;
      z-index: 5;
      background:#f8fafc;
      border-bottom:1px solid var(--border);
      padding: 10px 12px;
      text-align:left;
      font-size: var(--fs-2xs);
      font-weight: 600;
      color:#334155;
      letter-spacing:.12em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    tbody td{
      padding: 10px 12px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      font-size: var(--fs-sm);
      font-weight: 400;
      color:#111827;
      background:#fff;
    }

    tbody tr:hover td{ background:#fbfdff; }

    .idpill{
      display:inline-flex; align-items:center; gap:7px;
      padding: 6px 8px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background:#fff;
      white-space: nowrap;
      font-size: var(--fs-sm);
      font-weight: 500;
    }

    .uName{ font-weight: 600; }
    .uSub{
      color: var(--muted);
      font-size: var(--fs-xs);
      margin-top: 3px;
      font-weight: 400;
    }

    .role{
      display:inline-flex; align-items:center; gap:7px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: var(--fs-xs);
      font-weight: 500;
      border: 1px solid var(--border);
      background:#fff;
      white-space: nowrap;
      color:#334155;
    }

    .role.admin{
      border-color: rgba(37,99,235,.20);
      background: rgba(37,99,235,.08);
      color:#1d4ed8;
    }

    .role.kema{
      border-color: rgba(16,185,129,.22);
      background: rgba(16,185,129,.10);
      color:#047857;
    }

    .actBtns{
      display:flex; gap:8px; align-items:center; justify-content:flex-end; flex-wrap:wrap;
    }

    .footerBar{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      padding: 12px 14px;
      border-top: 1px solid #f0f2f7;
      background:#fff;
      flex-wrap:wrap;
    }

    /* profile dropdown */
    .top-actions{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
      position:relative;
    }

    .profileMenu{
      position:relative;
    }

    .profileBtn{
      display:flex;
      align-items:center;
      gap:10px;
      padding: 7px 10px 7px 8px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background:#fff;
      cursor:pointer;
      box-shadow: var(--shadow2);
      transition:.15s ease;
    }

    .profileBtn:hover{
      background:#f8fbff;
      transform: translateY(-1px);
    }

    .profileAvatar{
      width:34px;
      height:34px;
      border-radius:999px;
      display:grid;
      place-items:center;
      background: linear-gradient(135deg, #dcfce7, #bbf7d0);
      color:#15803d;
      font-weight:700;
      font-size:13px;
      overflow:hidden;
      border:1px solid #ccead6;
      flex:0 0 34px;
    }

    .profileAvatar img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .profileMeta{
      display:flex;
      flex-direction:column;
      align-items:flex-start;
      line-height:1.2;
    }

    .profileName{
      font-size:12px;
      font-weight:700;
      color:#0f172a;
    }

    .profileRole{
      font-size:10.5px;
      color:#64748b;
      font-weight:500;
    }

    .profileDropdown{
      position:absolute;
      top: calc(100% + 10px);
      right:0;
      width: 220px;
      background:#fff;
      border:1px solid var(--border);
      border-radius:16px;
      box-shadow: 0 18px 50px rgba(2,6,23,.12);
      padding:8px;
      display:none;
      z-index:90;
    }

    .profileDropdown.show{ display:block; }

    .dropdownHead{
      padding:10px 12px 8px;
      border-bottom:1px solid #eef2f7;
      margin-bottom:6px;
    }

    .dropdownHead .name{
      font-size:12px;
      font-weight:700;
      color:#0f172a;
    }

    .dropdownHead .sub{
      font-size:11px;
      color:#64748b;
      margin-top:3px;
    }

    .dropdownItem{
      width:100%;
      display:flex;
      align-items:center;
      gap:10px;
      border:none;
      background:#fff;
      padding:10px 12px;
      border-radius:12px;
      cursor:pointer;
      font:inherit;
      font-size:12px;
      font-weight:600;
      color:#0f172a;
      text-align:left;
    }

    .dropdownItem:hover{
      background:#f8fbff;
    }

    .dropdownItem.danger{
      color:#b91c1c;
    }

    .dropdownItem.danger:hover{
      background:#fff1f2;
    }

    /* swal size kecil + aman agar tidak terpotong */
    .swal2-container{
      padding: 14px !important;
    }

    .swal2-popup{
      border-radius: 18px !important;
      padding: 16px !important;
      width: 420px !important;
      max-width: calc(100vw - 28px) !important;
    }

    .swal2-title{
      font-size: 14px !important;
      font-weight: 700 !important;
      padding-right: 18px !important;
    }

    .swal2-html-container{
      font-size: 12px !important;
      margin-top: 8px !important;
      overflow: visible !important;
    }

    .swal2-input,
    .swal2-select{
      height: 40px !important;
      font-size: 12px !important;
      border-radius: 12px !important;
      border: 1px solid #e8ebf3 !important;
      box-shadow: none !important;
      margin-left:auto !important;
      margin-right:auto !important;
    }

    .swal2-input:focus,
    .swal2-select:focus{
      border-color: rgba(37,99,235,.35) !important;
      box-shadow: 0 0 0 4px rgba(37,99,235,.10) !important;
    }

    .swal2-actions{
      margin-top: 14px !important;
      gap:8px !important;
    }

    .swal2-confirm,
    .swal2-cancel{
      border-radius: 12px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      padding: 8px 14px !important;
    }

    .sw-label{
      display:block;
      margin:8px 0 6px;
      color:#64748b;
      font-size:11.2px;
      font-weight:600;
      text-align:left;
    }

    .sw-note{
      font-size:11.2px;
      color:#64748b;
      margin:2px 0 10px;
      line-height:1.55;
      text-align:left;
    }

    .profileViewBox{
      display:grid;
      gap:12px;
      text-align:left;
    }

    .profileViewTop{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .profileViewAvatar{
      width:54px;
      height:54px;
      border-radius:999px;
      display:grid;
      place-items:center;
      background: linear-gradient(135deg, #dbeafe, #bfdbfe);
      color:#1d4ed8;
      font-size:20px;
      border:1px solid #cfe0ff;
      overflow:hidden;
      flex:0 0 54px;
    }

    .profileViewAvatar img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .profileViewName{
      font-size:14px;
      font-weight:700;
      color:#0f172a;
      line-height:1.2;
    }

    .profileViewRole{
      font-size:11px;
      color:#64748b;
      margin-top:4px;
      text-transform: capitalize;
    }

    .profileInfoGrid{
      display:grid;
      gap:8px;
    }

    .profileInfoItem{
      border:1px solid #edf2f7;
      background:#fbfdff;
      border-radius:12px;
      padding:10px 12px;
    }

    .profileInfoLabel{
      font-size:10.8px;
      color:#64748b;
      font-weight:600;
      margin-bottom:4px;
    }

    .profileInfoValue{
      font-size:12px;
      color:#0f172a;
      font-weight:600;
      word-break:break-word;
    }

    @media (max-width: 820px){
      .app{ flex-direction:column; }
      .main-top{ position:relative; }
      table{ min-width: 920px; }
      .field{ max-width: 100%; }
      .profileDropdown{
        right:auto;
        left:0;
      }
    }
  </style>

  <script>document.documentElement.classList.remove('sb-collapsed');</script>
</head>

<body>
  <div class="app">
    @include('partials.sidebar')

    <div class="main">
      <header class="main-top">
        <div class="crumb">
          <div class="pill"><i class="fa-solid fa-user-gear"></i> Akun & Profil</div>
          <span class="muted">Kelola akun + ubah profil.</span>
        </div>

        <div class="top-actions">
          <button class="btn btn-primary" id="btnAddUser" type="button">
            <i class="fa-solid fa-user-plus"></i> Tambah Akun
          </button>

          <div class="profileMenu" id="profileMenu">
            <button class="profileBtn" id="profileBtn" type="button">
              <div class="profileAvatar">
                @if(!empty($me['foto']))
                  <img src="{{ $me['foto'] }}" alt="Profil">
                @else
                  <i class="fa-solid fa-user"></i>
                @endif
              </div>

              <div class="profileMeta">
                <div class="profileName">{{ $me['username'] ?? 'Admin' }}</div>
                <div class="profileRole">{{ $me['role'] ?? 'admin' }}</div>
              </div>

              <i class="fa-solid fa-chevron-down" style="font-size:11px;color:#64748b;"></i>
            </button>

            <div class="profileDropdown" id="profileDropdown">
              <div class="dropdownHead">
                <div class="name">{{ $me['username'] ?? 'Admin' }}</div>
                <div class="sub">{{ $me['role'] ?? 'admin' }}</div>
              </div>

              <button class="dropdownItem" type="button" id="btnViewProfile">
                <i class="fa-solid fa-id-badge"></i> Lihat Profil
              </button>

              <button class="dropdownItem" type="button" id="btnEditProfile">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="container">
        <div class="stack">
          {{-- KELOLA AKUN --}}
          <section class="card">
            <div class="cardHead">
              <div class="cardTitle">
                <i class="fa-solid fa-users-gear"></i>
                <span>Kelola Akun</span>
              </div>
              <div class="muted">
                Total: <b id="accCount">{{ count($users ?? []) }}</b>
              </div>
            </div>

            <div class="tools">
              <div class="field">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="accSearch" placeholder="Cari username...">
              </div>

              <div class="spacer"></div>

              <div class="muted">Ditampilkan: <b id="accShown">{{ count($users ?? []) }}</b></div>
            </div>

            <div class="tablewrap">
              <table id="usersTable">
                <thead>
                  <tr>
                    <th style="width:110px;">ID</th>
                    <th>Username</th>
                    <th style="width:170px;">Role</th>
                    <th style="width:220px;">Dibuat</th>
                    <th style="width:230px; text-align:right;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $u)
                    <tr class="u-row" data-id="{{ $u->id }}" data-username="{{ strtolower($u->username) }}">
                      <td>
                        <span class="idpill">
                          <i class="fa-solid fa-hashtag"></i> {{ $u->id }}
                        </span>
                      </td>

                      <td>
                        <div class="uName">{{ $u->username }}</div>
                        <div class="uSub">akun sistem</div>
                      </td>

                      <td>
                        @if($u->role === 'admin')
                          <span class="role admin"><i class="fa-solid fa-shield"></i> admin</span>
                        @else
                          <span class="role kema"><i class="fa-solid fa-user-shield"></i> kemahasiswaan</span>
                        @endif
                      </td>

                      <td class="muted">{{ $u->created_at }}</td>

                      <td>
                        <div class="actBtns">
                          <button class="btn btn-mini" type="button" onclick="AccUI.editUser({{ $u->id }})">
                            <i class="fa-solid fa-pen"></i> Edit
                          </button>
                          <button class="btn btn-mini btn-danger" type="button" onclick="AccUI.deleteUser({{ $u->id }})">
                            <i class="fa-solid fa-trash"></i> Hapus
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="muted" style="padding:16px; text-align:center;">
                        Belum ada akun.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="footerBar">
              <div class="muted">Aksi menggunakan popup SweetAlert.</div>
              <div class="muted">Gunakan <b>Edit</b> untuk ubah role/password.</div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    window.AccUI = window.AccUI || (function(){
      const csrf = @json(csrf_token());
      const baseUsersUrl = @json(url('/admin/akun/users'));
      const me = @json($me ?? []);

      function ensureSwal(cb){
        if (window.Swal && typeof Swal.fire === "function") return cb();
        const s = document.createElement("script");
        s.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11";
        s.onload = cb;
        s.onerror = cb;
        document.head.appendChild(s);
      }

      async function api(url, method, data){
        const form = new FormData();
        form.append('_token', csrf);

        if (method === 'PUT' || method === 'DELETE') {
          form.append('_method', method);
          method = 'POST';
        }

        if (data) Object.keys(data).forEach(k => form.append(k, data[k] ?? ''));

        const res = await fetch(url, {
          method,
          body: form,
          headers: { 'Accept':'application/json' }
        });

        const text = await res.text();
        let json = {};
        try {
          json = JSON.parse(text);
        } catch(e) {
          json = { message: text };
        }

        if (!res.ok) throw new Error(json.message || 'Terjadi kesalahan');
        return json;
      }

      function toast(icon, title){
        ensureSwal(function(){
          Swal.fire({
            toast:true,
            position:'bottom-end',
            timer:2200,
            showConfirmButton:false,
            icon,
            title
          });
        });
      }

      function getMeRoleLabel(role){
        if (!role) return '-';
        return String(role).replaceAll('_', ' ');
      }

      function avatarHtmlSmall(){
        if (me && me.foto) {
          return `<img src="${me.foto}" alt="Profil">`;
        }
        return `<i class="fa-solid fa-user"></i>`;
      }

      function openProfileMenu(){
        const dd = document.getElementById('profileDropdown');
        if (dd) dd.classList.toggle('show');
      }

      function closeProfileMenu(){
        const dd = document.getElementById('profileDropdown');
        if (dd) dd.classList.remove('show');
      }

      function viewProfile(){
        closeProfileMenu();

        ensureSwal(function(){
          Swal.fire({
            title: 'Profil Saya',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#2563eb',
            html: `
              <div class="profileViewBox">
                <div class="profileViewTop">
                  <div class="profileViewAvatar">${avatarHtmlSmall()}</div>
                  <div>
                    <div class="profileViewName">${me.username ?? 'Admin'}</div>
                    <div class="profileViewRole">${getMeRoleLabel(me.role ?? 'admin')}</div>
                  </div>
                </div>

                <div class="profileInfoGrid">
                  <div class="profileInfoItem">
                    <div class="profileInfoLabel">Username</div>
                    <div class="profileInfoValue">${me.username ?? '-'}</div>
                  </div>

                  <div class="profileInfoItem">
                    <div class="profileInfoLabel">Role</div>
                    <div class="profileInfoValue">${getMeRoleLabel(me.role ?? 'admin')}</div>
                  </div>

                  <div class="profileInfoItem">
                    <div class="profileInfoLabel">Informasi</div>
                    <div class="profileInfoValue">Gunakan menu edit profil untuk mengganti username atau password.</div>
                  </div>
                </div>
              </div>
            `
          });
        });
      }

      function editMyProfile(){
        closeProfileMenu();

        ensureSwal(function(){
          Swal.fire({
            title: 'Edit Profil',
            html: `
              <div style="text-align:left;">
                <div class="sw-note">
                  Ubah akun yang sedang login. Password boleh dikosongkan jika tidak ingin diganti.
                </div>

                <label class="sw-label">Username</label>
                <input id="sw_me_username" class="swal2-input" value="${me.username ?? ''}" placeholder="username" style="margin:0; width:100%;">

                <label class="sw-label">Password Baru (opsional)</label>
                <input id="sw_me_password" type="password" class="swal2-input" placeholder="Kosongkan jika tidak diubah" style="margin:0; width:100%;">
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            preConfirm: async () => {
              const username = (document.getElementById('sw_me_username').value || '').trim();
              const password = (document.getElementById('sw_me_password').value || '');

              if (!username || username.length < 3) {
                return Swal.showValidationMessage('Username minimal 3 karakter');
              }

              const payload = { username };

              if (password && password.length > 0) {
                if (password.length < 4) {
                  return Swal.showValidationMessage('Password minimal 4 karakter');
                }
                payload.password = password;
              }

              return api(@json(route('admin.users.profile.update')), 'POST', payload);
            }
          }).then((r) => {
            if (r.isConfirmed) {
              toast('success', 'Profil berhasil disimpan');
              setTimeout(() => window.location.reload(), 650);
            }
          });
        });
      }

      function addUser(){
        ensureSwal(function(){
          Swal.fire({
            title: 'Tambah Akun',
            html: `
              <div style="text-align:left;">
                <div class="sw-note">
                  Buat akun baru untuk admin atau kemahasiswaan.
                </div>

                <label class="sw-label">Username</label>
                <input id="sw_username" class="swal2-input" placeholder="username" style="margin:0; width:100%;">

                <label class="sw-label">Password</label>
                <input id="sw_password" type="password" class="swal2-input" placeholder="password" style="margin:0; width:100%;">

                <label class="sw-label">Role</label>
                <select id="sw_role" class="swal2-select" style="width:100%; margin-top:0;">
                  <option value="admin">admin</option>
                  <option value="kemahasiswaan">kemahasiswaan</option>
                </select>
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            preConfirm: async () => {
              const username = document.getElementById('sw_username').value.trim();
              const password = document.getElementById('sw_password').value;
              const role = document.getElementById('sw_role').value;

              if (!username || username.length < 3) {
                return Swal.showValidationMessage('Username minimal 3 karakter');
              }

              if (!password || password.length < 4) {
                return Swal.showValidationMessage('Password minimal 4 karakter');
              }

              return api(@json(route('admin.users.store')), 'POST', { username, password, role });
            }
          }).then((r)=>{
            if(r.isConfirmed){
              toast('success', 'Akun berhasil dibuat');
              setTimeout(()=>window.location.reload(), 650);
            }
          });
        });
      }

      async function editUser(id){
        try{
          const res = await fetch(baseUsersUrl + '/' + id, {
            headers: { 'Accept':'application/json' }
          });

          const user = await res.json();
          if (!res.ok) throw new Error(user.message || 'Gagal mengambil data user');

          ensureSwal(function(){
            Swal.fire({
              title: 'Edit Akun',
              html: `
                <div style="text-align:left;">
                  <div class="sw-note">
                    Ubah username, role, atau isi password baru bila ingin mengganti.
                  </div>

                  <label class="sw-label">Username</label>
                  <input id="sw_username" class="swal2-input" value="${user.username ?? ''}" style="margin:0; width:100%;">

                  <label class="sw-label">Password Baru (opsional)</label>
                  <input id="sw_password" type="password" class="swal2-input" placeholder="kosongkan jika tidak diubah" style="margin:0; width:100%;">

                  <label class="sw-label">Role</label>
                  <select id="sw_role" class="swal2-select" style="width:100%; margin-top:0;">
                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>admin</option>
                    <option value="kemahasiswaan" ${user.role === 'kemahasiswaan' ? 'selected' : ''}>kemahasiswaan</option>
                  </select>
                </div>
              `,
              showCancelButton: true,
              confirmButtonText: 'Update',
              cancelButtonText: 'Batal',
              confirmButtonColor: '#2563eb',
              preConfirm: async () => {
                const username = document.getElementById('sw_username').value.trim();
                const password = document.getElementById('sw_password').value;
                const role = document.getElementById('sw_role').value;

                if (!username || username.length < 3) {
                  return Swal.showValidationMessage('Username minimal 3 karakter');
                }

                const payload = { username, role };

                if (password && password.length > 0) {
                  if (password.length < 4) {
                    return Swal.showValidationMessage('Password minimal 4 karakter');
                  }
                  payload.password = password;
                }

                return api(baseUsersUrl + '/' + id, 'PUT', payload);
              }
            }).then((r)=>{
              if(r.isConfirmed){
                toast('success', 'Akun berhasil diupdate');
                setTimeout(()=>window.location.reload(), 650);
              }
            });
          });
        }catch(e){
          toast('error', e.message || 'Gagal');
        }
      }

      function deleteUser(id){
        ensureSwal(function(){
          Swal.fire({
            icon: 'warning',
            title: 'Hapus Akun?',
            html: `
              <div style="color:#64748b;font-size:12px;line-height:1.5;text-align:left;">
                Akun akan dihapus permanen dan tidak bisa dibatalkan.
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444'
          }).then(async (r)=>{
            if(!r.isConfirmed) return;

            try{
              await api(baseUsersUrl + '/' + id, 'DELETE', {});
              toast('success', 'Akun berhasil dihapus');
              setTimeout(()=>window.location.reload(), 650);
            }catch(e){
              toast('error', e.message || 'Gagal hapus');
            }
          });
        });
      }

      function bindProfileMenu(){
        const btn = document.getElementById('profileBtn');
        const menu = document.getElementById('profileMenu');
        const viewBtn = document.getElementById('btnViewProfile');
        const editBtn = document.getElementById('btnEditProfile');

        if (btn) {
          btn.addEventListener('click', function(e){
            e.stopPropagation();
            openProfileMenu();
          });
        }

        if (viewBtn) {
          viewBtn.addEventListener('click', viewProfile);
        }

        if (editBtn) {
          editBtn.addEventListener('click', editMyProfile);
        }

        document.addEventListener('click', function(e){
          if (!menu) return;
          if (!menu.contains(e.target)) {
            closeProfileMenu();
          }
        });
      }

      function bindSearch(){
        const search = document.getElementById('accSearch');
        if (!search) return;

        search.addEventListener('input', function(){
          const q = (search.value || '').toLowerCase().trim();
          const rows = document.querySelectorAll('#usersTable .u-row');
          let visible = 0;

          rows.forEach(tr => {
            const uname = tr.getAttribute('data-username') || '';
            const show = uname.includes(q);
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
          });

          const cnt = document.getElementById('accCount');
          const shown = document.getElementById('accShown');
          if (cnt) cnt.textContent = String(visible);
          if (shown) shown.textContent = String(visible);
        });
      }

      function bind(){
        const add = document.getElementById('btnAddUser');
        if (add) add.addEventListener('click', addUser);

        bindSearch();
        bindProfileMenu();
      }

      return {
        bind,
        addUser,
        editUser,
        deleteUser,
        viewProfile,
        editMyProfile
      };
    })();

    document.addEventListener('DOMContentLoaded', function(){
      AccUI.bind();
    });
  </script>
</body>
</html>