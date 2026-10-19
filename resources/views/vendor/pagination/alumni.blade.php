@if ($paginator->hasPages())
  <nav class="flex flex-wrap items-center justify-center gap-1.5" aria-label="Navigasi halaman">
    @if ($paginator->onFirstPage())
      <span class="px-3 py-2 rounded-lg border border-stone-200 text-stone-300 text-sm">Prev</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
         class="px-3 py-2 rounded-lg border border-stone-200 text-stone-600 text-sm font-bold hover:border-emerald-600 hover:text-emerald-700 transition">Prev</a>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="px-3 py-2 text-stone-400 text-sm">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="px-3.5 py-2 rounded-lg bg-emerald-700 text-white text-sm font-extrabold">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg border border-stone-200 text-stone-600 text-sm font-bold hover:border-emerald-600 hover:text-emerald-700 transition">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next"
         class="px-3 py-2 rounded-lg border border-stone-200 text-stone-600 text-sm font-bold hover:border-emerald-600 hover:text-emerald-700 transition">Next</a>
    @else
      <span class="px-3 py-2 rounded-lg border border-stone-200 text-stone-300 text-sm">Next</span>
    @endif
  </nav>
@endif
