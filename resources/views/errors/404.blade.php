<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Halaman Tidak Ditemukan — PP Kunuuzul Imam Kauman</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#fafaf9;color:#44403c;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{max-width:520px;width:100%;text-align:center;background:#fff;border:1px solid #f5f5f4;border-radius:24px;padding:48px 32px;box-shadow:0 10px 40px rgba(6,78,59,.08)}
.card img{height:72px;width:auto;margin:0 auto}
.code{font-size:72px;font-weight:800;color:#064e3b;line-height:1;margin-top:16px}
.code span{color:#fbbf24}
h1{font-size:20px;color:#1c1917;margin-top:8px}
p{font-size:14px;color:#78716c;margin-top:10px;line-height:1.7}
.btns{display:flex;gap:12px;justify-content:center;margin-top:24px;flex-wrap:wrap}
a.btn{display:inline-block;font-size:13px;font-weight:800;letter-spacing:.08em;padding:13px 26px;border-radius:9999px;text-decoration:none;transition:transform .2s ease}
a.primary{background:#022c22;color:#fff}
a.primary:hover{background:#065f46;transform:translateY(-2px)}
a.ghost{border:1.5px solid #e7e5e4;color:#44403c}
a.ghost:hover{border-color:#022c22;color:#022c22}
.arab{font-family:Amiri,serif;color:#b45309;font-size:22px;margin-top:20px}
</style>
</head>
<body>
<div class="card">
  <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman">
  <div class="code">4<span>0</span>4</div>
  <h1>Halaman Tidak Ditemukan</h1>
  <p>Maaf, halaman yang Anda cari sudah dipindah, dihapus, atau alamatnya salah ketik.</p>
  <div class="btns">
    <a class="btn primary" href="{{ route('home') }}">← BERANDA</a>
    <a class="btn ghost" href="{{ route('ppdb.form') }}">DAFTAR PSB</a>
  </div>
  <div class="arab">﴿ وَقُل رَّبِّ زِدْنِي عِلْمًا ﴾</div>
</div>
</body>
</html>
