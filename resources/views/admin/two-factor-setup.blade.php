@extends('admin.layout')
@section('title','Keamanan Akun')
@section('crumb','Verifikasi 2 langkah & kode pemulihan')
@section('content')

<div class="card" style="max-width:760px">
  <div class="panel-head">
    <b><i class="fa-solid fa-shield-halved" style="color:var(--primary)"></i> Verifikasi 2 Langkah (2FA)</b>
    @if($enabled)
      <span class="pill p-green"><i class="fa-solid fa-circle-check"></i> Aktif</span>
    @else
      <span class="pill p-red"><i class="fa-solid fa-circle-xmark"></i> Nonaktif</span>
    @endif
  </div>

  @if(! $enabled)
    <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin-bottom:16px">
      Dengan 2FA, login tetap memakai email &amp; password, lalu ditambah 6 digit kode dari aplikasi authenticator
      (Google Authenticator, Authy, Microsoft Authenticator). Ini melindungi panel admin walau password bocor.
    </p>

    <div style="display:flex;gap:22px;flex-wrap:wrap;align-items:flex-start">
      <div style="background:#fff;padding:12px;border-radius:14px;border:1px solid var(--line)">
        <div id="qrcode"></div>
      </div>
      <div style="flex:1;min-width:240px">
        <p style="font-size:12.5px;color:var(--muted);margin-bottom:6px">1. Pindai QR di samping, atau masukkan kunci manual:</p>
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:14px">
          <code id="secretBox" style="flex:1;word-break:break-all;background:var(--input);border:1px solid var(--line);border-radius:10px;padding:10px 12px;font-size:13px;font-weight:700">{{ $secret }}</code>
          <button type="button" class="icon-btn" id="copySecret" title="Salin kunci"><i class="fa-regular fa-copy"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.two-factor.enable') }}">
          @csrf
          <label class="f">2. Masukkan 6 digit dari aplikasi
            <input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000" required>
          </label>
          <button class="btn"><i class="fa-solid fa-check"></i> Aktifkan 2FA</button>
        </form>
      </div>
    </div>
  @else
    <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin-bottom:16px">
      2FA aktif sejak {{ optional(auth()->user()->two_factor_confirmed_at)->translatedFormat('d F Y, H:i') }}. Setiap login memerlukan kode dari authenticator.
    </p>

    @if(session('recovery_codes_plain'))
      <div class="alert ok" style="display:block">
        <b>Simpan kode pemulihan ini sekarang.</b> Kode hanya ditampilkan sekali.
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:10px;font-family:monospace;font-size:14px">
          @foreach(session('recovery_codes_plain') as $c)
            <div style="background:#fff;border:1px solid var(--line);border-radius:8px;padding:6px 10px">{{ $c }}</div>
          @endforeach
        </div>
        <button type="button" class="btn light" style="margin-top:12px" id="copyCodes"><i class="fa-regular fa-copy"></i> Salin semua</button>
      </div>
    @endif

    <div class="grid g2" style="margin-top:6px">
      <div class="card" style="margin:0">
        <b style="font-size:13.5px"><i class="fa-solid fa-key" style="color:var(--primary)"></i> Kode Pemulihan</b>
        <p style="font-size:12.5px;color:var(--muted);line-height:1.6;margin:8px 0 12px">
          Tersisa {{ count($recoveryCodes) }} kode. Buat ulang jika sudah habis atau mencurigakan.
        </p>
        <form method="POST" action="{{ route('admin.two-factor.recovery') }}">
          @csrf
          <label class="f">Password saat ini<input type="password" name="password" required></label>
          <button class="btn light"><i class="fa-solid fa-rotate"></i> Buat Ulang Kode</button>
        </form>
      </div>

      <div class="card" style="margin:0;border-color:#fecaca">
        <b style="font-size:13.5px;color:var(--red)"><i class="fa-solid fa-triangle-exclamation"></i> Nonaktifkan 2FA</b>
        <p style="font-size:12.5px;color:var(--muted);line-height:1.6;margin:8px 0 12px">
          Menonaktifkan 2FA membuat akun hanya terlindungi password.
        </p>
        <form method="POST" action="{{ route('admin.two-factor.disable') }}" onsubmit="return confirm('Yakin menonaktifkan 2FA?')">
          @csrf
          <label class="f">Password saat ini<input type="password" name="password" required></label>
          <button class="btn red"><i class="fa-solid fa-power-off"></i> Nonaktifkan</button>
        </form>
      </div>
    </div>
  @endif
</div>

<script src="{{ asset('vendor/qrcode.min.js') }}"></script>
<script>
(function(){
  var el = document.getElementById('qrcode');
  if (el && typeof QRCode !== 'undefined') {
    new QRCode(el, { text: @json($uri), width: 180, height: 180, correctLevel: QRCode.CorrectLevel.M });
  }
  var copyBtn = document.getElementById('copySecret');
  if (copyBtn) copyBtn.addEventListener('click', function(){
    var t = document.getElementById('secretBox').innerText.trim();
    navigator.clipboard.writeText(t).then(function(){
      copyBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
      setTimeout(function(){ copyBtn.innerHTML = '<i class="fa-regular fa-copy"></i>'; }, 1500);
    });
  });
  var copyCodes = document.getElementById('copyCodes');
  if (copyCodes) copyCodes.addEventListener('click', function(){
    var codes = Array.prototype.map.call(document.querySelectorAll('.alert.ok div div'), function(d){ return d.innerText.trim(); });
    navigator.clipboard.writeText(codes.join('\n'));
    copyCodes.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
  });
})();
</script>
@endsection
