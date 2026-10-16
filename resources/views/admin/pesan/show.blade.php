@extends('admin.layout')
@section('title','Isi Pesan')
@section('content')
<div class="card" style="max-width:760px">
<p style="font-size:12px;color:var(--muted)">{{ $pesan->created_at->format('d M Y H:i') }} • {{ $pesan->email }} • {{ $pesan->no_hp }}</p>
<h2 style="margin:6px 0">{{ $pesan->subjek ?? '(tanpa subjek)' }}</h2>
<p style="font-size:13px;color:var(--muted)">Dari: <b style="color:#243044">{{ $pesan->nama }}</b>
@if($pesan->dijawab_at) <span class="pill p-green" style="margin-left:8px">sudah dijawab {{ $pesan->dijawab_at->diffForHumans() }}</span>
@else <span class="pill p-red" style="margin-left:8px">belum dijawab</span> @endif
@if($pesan->berita_id) <span class="pill p-blue" style="margin-left:4px">dipublish</span> @endif
</p>
<div style="background:#f8fafc;border:1px solid var(--line);border-radius:12px;padding:16px;margin:14px 0;white-space:pre-line;line-height:1.7">{{ $pesan->pesan }}</div>
<div style="display:flex;gap:8px;flex-wrap:wrap">
@if($pesan->email)<a class="btn light" href="mailto:{{ $pesan->email }}?subject=Re: {{ $pesan->subjek }}"><i class="fa-solid fa-reply"></i> Balas Email</a>@endif
@if($pesan->no_hp)<a class="btn green" id="waBtn" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pesan->no_hp) }}?text={{ urlencode('Assalamu\'alaikum '.$pesan->nama.', terkait: '.$pesan->pesan."\n\nJawaban: ".($pesan->jawaban ?? '')) }}" target="_blank"><i class="fa-brands fa-whatsapp"></i> Balas WA</a>@endif
<a class="btn ghost" href="{{ route('admin.pesan.index') }}">← Inbox</a>
@if($pesan->berita_id && $pesan->berita)<a class="btn light" href="{{ route('berita.show', $pesan->berita->slug) }}" target="_blank"><i class="fa-solid fa-eye"></i> Lihat Publish</a>@endif
</div>
</div>

<div class="card" style="max-width:760px;margin-top:12px">
<h3 style="font-size:15px">Jawab Konsultasi</h3>
<p style="font-size:12.5px;color:var(--muted);margin:4px 0 10px">Tulis jawaban di sini. Centang publish bila ingin tampil sebagai Berita / Artikel / dll. Tombol Balas WA di atas otomatis membawa jawaban ini.</p>
@if(session('sukses'))<div class="alert-ok" style="font-size:12.5px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;border-radius:10px;padding:10px 14px;margin-bottom:10px">{{ session('sukses') }}</div>@endif
<form method="POST" action="{{ route('admin.pesan.jawab', $pesan) }}" enctype="multipart/form-data">
@csrf
<label class="f" style="display:block">Jawaban<textarea id="jawabanField" name="jawaban" required style="min-height:140px;width:100%;border:1px solid var(--line);border-radius:10px;padding:12px" placeholder="Tulis jawaban ustadz / admin...">{{ old('jawaban', $pesan->jawaban) }}</textarea></label>
<div style="background:#f8fafc;border:1px dashed var(--line);border-radius:10px;padding:12px;margin-top:10px">
<p style="font-size:13px;font-weight:700">Publish seperti buat Berita / Artikel</p>
<label class="f" style="display:block;margin-top:8px">Judul publish<input name="judul" value="{{ old('judul', $pesan->berita->judul ?? preg_replace('/^\[Konsultasi\]\s*/i','',$pesan->subjek ?? '')) }}" style="width:100%;border:1px solid var(--line);border-radius:10px;padding:10px" placeholder="cth: Hukum Puasa Syawal"></label>
<label class="f" style="display:block;margin-top:8px">Excerpt / Ringkasan<textarea name="excerpt" style="width:100%;border:1px solid var(--line);border-radius:10px;padding:10px;min-height:60px" placeholder="1-2 kalimat tampil di kartu berita">{{ old('excerpt', $pesan->berita->excerpt ?? \Str::limit($pesan->pesan,160)) }}</textarea></label>
<div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;margin-top:8px">
<label class="f">Kategori<select name="kategori" style="border:1px solid var(--line);border-radius:10px;padding:10px">@foreach(array_merge($kategoris, ['acara'=>'Acara','ppdb'=>'PPDB','prestasi'=>'Prestasi','maklumat'=>'Maklumat Pondok','bahtsul'=>'Bahtsul Masail']) as $k => $l)<option value="{{ $k }}" @selected(old('kategori', $pesan->berita->kategori ?? 'artikel')===$k)>{{ $l }}</option>@endforeach</select></label>
<label class="f">Gambar sampul<input type="file" name="gambar" accept="image/*" style="border:1px solid var(--line);border-radius:10px;padding:8px">@if(!empty($pesan->berita->gambar))<small style="color:var(--muted)">Ada: {{ $pesan->berita->gambar }}</small>@endif</label>
<label class="check" style="display:flex;gap:6px;align-items:center;font-size:13px"><input type="checkbox" name="publish" value="1" @checked(old('publish', !$pesan->berita_id))> Publish jawaban</label>
</div>
</div>
<p style="font-size:12px;color:var(--muted);margin-top:6px">Tidak dicentang = hanya tersimpan di inbox (tidak tampil di website). Dicentang = dibuatkan/diupdate halaman Berita/Artikel dengan judul, gambar & ringkasan di atas.</p>
<div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
<button class="btn"><i class="fa-solid fa-floppy-disk"></i> Simpan Jawaban</button>
<a class="btn ghost" href="{{ route('admin.berita.index') }}">Kelola Berita</a>
</div>
</form>
</div>

@if($pesan->no_hp)
<script>
(function(){
  var field = document.getElementById('jawabanField');
  var wa = document.getElementById('waBtn');
  if(!field || !wa) return;
  var base = 'https://wa.me/{{ preg_replace('/[^0-9]/','',$pesan->no_hp) }}?text=';
  var nama = @json($pesan->nama);
  var tanya = @json($pesan->pesan);
  function sync(){
    var teks = "Assalamu'alaikum " + nama + ", terkait: " + tanya + "\n\nJawaban: " + (field.value || '');
    wa.href = base + encodeURIComponent(teks);
  }
  field.addEventListener('input', sync);
})();
</script>
@endif
@endsection
