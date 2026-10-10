@extends('admin.layout')
@section('title','Dashboard')
@section('crumb','Overview bisnis dan aktivitas pondok')

@section('content')
{{-- ===== stat cards ===== --}}
<div class="stats">
  <div class="stat-card">
    <div class="stat-top"><small>Total Pendaftar</small><div class="stat-ic" style="background:#ede9fe;color:#6d28d9"><i class="fa-solid fa-user-plus"></i></div></div>
    <div class="stat-num">{{ number_format($totalPendaftar) }}</div>
    <div class="delta">↑ {{ $dPendaftar['nilai'] }}% <small>vs bulan lalu</small></div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><small>Berita Publish</small><div class="stat-ic" style="background:#dbeafe;color:#2563eb"><i class="fa-solid fa-newspaper"></i></div></div>
    <div class="stat-num">{{ number_format($totalBerita) }}</div>
    <div class="delta">↑ {{ $dBerita['nilai'] }}% <small>vs bulan lalu</small></div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><small>Pesan Masuk @if($pesanBelumDibaca>0)<span class="pill p-red">{{ $pesanBelumDibaca }} baru</span>@endif</small><div class="stat-ic" style="background:#dcfce7;color:#059669"><i class="fa-solid fa-inbox"></i></div></div>
    <div class="stat-num">{{ number_format($totalPesan) }}</div>
    <div class="delta">↑ {{ $dPesan['nilai'] }}% <small>vs bulan lalu</small></div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><small>Foto Galeri</small><div class="stat-ic" style="background:#fef3c7;color:#d97706"><i class="fa-solid fa-images"></i></div></div>
    <div class="stat-num">{{ number_format($totalGaleri) }}</div>
    <div class="delta">↑ {{ $dGaleri['nilai'] }}% <small>vs bulan lalu</small></div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><small>Program Aktif</small><div class="stat-ic" style="background:#fce7f3;color:#db2777"><i class="fa-solid fa-book-open"></i></div></div>
    <div class="stat-num">{{ number_format($totalProgram) }}</div>
    <div class="delta">↑ {{ $dProgram['nilai'] }}% <small>vs bulan lalu</small></div>
  </div>
</div>

{{-- ===== chart + pipeline ===== --}}
<div class="grid2">
  <div class="panel">
    <div class="panel-head"><b>Performa Berita & Artikel</b><span class="pill p-gray">6 Bulan Terakhir</span></div>
    <div style="display:flex;gap:16px;font-size:12px;color:var(--muted);margin-bottom:8px">
      <span><i class="fa-solid fa-circle" style="color:#6d28d9;font-size:9px"></i> Berita</span>
      <span><i class="fa-solid fa-circle" style="color:#b7a6f5;font-size:9px"></i> Artikel</span>
    </div>
    <div style="height:280px"><canvas id="chPerf"></canvas></div>
  </div>
  <div class="panel">
    <div class="panel-head"><b>Pipeline PPDB</b><a class="link" href="{{ route('admin.pendaftaran.index') }}">Semua →</a></div>
    @php $colors=['menunggu'=>'#6d28d9','diproses'=>'#2563eb','diterima'=>'#059669','ditolak'=>'#dc2626','lunas'=>'#d97706']; $icons=['menunggu'=>'fa-user-clock','diproses'=>'fa-hourglass-half','diterima'=>'fa-circle-check','ditolak'=>'fa-circle-xmark','lunas'=>'fa-money-bill-wave']; @endphp
    @foreach($pipeline as $pl)
    <div class="pipe-row">
      <div class="pipe-ic" style="background:{{ $colors[$pl['status']] }}18;color:{{ $colors[$pl['status']] }}"><i class="fa-solid {{ $icons[$pl['status']] }}"></i></div>
      <div style="flex:1"><div style="display:flex;justify-content:space-between;font-weight:700"><span style="text-transform:capitalize">{{ $pl['status'] }}</span><span>{{ $pl['total'] }} • {{ $pl['persen'] }}%</span></div>
      <div class="pipe-bar"><span style="width:{{ $pl['persen'] }}%;background:{{ $colors[$pl['status']] }}"></span></div></div>
    </div>
    @endforeach
  </div>
</div>

{{-- ===== tabel + aktivitas ===== --}}
<div class="grid2b">
  <div class="panel">
    <div class="panel-head"><b>Pendaftar Terbaru</b><a class="link" href="{{ route('admin.pendaftaran.index') }}">Lihat Semua</a></div>
    <div style="overflow-x:auto"><table>
      <tr><th>Nama</th><th>Jenjang</th><th>Status</th><th>Dibuat</th></tr>
      @forelse($pendaftarTerbaru as $p)
      <tr>
        <td><b><a href="{{ route('admin.pendaftaran.show',$p) }}" style="color:var(--ink);text-decoration:none">{{ $p->nama_lengkap }}</a></b><br><small style="color:var(--muted)">{{ $p->nomor_pendaftaran }}</small></td>
        <td>{{ $p->jenjang }}</td>
        <td><span class="pill {{ $p->status==='diterima'?'p-green':($p->status==='ditolak'?'p-red':($p->status==='menunggu'?'p-gold':'p-blue')) }}">{{ $p->status }}</span></td>
        <td style="white-space:nowrap;font-size:12.5px;color:var(--muted)">{{ $p->created_at->diffForHumans() }}</td>
      </tr>
      @empty<tr><td colspan="4" style="color:var(--muted)">Belum ada pendaftar.</td></tr>@endforelse
    </table></div>
  </div>
  <div class="panel">
    <div class="panel-head"><b>Aktivitas Terbaru</b><a class="link" href="{{ route('admin.pesan.index') }}">Lihat Semua</a></div>
    @forelse($aktivitas as $a)
    <div class="act-row">
      <div class="act-ic" style="background:{{ $a['warna'] }}"><i class="fa-solid {{ $a['ikon'] }}"></i></div>
      <div style="flex:1;min-width:0"><div style="font-weight:700">{{ $a['judul'] }}</div><div style="font-size:12px;color:var(--muted)">{{ $a['sub'] }}</div></div>
      <div style="font-size:11.5px;color:var(--muted);white-space:nowrap">{{ $a['waktu']->diffForHumans() }}</div>
    </div>
    @empty<div style="color:var(--muted);font-size:13px">Belum ada aktivitas.</div>@endforelse
  </div>
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('chPerf');
if (ctx && window.Chart) {
  const dark = document.documentElement.dataset.theme === 'dark';
  const gridColor = dark ? '#2b2854' : '#eef0f7';
  const tickColor = dark ? '#9c96c9' : '#8a8fa3';
  const grad = ctx.getContext('2d').createLinearGradient(0,0,0,260);
  grad.addColorStop(0,'rgba(109,40,217,.28)'); grad.addColorStop(1,'rgba(109,40,217,0)');
  window.perfChart = new Chart(ctx, {type:'line',
    data:{labels:@json($chartLabels),datasets:[
      {label:'Berita',data:@json($chartBerita),borderColor:'#6d28d9',backgroundColor:grad,fill:true,tension:.45,pointRadius:4,pointBackgroundColor:'#6d28d9',borderWidth:2.5},
      {label:'Artikel',data:@json($chartArtikel),borderColor:'#b7a6f5',borderDash:[6,5],fill:false,tension:.45,pointRadius:3,borderWidth:2}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
      scales:{y:{beginAtZero:true,grid:{color:gridColor},ticks:{precision:0,color:tickColor}},x:{grid:{display:false},ticks:{color:tickColor}}}}});
}
</script>
@endsection
