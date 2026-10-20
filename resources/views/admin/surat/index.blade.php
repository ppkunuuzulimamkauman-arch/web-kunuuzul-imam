@extends('admin.layout')

@section('title', 'Permohonan Surat')
@section('crumb', 'Kelola permohonan surat dari masyarakat & santri')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.surat.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <div class="searchbar" style="min-width:260px;background:var(--card)">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau no. HP...">
    </div>
    <select name="jenis" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua jenis</option>
      @foreach($jenisOptions as $key => $label)
        <option value="{{ $key }}" @selected(request('jenis') === $key)>{{ $label }}</option>
      @endforeach
    </select>
    <select name="status" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua status</option>
      @foreach(\App\Models\SuratPermohonan::statusOptions() as $key => $label)
        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn ghost"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
  </form>

  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn ghost" href="{{ route('admin.surat.export', array_filter(request()->only('q', 'status', 'jenis'))) }}">
      <i class="fa-solid fa-file-csv"></i> Export CSV
    </a>
  </div>
</div>

@php
  $statItems = [
      ['label' => 'Total', 'value' => $stats['total'], 'color' => '#0f766e', 'bg' => '#ccfbf1', 'icon' => 'fa-file-lines'],
      ['label' => 'Menunggu', 'value' => $stats['menunggu'], 'color' => '#92400e', 'bg' => '#fef3c7', 'icon' => 'fa-hourglass-half'],
      ['label' => 'Diproses', 'value' => $stats['diproses'], 'color' => '#1e40af', 'bg' => '#dbeafe', 'icon' => 'fa-gears'],
      ['label' => 'Selesai', 'value' => $stats['selesai'], 'color' => '#15803d', 'bg' => '#dcfce7', 'icon' => 'fa-circle-check'],
      ['label' => 'Ditolak', 'value' => $stats['ditolak'], 'color' => '#991b1b', 'bg' => '#fee2e2', 'icon' => 'fa-circle-xmark'],
  ];
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

<div style="display:flex;gap:6px;flex-wrap:wrap;margin:0 0 12px">
  @foreach(['' => 'Semua'] + \App\Models\SuratPermohonan::statusOptions() as $key => $label)
    <a href="{{ route('admin.surat.index', array_filter(['status' => $key, 'q' => request('q'), 'jenis' => request('jenis')])) }}"
       class="pill {{ (string) request('status', '') === (string) $key ? 'p-green' : 'p-gray' }}"
       style="text-decoration:none">{{ $label }}</a>
  @endforeach
</div>

<div class="card">
  @if($permohonans->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada permohonan surat.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Kode</th>
            <th>Pemohon</th>
            <th>Jenis Surat</th>
            <th>Status</th>
            <th>Diajukan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($permohonans as $permohonan)
            <tr>
              <td style="font-weight:800;white-space:nowrap;font-family:ui-monospace,monospace">{{ $permohonan->kode }}</td>
              <td>
                <b>{{ $permohonan->nama }}</b>
                <br><small style="color:var(--muted)">{{ $permohonan->no_hp }}{{ $permohonan->lembaga ? ' • '.$permohonan->lembaga : '' }}</small>
              </td>
              <td>
                {{ $permohonan->jenisLabel() }}
                @if($permohonan->nomor_surat)
                  <br><small style="color:var(--muted)">No: {{ $permohonan->nomor_surat }}</small>
                @endif
              </td>
              <td><span class="pill {{ $permohonan->statusPillClass() }}">{{ $permohonan->statusLabel() }}</span></td>
              <td style="white-space:nowrap;font-size:12.5px;color:var(--muted)">
                {{ $permohonan->created_at?->format('d M Y') }}
                <br>{{ $permohonan->created_at?->format('H:i') }}
              </td>
              <td>
                <div class="actions">
                  <a class="icon-btn" href="{{ route('admin.surat.show', $permohonan) }}" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.surat.destroy', $permohonan) }}"
                        onsubmit="return confirm('Hapus permohonan {{ $permohonan->kode }}?')">
                    @csrf
                    @method('DELETE')
                    <button class="icon-btn danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pagination">{{ $permohonans->links('vendor.pagination.admin') }}</div>
  @endif
</div>
@endsection
