@extends('admin.layout')

@section('title', 'Tahun Ajaran')
@section('crumb', 'Periode akademik dan kelas')

@section('content')
<div class="toolbar">
  <div></div>
  <a class="btn" href="{{ route('admin.siakad.tahun-ajaran.create') }}"><i class="fa-solid fa-plus"></i> Tambah Tahun Ajaran</a>
</div>

<div class="card">
  @if($tahunAjarans->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada tahun ajaran. Buat tahun ajaran terlebih dahulu sebelum menambahkan kelas.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Tahun Ajaran</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($tahunAjarans as $tahunAjaran)
            <tr>
              <td><b>{{ $tahunAjaran->nama }}</b></td>
              <td>{{ $tahunAjaran->mulai?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $tahunAjaran->selesai?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $tahunAjaran->kelas_count }} kelas</td>
              <td>
                <span class="pill {{ $tahunAjaran->is_aktif ? 'p-green' : 'p-gray' }}">{{ $tahunAjaran->is_aktif ? 'Aktif' : 'Tidak aktif' }}</span>
              </td>
              <td>
                <div style="display:flex;gap:6px;align-items:center">
                  <a href="{{ route('admin.siakad.tahun-ajaran.edit', $tahunAjaran) }}" class="btn ghost" style="padding:7px 10px;font-size:12px"><i class="fa-solid fa-pen"></i></a>
                  <form method="POST" action="{{ route('admin.siakad.tahun-ajaran.destroy', $tahunAjaran) }}" onsubmit="return confirm('Hapus tahun ajaran {{ $tahunAjaran->nama }}?')">
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
    <div style="margin-top:12px">{{ $tahunAjarans->links() }}</div>
  @endif
</div>
@endsection
