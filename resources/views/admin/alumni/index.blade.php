@extends('admin.layout')

@section('title', 'Data Alumni')
@section('crumb', 'Direktori alumni pondok')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.alumni.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <div class="searchbar" style="min-width:260px;background:var(--card)">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input name="q" value="{{ $q }}" placeholder="Cari nama, lembaga, domisili...">
    </div>
    <select name="angkatan" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua angkatan</option>
      @foreach($angkatans as $tahun)
        <option value="{{ $tahun }}" @selected((string) $angkatan === (string) $tahun)>Angkatan {{ $tahun }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn ghost"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
  </form>

  <a class="btn" href="{{ route('admin.alumni.create') }}"><i class="fa-solid fa-plus"></i> Tambah Alumni</a>
</div>

<div class="card">
  @if($alumnis->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada data alumni.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>Angkatan</th>
            <th>Lembaga</th>
            <th>Domisili</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($alumnis as $alumni)
            <tr>
              <td>
                @if($alumni->fotoUrl())
                  <img src="{{ $alumni->fotoUrl() }}" alt="{{ $alumni->nama }}"
                       style="width:46px;height:46px;object-fit:cover;border-radius:12px" onerror="this.style.display='none'">
                @else
                  <div class="avatar">{{ strtoupper(substr($alumni->nama, 0, 1)) }}</div>
                @endif
              </td>
              <td>
                <b>{{ $alumni->nama }}</b>
                @if($alumni->pekerjaan)
                  <br><small style="color:var(--muted)">{{ $alumni->pekerjaan }}</small>
                @endif
              </td>
              <td>
                {{ $alumni->angkatan ?: '-' }}
                @if($alumni->tahun_lulus)
                  <br><small style="color:var(--muted)">Lulus {{ $alumni->tahun_lulus }}</small>
                @endif
              </td>
              <td>{{ $alumni->lembaga ?: '-' }}</td>
              <td>{{ $alumni->domisili ?: '-' }}</td>
              <td>
                {!! $alumni->is_tampil
                    ? '<span class="pill p-green">tampil</span>'
                    : '<span class="pill p-gray">sembunyi</span>' !!}
              </td>
              <td>
                <div class="actions">
                  <a class="icon-btn" href="{{ route('admin.alumni.edit', $alumni) }}" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.alumni.destroy', $alumni) }}"
                        onsubmit="return confirm('Hapus data alumni ini?')">
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

    <div class="pagination">{{ $alumnis->links('vendor.pagination.admin') }}</div>
  @endif
</div>
@endsection
