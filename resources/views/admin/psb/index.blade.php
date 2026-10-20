@extends('admin.layout')

@section('title', 'PSB')
@section('crumb', 'Informasi halaman PSB & ringkasan pendaftar')

@section('content')
@if(session('sukses'))
  <div class="alert ok"><i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}</div>
@endif
@if($errors->any())
  <div class="alert err">
    <ul style="margin:0;padding-left:18px">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="toolbar">
  <div style="font-size:13px;color:var(--muted);font-weight:600;max-width:520px">
    Halaman publik:
    <a href="{{ $psbUrl }}" target="_blank" rel="noopener" class="link">{{ $psbUrl }}</a>
    <div style="margin-top:2px">Data pendaftar PSB dikelola di menu <b>PPDB Santri</b>.</div>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn ghost" href="{{ route('admin.pendaftaran.index') }}"><i class="fa-solid fa-users"></i> Lihat Pendaftar</a>
    <a class="btn ghost" href="{{ route('admin.pendaftaran.export') }}"><i class="fa-solid fa-file-csv"></i> Export CSV</a>
    <a class="btn" href="{{ $psbUrl }}" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Halaman PSB</a>
  </div>
</div>

@php
  $statItems = [
      ['label' => 'Total Pendaftar', 'value' => $counts['total'] ?? 0, 'color' => '#0f766e', 'bg' => '#ccfbf1', 'icon' => 'fa-users'],
      ['label' => 'Menunggu', 'value' => $counts['menunggu'] ?? 0, 'color' => '#92400e', 'bg' => '#fef3c7', 'icon' => 'fa-hourglass-half'],
      ['label' => 'Diproses', 'value' => $counts['diproses'] ?? 0, 'color' => '#1e40af', 'bg' => '#dbeafe', 'icon' => 'fa-spinner'],
      ['label' => 'Diterima', 'value' => $counts['diterima'] ?? 0, 'color' => '#15803d', 'bg' => '#dcfce7', 'icon' => 'fa-circle-check'],
      ['label' => 'Ditolak', 'value' => $counts['ditolak'] ?? 0, 'color' => '#991b1b', 'bg' => '#fee2e2', 'icon' => 'fa-circle-xmark'],
      ['label' => 'Lunas', 'value' => $counts['lunas'] ?? 0, 'color' => '#6d28d9', 'bg' => '#ede9fe', 'icon' => 'fa-wallet'],
  ];
  $pillFor = fn (string $status) => match ($status) {
      'diterima', 'lunas' => 'p-green',
      'ditolak' => 'p-red',
      'menunggu' => 'p-gold',
      'diproses' => 'p-blue',
      default => 'p-gray',
  };
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin-bottom:14px">
  @foreach($statItems as $s)
    <div class="card" style="display:flex;align-items:center;gap:12px;padding:14px;margin:0">
      <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:16px">
        <i class="fa-solid {{ $s['icon'] }}"></i>
      </div>
      <div>
        <div style="font-size:20px;font-weight:800;line-height:1">{{ $s['value'] }}</div>
        <div style="font-size:12px;color:var(--muted);font-weight:700">{{ $s['label'] }}</div>
      </div>
    </div>
  @endforeach
</div>

<div class="grid2">
  <div class="panel">
    <div class="panel-head">
      <b>Informasi Halaman PSB</b>
      <span class="pill {{ ($values['psb_status'] ?? '') === 'Ditutup' ? 'p-red' : 'p-green' }}">
        {{ $values['psb_status'] !== '' ? $values['psb_status'] : 'Status belum diisi' }}
      </span>
    </div>

    <form method="POST" action="{{ route('admin.psb.update') }}">
      @csrf
      <div class="form-grid">
        <label class="f">Tahun Ajaran
          <input name="psb_tahun_ajaran" value="{{ old('psb_tahun_ajaran', $values['psb_tahun_ajaran']) }}" maxlength="20" placeholder="2026/2027">
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Tampil di judul dan badge halaman PSB.</small>
        </label>

        <label class="f">Status Pendaftaran
          <input name="psb_status" value="{{ old('psb_status', $values['psb_status']) }}" maxlength="30" list="psbStatusList" placeholder="Dibuka">
          <datalist id="psbStatusList">
            <option value="Dibuka">
            <option value="Segera Dibuka">
            <option value="Ditutup">
          </datalist>
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Boleh teks bebas, mis. "Dibuka (Gelombang 1)".</small>
        </label>

        <label class="f" style="grid-column:1/-1">Gelombang &amp; Jadwal
          <textarea name="psb_gelombang" placeholder="Gelombang 1|1 Juli - 31 Agustus 2026|Kuota terbatas">{{ old('psb_gelombang', $values['psb_gelombang']) }}</textarea>
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Satu baris satu gelombang. Format: <b>Nama|Periode|Catatan</b>. Kosongkan jika jadwal belum diumumkan.</small>
        </label>

        <label class="f" style="grid-column:1/-1">Syarat / Berkas
          <textarea name="psb_syarat">{{ old('psb_syarat', $values['psb_syarat']) }}</textarea>
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Satu baris satu syarat.</small>
        </label>

        <label class="f" style="grid-column:1/-1">Info Biaya
          <textarea name="psb_biaya_info" style="min-height:90px">{{ old('psb_biaya_info', $values['psb_biaya_info']) }}</textarea>
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Tampil di FAQ "Apakah ada biaya pendidikan?".</small>
        </label>

        <label class="f">No. WA Panitia
          <input name="psb_narahubung" value="{{ old('psb_narahubung', $values['psb_narahubung']) }}" maxlength="25" placeholder="081234567890">
          <small style="display:block;color:var(--muted);font-weight:500;margin-top:4px;font-size:11.5px">Kosongkan untuk memakai No. WA Asrama.</small>
        </label>

        <div style="display:flex;align-items:flex-end">
          <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Informasi</button>
        </div>
      </div>
    </form>
  </div>

  <div>
    <div class="panel" style="margin-bottom:14px">
      <div class="panel-head">
        <b>Pendaftar Terbaru</b>
        <a class="link" href="{{ route('admin.pendaftaran.index') }}">Lihat semua</a>
      </div>

      @forelse($recent as $p)
        <a href="{{ route('admin.pendaftaran.show', $p) }}" class="pipe-row" style="text-decoration:none;color:inherit">
          <div class="pipe-ic" style="background:#ccfbf1;color:#0f766e"><i class="fa-solid fa-user"></i></div>
          <div style="flex:1;min-width:0">
            <div style="font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->nama_lengkap }}</div>
            <div style="font-size:11.5px;color:var(--muted)">{{ $p->nomor_pendaftaran }} • {{ $p->jenjang }}</div>
          </div>
          <span class="pill {{ $pillFor((string) $p->status) }}">{{ $p->status }}</span>
        </a>
      @empty
        <p style="color:var(--muted);font-size:13px;margin:0">Belum ada pendaftar.</p>
      @endforelse
    </div>

    <div class="panel">
      <div class="panel-head"><b>Alur Halaman</b></div>
      <div class="pipe-row">
        <div class="pipe-ic" style="background:#dbeafe;color:#1e40af"><i class="fa-solid fa-globe"></i></div>
        <div style="font-size:12.5px">Halaman publik PSB menampilkan info dari form di samping.</div>
      </div>
      <div class="pipe-row">
        <div class="pipe-ic" style="background:#fef3c7;color:#92400e"><i class="fa-solid fa-pen-to-square"></i></div>
        <div style="font-size:12.5px">Tombol daftar mengarah ke formulir PPDB di situs utama.</div>
      </div>
      <div class="pipe-row">
        <div class="pipe-ic" style="background:#dcfce7;color:#15803d"><i class="fa-solid fa-list-check"></i></div>
        <div style="font-size:12.5px">Data pendaftar diverifikasi lewat menu PPDB Santri.</div>
      </div>
    </div>
  </div>
</div>
@endsection
