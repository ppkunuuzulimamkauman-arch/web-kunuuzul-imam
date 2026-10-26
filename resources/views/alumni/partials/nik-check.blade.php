@php($inline = $inline ?? false)

<div class="flex items-center gap-2">
  <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
    <i class="fa-solid fa-magnifying-glass text-xs"></i>
  </span>
  <div>
    <label for="nik" class="text-sm font-extrabold text-stone-900">Cari alumni</label>
    <p class="text-[11px] text-stone-500 mt-0.5">Gunakan 16 digit NIK untuk menemukan profil.</p>
  </div>
</div>

<form method="POST" action="{{ route('alumni.register.check') }}"
      class="mt-4 {{ $inline ? 'flex flex-col md:flex-row md:items-start gap-3' : '' }}">
  @csrf
  <input type="hidden" name="source" value="{{ $source ?? 'register' }}">
  <div class="{{ $inline ? 'flex-1 min-w-0' : '' }}">
    <input id="nik" name="nik" inputmode="numeric" autocomplete="off" maxlength="16"
           pattern="[0-9]{16}" required value="{{ old('nik', $nikValue ?? '') }}" placeholder="16 digit angka"
           class="w-full px-4 {{ $inline ? 'py-3.5' : 'py-3.5' }} rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 font-mono tracking-wider focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition">
    @error('nik')
      <p class="text-red-600 text-[12px] font-bold mt-2">{{ $message }}</p>
    @enderror
  </div>
  <button type="submit"
          class="{{ $inline ? 'w-full md:w-auto px-8 py-3' : 'mt-3 w-full px-5 py-3.5' }} rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
    Lanjut
  </button>
</form>

<p class="text-[11px] text-stone-400 mt-3">
  Data hanya tampil bila sudah disetujui admin. NIK terdapat pada KTP/KK.
</p>

@push('scripts')
<script>
  (function () {
    const input = document.getElementById('nik');
    if (!input) return;
    input.addEventListener('input', function () {
      input.value = input.value.replace(/\D/g, '').slice(0, 16);
    });
  })();
</script>
@endpush
