<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

:root{
--primary:#0ea5e9;
--primary-dark:#0369a1;
--navy:#0f172a;
--muted:#64748b;
--white:#ffffff;
--bg:#eef6fb;
--line:#e2e8f0;
--shadow:0 25px 60px rgba(15,23,42,.14);
--radius:24px;
}

*{box-sizing:border-box}

body{
margin:0;
font-family:"Segoe UI",sans-serif;
background:linear-gradient(180deg,#f8fcff,#eef6fb);
color:var(--navy);
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:20px;
}

.login-card{
width:100%;
max-width:940px;
background:#fff;
border-radius:var(--radius);
overflow:hidden;
box-shadow:var(--shadow);
display:grid;
grid-template-columns:1fr 1.1fr;
}

.left-panel{
background:linear-gradient(135deg,#22d3ee,#0ea5e9,#0369a1);
color:white;
display:flex;
align-items:center;
justify-content:center;
padding:40px;
border-top-right-radius:90px;
border-bottom-right-radius:90px;
}

.left-content{
max-width:260px;
text-align:center;
}

.left-content h2{
font-size:22px;
margin-bottom:12px;
font-weight:700;
line-height:1.5;
}

.left-content p{
font-size:13.5px;
line-height:1.8;
opacity:.95;
}

.right-panel{
display:flex;
align-items:center;
justify-content:center;
padding:40px;
}

.form-box{
width:100%;
max-width:360px;
}

.form-title{
text-align:center;
margin-bottom:25px;
}

.form-title h1{
margin:0;
font-size:28px;
font-weight:700;
}

.form-title p{
font-size:13px;
color:var(--muted);
margin-top:8px;
}

.alert{
background:#fff7ed;
border:1px solid #fdba74;
color:#c2410c;
padding:12px;
border-radius:12px;
font-size:13px;
margin-bottom:16px;
display:flex;
gap:8px;
align-items:flex-start;
}

.field{
margin-bottom:16px;
}

label{
font-size:12.5px;
font-weight:700;
display:block;
margin-bottom:6px;
color:#334155;
}

.input-wrap{
position:relative;
}

.input-wrap input{
width:100%;
height:48px;
border:1px solid #e2e8f0;
border-radius:10px;
padding:0 50px 0 14px;
font-size:14px;
outline:none;
}

.input-wrap input:focus{
border-color:#0ea5e9;
box-shadow:0 0 0 3px rgba(14,165,233,.12);
}

.input-wrap .icon{
position:absolute;
right:0;
top:0;
height:100%;
width:42px;
background:#f1f5f9;
border-left:1px solid #e2e8f0;
display:flex;
align-items:center;
justify-content:center;
color:#475569;
border-radius:0 10px 10px 0;
font-size:14px;
}

.actions{
text-align:right;
margin-bottom:16px;
}

.actions a{
font-size:12px;
color:#64748b;
text-decoration:none;
}

.actions a:hover{
color:#0ea5e9;
}

.btn{
width:100%;
height:46px;
border:none;
border-radius:999px;
background:linear-gradient(90deg,#0f172a,#0ea5e9,#22d3ee);
color:white;
font-weight:700;
font-size:14px;
cursor:pointer;
box-shadow:0 12px 25px rgba(14,165,233,.25);
}

.btn:hover{
transform:translateY(-1px);
}

.hint{
margin-top:16px;
font-size:12.5px;
text-align:center;
color:var(--muted);
line-height:1.7;
}

.hint b{
color:var(--primary-dark);
}

@media(max-width:860px){

.login-card{
grid-template-columns:1fr;
max-width:460px;
}

.left-panel{
border-radius:0;
border-bottom-left-radius:70px;
border-bottom-right-radius:70px;
padding:30px;
}

.left-content h2{
font-size:20px;
}

}

</style>
</head>

<body>

<div class="login-card">
  <div class="left-panel">
    <div class="left-content">
      <h2>
          Selamat datang di Institut Digital Ekonomi LPKIA Bandung
      </h2>
      <p>
          Akses admin dan kemahasiswaan. Silakan login terlebih dahulu
          untuk mengelola peminjaman ruangan.
      </p>
    </div>
</div>

<div class="right-panel">

<div class="form-box">

<div class="form-title">
<h1>Login</h1>
<p>Masukkan username dan password Anda</p>
</div>

@if (session('err'))
<div class="alert">
<i class="fa-solid fa-circle-exclamation"></i>
<div>{{ session('err') }}</div>
</div>
@endif

@if ($errors->any())
<div class="alert">
<i class="fa-solid fa-circle-exclamation"></i>
<div>{{ $errors->first() }}</div>
</div>
@endif

<form method="post" action="{{ route('admin.login.submit') }}" autocomplete="off">
@csrf

<div class="field">
<label>Username</label>
<div class="input-wrap">
<input name="username" required placeholder="Masukkan username" value="{{ old('username') }}">
<span class="icon">
<i class="fa-solid fa-user"></i>
</span>
</div>
</div>

<div class="field">
<label>Password</label>
<div class="input-wrap">
<input name="password" type="password" required placeholder="Masukkan password">
<span class="icon">
<i class="fa-solid fa-lock"></i>
</span>
</div>
</div>


<button class="btn" type="submit">
Login
</button>

</form>

<div class="hint">
Gunakan akun <b>admin</b> atau <b>kemahasiswaan</b>
untuk mengelola pengajuan peminjaman ruangan.
</div>

</div>

</div>

</div>

</body>
</html>