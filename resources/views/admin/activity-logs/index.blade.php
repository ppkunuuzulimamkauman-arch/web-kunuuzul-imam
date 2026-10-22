@extends('admin.layout')

@section('title', 'Log Aktivitas')
@section('crumb', 'Audit log terpusat untuk seluruh modul admin')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.activity-logs.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <div class="searchbar" style="min-width:280px;background:var(--card)">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input name="q" value="{{ $q }}" placeholder="Cari aksi, deskripsi, atau nama admin...">
    </div>
    <select name="module" style="border:1px solid var(--line);border-radius:10px;padding:9px 12px;background:var(--card);color:var(--ink);font-size:13px">
      <option value="">Semua modul</option>
      @foreach($modules as $item)
        <option value="{{ $item }}" @selected($module === $item)>{{ strtoupper($item) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn ghost"><i class="fa-solid fa-filter"></i> Filter</button>
  </form>
</div>

<div class="card">
  @if($logs->isEmpty())
    <p style="color:var(--muted);font-size:14px">Belum ada aktivitas yang tercatat.</p>
  @else
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>Waktu</th>
            <th>Admin</th>
            <th>Modul</th>
            <th>Aksi</th>
            <th>Deskripsi</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
          @foreach($logs as $log)
            <tr>
              <td>
                {{ $log->created_at?->format('d/m/Y H:i') ?? '-' }}
                <br><small style="color:var(--muted)">{{ $log->created_at?->diffForHumans() }}</small>
              </td>
              <td>{{ $log->user?->name ?? 'Sistem' }}</td>
              <td><span class="pill p-gray">{{ strtoupper($log->module) }}</span></td>
              <td><code style="font-size:11px">{{ $log->action }}</code></td>
              <td style="max-width:420px">{{ $log->description ?: '-' }}</td>
              <td><small style="color:var(--muted)">{{ $log->ip_address ?: '-' }}</small></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="margin-top:12px">{{ $logs->links() }}</div>
  @endif
</div>
@endsection
