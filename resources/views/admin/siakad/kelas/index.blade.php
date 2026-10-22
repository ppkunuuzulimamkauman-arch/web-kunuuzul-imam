@extends('admin.layout')

@section('title', 'Kelas')
@section('crumb', 'Kelas per tahun ajaran')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.siakad.kelas.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <div class="searchbar" style="min-width:220px;background:var(--card)">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input name="q" value="{{ $q }}" placeholder="Cari nama kelas...">
    </div>
    <select name="tahun_ajaran_id" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua tahun ajaran</option>
      @foreach($tahunAjarans as $tahun)
        <option value="{{ $tahun->id }}" @selected((string) $tahunAjaranId === (string) $tahun->id)>{{ $tahun->nama }}{{ $tahun->is_aktif ? ' (aktif)' : '' }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn ghost"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
  </form>

  <a class="btn" href="{{ route('admin.siakad.kelas.create') }}"><i class="fa-solid fa-plus"></i> Tambah Kelas</a>
</div>

@if($tahunAjarans->isEmpty())
  <div class="alert" style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
    Belum ada tahun ajaran. <a href="{{ route('admin.siakad.tahun-ajaran.create') }}" style="color:inherit;font-weight:800">Buat tahun ajaran dulu</a> sebelum menambahkan kelas.
  </div>
@endif

<div class="card">
  @if($kelas->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada data kelas.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Kelas</th>
            <th>Tahun Ajaran</th>
            <th>Jenjang / Tingkat</th>
            <th>Wali Kelas</th>
            <th>Santri</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($kelas as $item)
            <tr>
              <td><b>{{ $item->nama }}</b></td>
              <td>{{ $item->tahunAjaran?->nama ?? '-' }}</td>
              <td>{{ $item->jenjang ?: '-' }}{{ $item->tingkat ? ' · Tingkat '.$item->tingkat : '' }}</td>
              <td>{{ $item->wali_kelas ?: '-' }}</td>
              <td>{{ $item->santris_count }} santri</td>
              <td><span class="pill {{ $item->is_aktif ? 'p-green' : 'p-gray' }}">{{ $item->is_aktif ? 'Aktif' : 'Tidak aktif' }}</span></td>
              <td>
                <div style="display:flex;gap:6px;align-items:center">
                  <a href="{{ route('admin.siakad.kelas.edit', $item) }}" class="btn ghost" style="padding:7px 10px;font-size:12px"><i class="fa-solid fa-pen"></i></a>
                  <form method="POST" action="{{ route('admin.siakad.kelas.destroy', $item) }}" onsubmit="return confirm('Hapus kelas {{ $item->nama }}?')">
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
    <div style="margin-top:12px">{{ $kelas->links() }}</div>
  @endif
</div>
@endsection
