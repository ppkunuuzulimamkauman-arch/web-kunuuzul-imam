<a href="{{ route('alumni.show', $alumni) }}"
   class="group bg-white border border-stone-200 rounded-2xl p-5 flex gap-4 hover:shadow-xl hover:-translate-y-0.5 transition">
  @if($alumni->fotoUrl())
    <img src="{{ $alumni->fotoUrl() }}" alt="Foto {{ $alumni->nama }}"
         class="w-20 h-20 rounded-2xl object-cover shrink-0 bg-stone-100">
  @else
    <div class="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl font-extrabold shrink-0">
      {{ strtoupper(substr($alumni->nama, 0, 1)) }}
    </div>
  @endif
  <div class="min-w-0">
    <h3 class="font-extrabold text-stone-900 leading-tight group-hover:text-emerald-700 transition">
      {{ $alumni->nama }}
    </h3>
    @if($alumni->angkatanLabel())
      <p class="text-[12px] font-bold text-emerald-700 mt-1">{{ $alumni->angkatanLabel() }}</p>
    @endif
    @if($alumni->lembaga)
      <p class="text-[13px] text-stone-500 mt-1 truncate">{{ $alumni->lembaga }}</p>
    @endif
    @if($alumni->domisili)
      <p class="text-[12px] text-stone-400 mt-1 truncate">
        <i class="fa-solid fa-location-dot mr-1"></i>{{ $alumni->domisili }}
      </p>
    @endif
  </div>
</a>
