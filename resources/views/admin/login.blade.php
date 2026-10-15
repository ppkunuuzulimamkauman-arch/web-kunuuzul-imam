<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — PP Kunuuzul Imam Kauman</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
  :root {
    --bg-base: #e4e9f2;
    --bg-cream: #ece7db;
    --shadow-dark: #b8bfd0;
    --shadow-light: #ffffff;
    --text-dark: #1b2a4a;
    --text-muted: #8a93a8;
    --luxury-navy: #1b2a4a;
    --luxury-navy-2: #2c3f66;
  }
  body {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--bg-cream), var(--bg-base));
    padding: 20px;
  }

  /* ===== shell ===== */
  .shell {
    position: relative; width: 1000px; max-width: 100%; min-height: 620px;
    background: var(--bg-base); border-radius: 26px; overflow: hidden;
    box-shadow: 12px 12px 24px var(--shadow-dark), -12px -12px 24px var(--shadow-light);
    display: flex;
  }
  /* ===== form containers (a = sign in, b = request access) ===== */
  .a-container, .b-container {
    width: 50%; display: flex; align-items: center; justify-content: center;
    padding: 40px 56px; transition: opacity .5s ease, transform .7s cubic-bezier(.65,0,.35,1);
  }
  .a-container { opacity: 1; z-index: 5; }
  .b-container { opacity: 0; z-index: 1; position: absolute; right: 0; top: 0; height: 100%; transform: translateX(40px); pointer-events: none; }
  .shell.is-switched .a-container { opacity: 0; z-index: 1; transform: translateX(40px); pointer-events: none; }
  .shell.is-switched .b-container { opacity: 1; z-index: 5; transform: translateX(0); pointer-events: auto; }
  .form { width: 100%; max-width: 330px; text-align: center; }
  .form h1 { color: var(--text-dark); font-size: 27px; margin-bottom: 6px; }
  .form .sub { font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px; }
  .social { display: flex; gap: 12px; justify-content: center; margin: 14px 0; }
  .social span {
    width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: var(--text-dark); font-size: 15px; background: var(--bg-base);
    box-shadow: 5px 5px 12px var(--shadow-dark), -5px -5px 12px var(--shadow-light);
  }
  .field {
    width: 100%; border: none; outline: none; margin: 7px 0; padding: 14px 18px;
    border-radius: 14px; font-size: 14.5px; font-weight: 500; letter-spacing: .4px;
    color: var(--text-dark); background: var(--bg-base);
    box-shadow: inset 5px 5px 12px var(--shadow-dark), inset -5px -5px 12px var(--shadow-light);
    font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif; resize: none; caret-color: var(--luxury-navy);
    transition: box-shadow .3s ease, transform .3s ease, background-color .3s ease;
  }
  .field::placeholder { color: var(--text-muted); transition: opacity .25s ease, transform .25s ease; }
  .field:hover { transform: translateY(-1px); }
  .field:focus {
    transform: translateY(-2px) scale(1.01);
    background: #f5f7fc;
    box-shadow: inset 3px 3px 8px var(--shadow-dark), inset -3px -3px 8px var(--shadow-light),
      0 0 0 3px rgba(27,42,74,.16), 8px 12px 24px rgba(27,42,74,.18);
  }
  .field:focus::placeholder { opacity: 0; transform: translateX(10px); }
  .row { display: flex; align-items: center; justify-content: space-between; margin: 10px 2px 2px; font-size: 12.5px; color: var(--text-muted); }
  .pw-login { position: relative; }
  .pw-login .field { padding-right: 48px; }
  .pw-login-eye { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; color: var(--text-muted); cursor: pointer; font-size: 15px; padding: 8px; }
  .pw-login-eye:hover { color: var(--text-dark); }
  .row label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
  .row a { color: var(--text-muted); text-decoration: none; cursor: pointer; }
  .row a:hover { color: var(--text-dark); text-decoration: underline; }
  .btn {
    margin-top: 16px; padding: 13px 54px; border: none; cursor: pointer; border-radius: 999px;
    font-size: 13px; font-weight: 700; letter-spacing: 1.5px; color: #fff;
    background: linear-gradient(145deg, var(--luxury-navy-2), var(--luxury-navy));
    box-shadow: 6px 6px 14px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
    transition: transform .25s;
  }
  .btn:hover { transform: translateY(-2px); }
  .btn:active { transform: translateY(0); box-shadow: inset 4px 4px 10px rgba(0,0,0,.4); }
  .btn.ghost { background: transparent; border: 2px solid #fff; box-shadow: none; }
  .btn.ghost:hover { background: rgba(255,255,255,.15); }
  .alert-error, .alert-ok {
    font-size: 12.5px; border-radius: 12px; padding: 10px 14px; margin-bottom: 10px; text-align: left;
    background: var(--bg-base);
  }
  .alert-error { color: #b42318; box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light); }
  .alert-ok { color: #14643a; box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light); }
  /* ===== switch overlay ===== */
  .switch-cnt {
    position: absolute; top: 0; right: 0; width: 50%; height: 100%; z-index: 20; overflow: hidden;
    transition: transform .7s cubic-bezier(.65,0,.35,1);
  }
  .shell.is-switched .switch-cnt { transform: translateX(-100%); }
  .switch-track {
    position: relative; width: 200%; height: 100%; left: -100%; display: flex;
    background:
      linear-gradient(135deg, rgba(27,42,74,.88), rgba(27,42,74,.94)),
      url('/images/login-santri.jpg') center/cover no-repeat,
      linear-gradient(135deg, var(--luxury-navy-2), var(--luxury-navy));
    box-shadow: inset 8px 8px 22px rgba(0,0,0,.35), inset -5px -5px 14px rgba(255,255,255,.1);
    transition: transform .7s cubic-bezier(.65,0,.35,1);
  }
  .shell.is-switched .switch-track { transform: translateX(50%); }
  .switch-panel {
    position: relative; width: 50%; display: flex; flex-direction: column;
    align-items: center; justify-content: center; text-align: center; padding: 0 48px; color: #fff; overflow: hidden;
  }
  .switch-panel h1 { font-size: 30px; margin-bottom: 12px; }
  .switch-panel p { font-size: 13.5px; line-height: 1.7; opacity: .85; margin-bottom: 22px; max-width: 300px; }
  .switch-panel img.logo {
    width: 88px; height: 88px; object-fit: contain; margin-bottom: 16px;
    filter: drop-shadow(0 8px 16px rgba(0,0,0,.45));
  }
  .switch-circle {
    position: absolute; border-radius: 50%;
    background: rgba(255,255,255,.07);
    box-shadow: inset 4px 4px 12px rgba(0,0,0,.25), inset -3px -3px 10px rgba(255,255,255,.12);
    pointer-events: none;
  }
  .switch-panel > *:not(.switch-circle) { position: relative; z-index: 1; }
  .switch-circle.c1 { width: 190px; height: 190px; top: 28px; right: 28px; z-index: 0; animation: drift1 9s ease-in-out infinite; }
  .switch-circle.c2 { width: 130px; height: 130px; bottom: 28px; left: 28px; z-index: 0; animation: drift2 11s ease-in-out infinite; }
  @keyframes drift1 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(-26px, 30px) scale(1.08); }
  }
  @keyframes drift2 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(24px, -26px) scale(1.12); }
  }
  @media (prefers-reduced-motion: reduce) {
    .switch-circle.c1, .switch-circle.c2 { animation: none; }
  }
  /* ===== responsive ===== */
  @media (max-width: 1000px) {
    .a-container, .b-container { padding: 32px; }
    .switch-panel { padding: 0 30px; }
  }
  @media (max-width: 800px) {
    .shell { min-height: 640px; }
    .form h1 { font-size: 23px; }
    .switch-panel h1 { font-size: 24px; }
  }
  @media (max-width: 600px) {
    .shell { flex-direction: column; min-height: 880px; }
    .a-container, .b-container { width: 100%; height: 58%; padding: 28px 24px; }
    .b-container { top: 0; }
    .switch-cnt { right: auto; bottom: 0; top: auto; width: 100%; height: 42%; }
    .shell.is-switched .switch-cnt { transform: none; }
    .switch-track { width: 100%; height: 200%; left: 0; top: -100%; flex-direction: column; }
    .shell.is-switched .switch-track { transform: translateY(50%); }
    .switch-panel { width: 100%; height: 50%; padding: 18px; }
    .switch-panel img.logo { width: 62px; height: 62px; margin-bottom: 10px; }
    .switch-panel h1 { font-size: 21px; margin-bottom: 8px; }
    .switch-panel p { font-size: 12.5px; margin-bottom: 14px; }
  }
</style>
</head>
<body>

<div class="shell" id="shell">

  <!-- A: SIGN IN (form asli login admin) -->
  <div class="a-container">
    <form class="form" method="POST" action="{{ route('login') }}">
      @csrf
      <h1>Login Admin</h1>
      <p class="sub">Masuk untuk mengelola website pondok</p>
      @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
      @endif
      <div class="social"><span><i class="fab fa-facebook-f"></i></span><span><i class="fab fa-linkedin-in"></i></span><span><i class="fab fa-x-twitter"></i></span></div>
      <input class="field" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
      <div class="pw-login"><input class="field" type="password" name="password" id="loginPass" placeholder="Password" required><button type="button" class="pw-login-eye" id="loginEye" aria-label="Tampilkan sandi"><i class="fa-solid fa-eye"></i></button></div>
      <div class="row">
        <label><input type="checkbox" name="remember"> Ingat saya</label>
        <a id="linkForgot">Forgot your password?</a>
      </div>
      @include('partials.turnstile')
      <button class="btn" type="submit">SIGN IN</button>
    </form>
  </div>

  <!-- B: AJUKAN AKSES (masuk inbox Pesan admin) -->
  <div class="b-container">
    <form class="form" method="POST" action="{{ route('kontak.store') }}">
      @csrf
      <h1>Ajukan Akses</h1>
      <p class="sub">Belum punya akun? Kirim permintaan ke administrator</p>
      @if(session('sukses'))
        <div class="alert-ok">{{ session('sukses') }}</div>
      @endif
      <input type="hidden" name="subjek" value="Permintaan Akun Admin">
      <input class="field" type="text" name="nama" placeholder="Name" required>
      <input class="field" type="email" name="email" placeholder="Email" required>
      <input class="field" type="text" name="no_hp" placeholder="No HP/WA">
      <textarea class="field" name="pesan" rows="3" placeholder="Keperluan / jabatan Anda" required></textarea>
      @include('partials.turnstile')
      <button class="btn" type="submit">KIRIM PERMINTAAN</button>
    </form>
  </div>

  <!-- SWITCH OVERLAY -->
  <div class="switch-cnt">
    <div class="switch-track">
      <div class="switch-panel">
        <span class="switch-circle c1"></span><span class="switch-circle c2"></span>
        <img class="logo" src="{{ asset('images/logo-pondok.png') }}" alt="Logo Pondok">
        <h1>Welcome Back!</h1>
        <p>To keep connected please login with your admin account</p>
        <button class="btn ghost" id="btnToSignIn" type="button">SIGN IN</button>
      </div>
      <div class="switch-panel">
        <span class="switch-circle c1"></span><span class="switch-circle c2"></span>
        <img class="logo" src="{{ asset('images/logo-pondok.png') }}" alt="Logo Pondok">
        <h1>Hello, Friend!</h1>
        <p>Enter your details and request access to the admin panel</p>
        <button class="btn ghost" id="btnToRequest" type="button">AJUKAN AKSES</button>
      </div>
    </div>
  </div>

</div>

<script>
  const shell = document.querySelector('#shell');
  function changeForm(toRequest) {
    shell.classList.toggle('is-switched', !!toRequest);
  }
  document.querySelector('#btnToRequest').addEventListener('click', () => changeForm(true));
  document.querySelector('#btnToSignIn').addEventListener('click', () => changeForm(false));
  document.querySelector('#linkForgot').addEventListener('click', () => changeForm(true));
  (function(){
    const eye = document.querySelector('#loginEye'), pass = document.querySelector('#loginPass');
    if(!eye || !pass) return;
    eye.addEventListener('click', () => {
      const show = pass.type === 'password';
      pass.type = show ? 'text' : 'password';
      eye.querySelector('i').className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
      eye.setAttribute('aria-label', show ? 'Sembunyikan sandi' : 'Tampilkan sandi');
    });
  })();
  @if(session('sukses'))
    changeForm(true);
  @endif
</script>
</body>
</html>
