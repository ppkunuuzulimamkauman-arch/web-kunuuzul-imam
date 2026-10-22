@extends('admin.layout')

@section('title', 'Dashboard SIAKAD')
@section('crumb', 'Ringkasan data santri dan akademik')

@section('content')
@php
  $statItems = [
      ['label' => 'Santri Aktif', 'value' => $stats['santri_aktif'], 'color' => '#166534', 'bg' => '#dcfce7', 'icon' => 'fa-user-graduate'],
      ['label' => 'Santri Lulus', 'value' => $stats['santri_lulus'], 'color' => '#1e40af', 'bg' => '#dbeafe', 'icon' => 'fa-award'],
      ['label' => 'Kelas Aktif', 'value' => $stats['kelas_aktif'], 'color' => '#92400e', 'bg' => '#fef3c7', 'icon' => 'fa-school'],
      ['label' => 'PSB Belum Terhubung', 'value' => $stats['pendaftar_belum_terhubung'], 'color' => '#991b1b', 'bg' => '#fee2e2', 'icon' => 'fa-link-slash'],
      ['label' => 'Alumni Belum Terhubung', 'value' => $stats['alumni_belum_terhubung'], 'color' => '#475569', 'bg' => '#f1f5f9', 'icon' => 'fa-users'],
  ];
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:14px">
  @foreach($statItems as $s)
    <div class="card" style="display:flex;align-items:center;gap:12px;padding:14px;margin:0">
      <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:16px">
        <i class="fa-solid {{ $s['icon'] }}"></i>
      </div>
      <div>
        <div style="font-size:22px;font-weight:800;line-height:1">{{ $s['value'] }}</div>
        <div style="font-size:11px;color:var(--muted);font-weight:700">{{ $s['label'] }}</div>
      </div>
    </div>
  @endforeach
</div>

<div class="card" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between">
  <div>
    <b style="font-size:14px">Tahun ajaran aktif:</b>
    <span class="pill {{ $tahunAktif ? 'p-green' : 'p-gray' }}">{{ $tahunAktif?->nama ?? 'Belum diatur' }}</span>
    @if($tahunAktif && $tahunAktif->mulai)
      <small style="color:var(--muted)">{{ $tahunAktif->mulai->format('d/m/Y') }} — {{ $tahunAktif->selesai?->format('d/m/Y') ?? '...' }}</small>
    @endif
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn ghost" href="{{ route('admin.siakad.tahun-ajaran.index') }}"><i class="fa-solid fa-calendar-days"></i> Atur Tahun Ajaran</a>
    <a class="btn" href="{{ route('admin.siakad.santri.create') }}"><i class="fa-solid fa-plus"></i> Tambah Santri</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);gap:14px" class="siakad-dashboard-grid">
  <div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
      <b style="font-size:14px">Santri Terbaru</b>
      <a href="{{ route('admin.siakad.santri.index') }}" style="font-size:12px;color:var(--primary);font-weight:700;text-decoration:none">Lihat semua</a>
    </div>
    @if($santriTerbaru->isEmpty())
      <p style="color:var(--muted);font-size:14px">Belum ada data santri. Data akan otomatis dibuat saat pendaftar PSB diterima.</p>
    @else
      <div style="overflow-x:auto">
        <table>
          <thead><tr><th>Nama</th><th>NIS</th><th>Status</th><th>Masuk</th></tr></thead>
          <tbody>
            @foreach($santriTerbaru as $santri)
              <tr>
                <td><b>{{ $santri->nama }}</b><br><small style="color:var(--muted)">{{ $santri->asal_sekolah ?: 'Asal sekolah belum diisi' }}</small></td>
                <td>{{ $santri->nis ?: '-' }}</td>
                <td><span class="pill {{ $santri->statusPillClass() }}">{{ $santri->statusLabel() }}</span></td>
                <td>{{ $santri->tanggal_masuk?->format('d/m/Y') ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
      <b style="font-size:14px">Kelas Aktif</b>
      <a href="{{ route('admin.siakad.kelas.index') }}" style="font-size:12px;color:var(--primary);font-weight:700;text-decoration:none">Kelola</a>
    </div>
    @if($kelasAktif->isEmpty())
      <p style="color:var(--muted);font-size:14px">Belum ada kelas aktif.</p>
    @else
      @foreach($kelasAktif as $kelas)
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;padding:9px 0;border-bottom:1px solid var(--rowline)">
          <div>
            <b style="font-size:13px">{{ $kelas->nama }}</b>
            <div style="font-size:11px;color:var(--muted)">{{ $kelas->tahunAjaran?->nama ?? '-' }} @if($kelas->jenjang) · {{ $kelas->jenjang }} @endif</div>
          </div>
          <span class="pill p-gray">{{ $kelas->santris_count }} santri</span>
        </div>
      @endforeach
    @endif
  </div>
</div>

<style>@media(max-width:900px){.siakad-dashboard-grid{grid-template-columns:1fr!important}}</style>
@endsection
