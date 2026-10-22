@extends('admin.layout')

@section('title', 'Data Santri')
@section('crumb', 'Identitas induk yang menghubungkan PSB, akademik, alumni, dan surat')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.siakad.santri.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <div class="searchbar" style="min-width:280px;background:var(--card)">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input name="q" value="{{ $q }}" placeholder="Cari nama, NIS, NIK, atau asal sekolah...">
    </div>
    <select name="status" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua status</option>
      @foreach(\App\Models\Santri::statusOptions() as $value => $label)
        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn ghost"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
  </form>

  <a class="btn" href="{{ route('admin.siakad.santri.create') }}"><i class="fa-solid fa-plus"></i> Tambah Santri</a>
</div>

<div style="display:flex;gap:6px;flex-wrap:wrap;margin:0 0 12px">
  @foreach(['' => 'Semua'] + \App\Models\Santri::statusOptions() as $key => $label)
    <a href="{{ route('admin.siakad.santri.index', array_filter(['status' => $key, 'q' => $q])) }}"
       class="pill {{ $status === (string) $key ? 'p-green' : 'p-gray' }}"
       style="text-decoration:none">{{ $label }} ({{ $counts[$key] ?? 0 }})</a>
  @endforeach
</div>

<div class="card">
  @if($santris->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada data santri yang cocok. Data santri otomatis dibuat ketika pendaftar PSB berstatus diterima/lunas.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NIS / NIK</th>
            <th>JK</th>
            <th>Asal Sekolah</th>
            <th>Wali</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($santris as $santri)
            <tr>
              <td>
                <b>{{ $santri->nama }}</b>
                <br><small style="color:var(--muted)">{{ $santri->tempat_lahir ?: '-' }}{{ $santri->tanggal_lahir ? ', '.$santri->tanggal_lahir->format('d/m/Y') : '' }}</small>
              </td>
              <td>
                {{ $santri->nis ?: '-' }}
                @if($santri->nik)
                  <br><small style="color:var(--muted)">NIK: {{ $santri->nik }}</small>
                @endif
              </td>
              <td>{{ $santri->jenis_kelamin ?: '-' }}</td>
              <td>{{ $santri->asal_sekolah ?: '-' }}</td>
              <td>
                {{ $santri->nama_wali ?: '-' }}
                @if($santri->hp_wali)<br><small style="color:var(--muted)">{{ $santri->hp_wali }}</small>@endif
              </td>
              <td><span class="pill {{ $santri->statusPillClass() }}">{{ $santri->statusLabel() }}</span></td>
              <td>
                <div style="display:flex;gap:6px;align-items:center">
                  <a href="{{ route('admin.siakad.santri.edit', $santri) }}" class="btn ghost" style="padding:7px 10px;font-size:12px"><i class="fa-solid fa-pen"></i></a>
                  <form method="POST" action="{{ route('admin.siakad.santri.destroy', $santri) }}" onsubmit="return confirm('Hapus data santri {{ $santri->nama }}? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn ghost" style="padding:7px 10px;font-size:12px;color:var(--red)"><i class="fa-solid fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="margin-top:12px">{{ $santris->links() }}</div>
  @endif
</div>
@endsection
