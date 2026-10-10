@extends('admin.layout')
@section('title','Detail Pendaftar')
@section('crumb',$pendaftaran->nomor_pendaftaran)
@section('content')
<div class="grid g2">
<div class="card">
<b><i class="fa-solid fa-user"></i> Data Santri</b>
<dl class="kv" style="margin-top:12px">
<dt>Nomor</dt><dd>{{ $pendaftaran->nomor_pendaftaran }}</dd>
<dt>Nama</dt><dd>{{ $pendaftaran->nama_lengkap }}</dd>
<dt>NIK</dt><dd>{{ $pendaftaran->nik ?? '-' }}</dd>
<dt>TTL</dt><dd>{{ $pendaftaran->tempat_lahir }}{{ $pendaftaran->tanggal_lahir ? ', '.$pendaftaran->tanggal_lahir->format('d M Y') : '' }}</dd>
<dt>L/P</dt><dd>{{ $pendaftaran->jenis_kelamin==='L'?'Laki-laki':'Perempuan' }}</dd>
<dt>Alamat</dt><dd>{{ $pendaftaran->alamat }}</dd>
<dt>Asal sekolah</dt><dd>{{ $pendaftaran->asal_sekolah ?? '-' }}</dd>
<dt>Jenjang</dt><dd>{{ $pendaftaran->jenjang }}</dd>
<dt>Program</dt><dd>{{ $pendaftaran->program_pilihan ?? '-' }}</dd>
<dt>Catatan</dt><dd>{{ $pendaftaran->catatan ?? '-' }}</dd>
<dt>Tgl pengisian</dt><dd>{{ $pendaftaran->tanggal_pengisian?->format('d M Y') ?? '-' }}</dd>
<dt>Daftar</dt><dd>{{ $pendaftaran->created_at->format('d M Y H:i') }}</dd>
</dl>
</div>
<div class="card">
<b><i class="fa-solid fa-users"></i> Orang Tua / Wali</b>
<dl class="kv" style="margin:12px 0">
<dt>Nama ayah/wali</dt><dd>{{ $pendaftaran->nama_ayah ?? $pendaftaran->nama_wali ?? '-' }}</dd>
<dt>Pekerjaan ayah</dt><dd>{{ $pendaftaran->pekerjaan_ayah ?? '-' }}</dd>
<dt>Nama ibu</dt><dd>{{ $pendaftaran->nama_ibu ?? '-' }}</dd>
<dt>Pekerjaan ibu</dt><dd>{{ $pendaftaran->pekerjaan_ibu ?? '-' }}</dd>
<dt>No. hubungi</dt><dd>{{ $pendaftaran->no_hp ?? $pendaftaran->hp_wali }} <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $pendaftaran->no_hp ?? $pendaftaran->hp_wali) }}" target="_blank" style="font-size:12px">Chat WA →</a></dd>
@if($pendaftaran->nama_wali)<dt>Nama wali (lama)</dt><dd>{{ $pendaftaran->nama_wali }} • {{ $pendaftaran->hp_wali }}</dd>@endif
</dl>
</div>
<div class="card">
<b><i class="fa-solid fa-file"></i> Berkas</b>
<dl class="kv" style="margin:12px 0">
<dt>Ijazah / SKL</dt><dd>@if($pendaftaran->file_ijazah)<a href="{{ asset('storage/'.$pendaftaran->file_ijazah) }}" target="_blank">Lihat berkas →</a>@else - @endif</dd>
<dt>Kartu Keluarga</dt><dd>@if($pendaftaran->file_kk)<a href="{{ asset('storage/'.$pendaftaran->file_kk) }}" target="_blank">Lihat berkas →</a>@else - @endif</dd>
<dt>KIP</dt><dd>@if($pendaftaran->file_kip)<a href="{{ asset('storage/'.$pendaftaran->file_kip) }}" target="_blank">Lihat berkas →</a>@else - @endif</dd>
</dl>
</div>
<div class="card">
<b><i class="fa-solid fa-flag"></i> Status</b>
<dl class="kv" style="margin:12px 0">
<dt>Status</dt><dd><span class="pill {{ $pendaftaran->status==='diterima'?'p-green':($pendaftaran->status==='ditolak'?'p-red':'p-gold') }}">{{ $pendaftaran->status }}</span></dd>
</dl>
<form method="POST" action="{{ route('admin.pendaftaran.status',$pendaftaran) }}">@csrf @method('PATCH')
<label class="f">Ubah status<select name="status">@foreach(['menunggu'=>'Menunggu','diproses'=>'Diproses / verifikasi','diterima'=>'Diterima','ditolak'=>'Ditolak','lunas'=>'Lunas / daftar ulang'] as $k=>$l)<option value="{{ $k }}" @selected($pendaftaran->status===$k)>{{ $l }}</option>@endforeach</select></label>
<button class="btn green"><i class="fa-solid fa-check"></i> Update Status</button>
</form>
<div style="margin-top:12px;display:flex;gap:8px"><a class="btn ghost" href="{{ route('admin.pendaftaran.index') }}">← Kembali</a></div>
</div>
</div>
@endsection
