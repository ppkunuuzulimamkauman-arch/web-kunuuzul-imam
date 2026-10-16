@extends('admin.layout')
@section('title','Pengaturan Website')
@section('crumb','Pilih kategori di sub menu, lalu simpan')
@section('content')
<style>
.set-wrap{display:grid;grid-template-columns:230px 1fr;gap:14px;align-items:start}
@media(max-width:860px){.set-wrap{grid-template-columns:1fr}}
.set-menu{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:10px;position:sticky;top:76px}
.set-menu a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;color:var(--ink);font-size:13.5px;font-weight:700;margin-bottom:2px}
.set-menu a i{width:18px;text-align:center;color:var(--muted)}
.set-menu a:hover{background:var(--bg)}
.set-menu a.active{background:var(--primary);color:#fff}
.set-menu a.active i{color:#fff}
.set-menu small{display:block;font-size:11px;font-weight:500;opacity:.7}
.set-hint{font-size:12px;color:var(--muted);font-weight:500;margin-top:4px}
</style>

<div class="set-wrap">
  {{-- SUB MENU KATEGORI --}}
  <div class="set-menu">
    @foreach($groups as $key => $g)
      <a href="{{ route('admin.setting.index', ['tab' => $key]) }}" class="{{ $tab === $key ? 'active' : '' }}">
        <i class="{{ $g['icon'] }}"></i>
        <span>{{ $g['label'] }}<small>{{ count($g['fields']) }} field</small></span>
      </a>
    @endforeach
  </div>

  {{-- FORM ISI KATEGORI AKTIF --}}
  <div class="card" style="margin-bottom:0">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
      <div style="width:38px;height:38px;border-radius:11px;background:#ede9fe;color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
        <i class="{{ $groups[$tab]['icon'] }}"></i>
      </div>
      <div>
        <div style="font-size:15px;font-weight:800">{{ $groups[$tab]['label'] }}</div>
        <div style="font-size:12.5px;color:var(--muted)">{{ $groups[$tab]['desc'] }}</div>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.setting.update') }}" enctype="multipart/form-data">@csrf
      <input type="hidden" name="tab" value="{{ $tab }}">
      @foreach($groups[$tab]['fields'] as $key => $meta)
        @php
          $def = $defaults[$key] ?? '';
          $val = $stored[$key] ?? $def;
          $val = old('settings.'.$key, $val);
          $type = $meta['type'] ?? (str_contains((string)$def, "\n") || strlen((string)$def) > 120 ? 'textarea' : 'text');
        @endphp
        <label class="f">{{ $meta['label'] }}
          <span style="font-weight:500;color:var(--muted);font-size:11.5px"> — {{ $key }}</span>
          @if($type === 'textarea')
            <textarea name="settings[{{ $key }}]" style="white-space:pre-line" rows="4">{{ $val }}</textarea>
          @elseif($type === 'image')
            @php $imgUrl = $val ? (str_starts_with($val, 'http') || str_starts_with($val, '/') ? $val : '/storage/' . ltrim($val, '/')) : null; @endphp
            @if($imgUrl)<img src="{{ $imgUrl }}" alt="Pratinjau" style="max-width:220px;max-height:140px;object-fit:cover;border-radius:10px;border:1px solid var(--line);margin:6px 0" onerror="this.style.display='none'">@endif
            <input type="file" name="settings_file[{{ $key }}]" accept=".jpg,.jpeg,.png,.webp">
            <input type="hidden" name="settings[{{ $key }}]" value="{{ $val }}">
          @else
            <input name="settings[{{ $key }}]" value="{{ $val }}">
          @endif
          @if(!empty($meta['hint']))<div class="set-hint">{{ $meta['hint'] }}</div>@endif
        </label>
      @endforeach
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px">
        <button class="btn"><i class="fa-solid fa-floppy-disk"></i> Simpan {{ $groups[$tab]['label'] }}</button>
        <a class="btn light" href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-eye"></i> Lihat Website</a>
      </div>
    </form>

    @if($tab === 'footer')
      <div class="alert ok" style="margin-top:14px;margin-bottom:0">
        <b>Cara isi kolom Lembaga:</b> satu baris satu link dengan format <code>Label|URL</code>.<br>
        Contoh:<br><code>MADRASAH DINIYAH|/lembaga</code><br><code>MA Kunuuzul Imam|https://makunuuzulimamkauman.sch.id/</code>
      </div>
    @endif
  </div>
</div>
@endsection
