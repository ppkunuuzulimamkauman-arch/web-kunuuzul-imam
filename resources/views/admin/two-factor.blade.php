<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi 2 Langkah — PP Kunuuzul Imam Kauman</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins','Segoe UI',Tahoma,sans-serif}
  body{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;background:linear-gradient(135deg,#ece7db,#e4e9f2)}
  .card{width:100%;max-width:400px;background:#e4e9f2;border-radius:24px;padding:36px 32px;text-align:center;box-shadow:12px 12px 24px #b8bfd0,-12px -12px 24px #fff}
  .ic{width:64px;height:64px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;background:linear-gradient(145deg,#2c3f66,#1b2a4a);box-shadow:6px 6px 14px #b8bfd0,-4px -4px 10px #fff}
  h1{font-size:21px;color:#1b2a4a;margin-bottom:8px}
  p.sub{font-size:12.5px;color:#8a93a8;margin-bottom:20px;line-height:1.6}
  .field{width:100%;border:none;outline:none;padding:14px 18px;border-radius:14px;font-size:20px;font-weight:600;letter-spacing:6px;text-align:center;color:#1b2a4a;background:#e4e9f2;box-shadow:inset 5px 5px 12px #b8bfd0,inset -5px -5px 12px #fff}
  .field:focus{background:#f5f7fc;box-shadow:inset 3px 3px 8px #b8bfd0,inset -3px -3px 8px #fff,0 0 0 3px rgba(27,42,74,.16)}
  .btn{margin-top:18px;width:100%;padding:13px;border:none;cursor:pointer;border-radius:999px;font-size:13px;font-weight:700;letter-spacing:1.5px;color:#fff;background:linear-gradient(145deg,#2c3f66,#1b2a4a);box-shadow:6px 6px 14px #b8bfd0,-4px -4px 10px #fff;transition:transform .25s}
  .btn:hover{transform:translateY(-2px)}
  .alert-error{font-size:12.5px;border-radius:12px;padding:10px 14px;margin-bottom:14px;text-align:left;color:#b42318;background:#e4e9f2;box-shadow:inset 4px 4px 10px #b8bfd0,inset -4px -4px 10px #fff}
  .back{display:inline-block;margin-top:16px;font-size:12.5px;color:#8a93a8;text-decoration:none}
  .back:hover{color:#1b2a4a;text-decoration:underline}
  .hint{margin-top:14px;font-size:11.5px;color:#8a93a8;line-height:1.6}
</style>
</head>
<body>
<div class="card">
  <div class="ic"><i class="fa-solid fa-shield-halved"></i></div>
  <h1>Verifikasi 2 Langkah</h1>
  <p class="sub">Masukkan 6 digit kode dari aplikasi authenticator Anda, atau salah satu kode pemulihan.</p>
  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('admin.two-factor.verify') }}">
    @csrf
    <input class="field" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="9" placeholder="000000" required autofocus>
    <button class="btn" type="submit">VERIFIKASI</button>
  </form>
  <a class="back" href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke login</a>
  <div class="hint">Kehilangan akses authenticator? Gunakan kode pemulihan yang Anda simpan saat mengaktifkan 2FA.</div>
</div>
</body>
</html>
