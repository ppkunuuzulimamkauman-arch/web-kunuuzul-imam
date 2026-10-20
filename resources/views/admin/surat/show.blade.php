@extends('admin.layout')

@section('title', 'Detail Permohonan Surat')
@section('crumb', $permohonan->kode)

@section('content')
<div class="grid g2">
  <div class="card">
    <b><i class="fa-solid fa-file-lines"></i> Data Permohonan</b>
    <dl class="kv" style="margin-top:12px">
      <dt>Kode</dt><dd style="font-family:ui-monospace,monospace">{{ $permohonan->kode }}</dd>
      <dt>Jenis surat</dt><dd>{{ $permohonan->jenisLabel() }}</dd>
      <dt>Status</dt><dd><span class="pill {{ $permohonan->statusPillClass() }}">{{ $permohonan->statusLabel() }}</span></dd>
      <dt>Nomor surat</dt><dd>{{ $permohonan->nomor_surat ?: '-' }}</dd>
      <dt>Diajukan</dt><dd>{{ $permohonan->created_at?->locale('id')->translatedFormat('d F Y, H:i') }}</dd>
      <dt>Selesai</dt><dd>{{ $permohonan->selesai_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }}</dd>
    </dl>

    <div style="border-top:1px solid var(--line);margin:14px 0;padding-top:14px"></div>

    <b><i class="fa-solid fa-user"></i> Data Pemohon</b>
    <dl class="kv" style="margin-top:12px">
      <dt>Nama</dt><dd>{{ $permohonan->nama }}</dd>
      <dt>NIK</dt><dd>{{ $permohonan->nik ?: '-' }}</dd>
      <dt>No. HP / WA</dt>
      <dd>
        {{ $permohonan->no_hp }}
        @if($permohonan->waUrl())
          <a href="{{ $permohonan->waUrl() }}" target="_blank" rel="noopener" style="font-size:12px;margin-left:6px">
            Chat WA <i class="fa-brands fa-whatsapp"></i>
          </a>
        @endif
      </dd>
      <dt>Email</dt><dd>{{ $permohonan->email ?: '-' }}</dd>
      <dt>Lembaga / unit</dt><dd>{{ $permohonan->lembaga ?: '-' }}</dd>
      <dt>Alamat</dt><dd>{{ $permohonan->alamat ?: '-' }}</dd>
    </dl>

    <div style="border-top:1px solid var(--line);margin:14px 0;padding-top:14px"></div>

    <b><i class="fa-solid fa-circle-info"></i> Detail Keperluan</b>
    <dl class="kv" style="margin-top:12px">
      @if($permohonan->jenis === 'izin_pulang')
        <dt>Tanggal mulai</dt><dd>{{ $permohonan->tanggal_mulai?->locale('id')->translatedFormat('d F Y') ?? '-' }}</dd>
        <dt>Tanggal selesai</dt><dd>{{ $permohonan->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? '-' }}</dd>
        <dt>Tujuan</dt><dd>{{ $permohonan->tujuan ?: '-' }}</dd>
      @endif
      @if(in_array($permohonan->jenis, ['rekomendasi', 'pengantar'], true))
        <dt>Ditujukan</dt><dd>{{ $permohonan->ditujukan ?: '-' }}</dd>
      @endif
      @if($permohonan->jenis === 'keterangan_lain')
        <dt>Jenis surat</dt><dd>{{ $permohonan->jenis_lainnya ?: '-' }}</dd>
      @endif
      <dt>Keperluan</dt><dd style="white-space:pre-line">{{ $permohonan->keperluan }}</dd>
      <dt>Lampiran</dt>
      <dd>
        @if($permohonan->lampiranUrl())
          <a href="{{ $permohonan->lampiranUrl() }}" target="_blank" rel="noopener">Lihat lampiran <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i></a>
        @else
          -
        @endif
      </dd>
    </dl>
  </div>

  <div>
    <div class="card">
      <b><i class="fa-solid fa-pen-to-square"></i> Proses Permohonan</b>
      <form method="POST" action="{{ route('admin.surat.update', $permohonan) }}" enctype="multipart/form-data" style="margin-top:12px">
        @csrf
        @method('PATCH')

        <label class="f">Status
          <select name="status">
            @foreach($statusOptions as $key => $label)
              <option value="{{ $key }}" @selected($permohonan->status === $key)>{{ $label }}</option>
            @endforeach
          </select>
        </label>

        <label class="f">Nomor surat
          <input name="nomor_surat" value="{{ old('nomor_surat', $permohonan->nomor_surat) }}" placeholder="Contoh: 042/PPS-KIK/X/2026">
        </label>

        <label class="f">Catatan untuk pemohon
          <textarea name="catatan_admin" rows="4" placeholder="Contoh: Surat sudah selesai, silakan diambil di kantor pondok.">{{ old('catatan_admin', $permohonan->catatan_admin) }}</textarea>
        </label>

        <label class="f">Berkas surat jadi (PDF / JPG / PNG)
          <input type="file" name="file_hasil" accept=".pdf,.jpg,.jpeg,.png">
          <small style="display:block;color:var(--muted);font-weight:600;margin-top:6px">Maksimal 8 MB. Kosongkan bila tidak diganti.</small>
        </label>

        @if($permohonan->fileHasilUrl())
          <p style="font-size:12.5px;margin-bottom:12px">
            <a href="{{ $permohonan->fileHasilUrl() }}" target="_blank" rel="noopener">
              <i class="fa-solid fa-file-arrow-down"></i> Lihat berkas surat saat ini
            </a>
          </p>
        @endif

        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <button class="btn green"><i class="fa-solid fa-check"></i> Simpan Perubahan</button>
          <a class="btn ghost" href="{{ route('admin.surat.index') }}">← Kembali</a>
        </div>
      </form>
    </div>

    <div class="card">
      <b><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</b>
      <dl class="kv" style="margin-top:12px">
        <dt>Dibuat</dt><dd>{{ $permohonan->created_at?->locale('id')->translatedFormat('d F Y, H:i') }}</dd>
        <dt>Terakhir diubah</dt><dd>{{ $permohonan->updated_at?->locale('id')->translatedFormat('d F Y, H:i') }}</dd>
        <dt>Kode lacak</dt><dd style="font-family:ui-monospace,monospace">{{ $permohonan->kode }}</dd>
      </dl>
      <p style="font-size:12px;color:var(--muted);margin-top:10px">
        Pemohon dapat mengecek status melalui halaman Cek Status dengan kode di atas.
      </p>
    </div>

    <div class="card" style="border-color:#fecaca">
      <b style="color:#991b1b"><i class="fa-solid fa-triangle-exclamation"></i> Hapus Permohonan</b>
      <p style="font-size:12.5px;color:var(--muted);margin:8px 0 12px">
        Menghapus permohonan juga menghapus lampiran dan berkas surat yang tersimpan.
      </p>
      <form method="POST" action="{{ route('admin.surat.destroy', $permohonan) }}"
            onsubmit="return confirm('Hapus permohonan {{ $permohonan->kode }} secara permanen?')">
        @csrf
        @method('DELETE')
        <button class="btn red"><i class="fa-solid fa-trash"></i> Hapus</button>
      </form>
    </div>
  </div>
</div>
@endsection
