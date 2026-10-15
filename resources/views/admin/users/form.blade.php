@extends('admin.layout')
@section('title',isset($user)?'Edit Akun':'Buat Akun Admin')
@section('content')
<div class="card" style="max-width:640px">
<form method="POST" action="{{ isset($user)?route('admin.users.update',$user):route('admin.users.store') }}">@csrf @if(isset($user))@method('PUT')@endif
<label class="f">Nama lengkap*<input name="name" required value="{{ old('name',$user->name??'') }}"></label>
<label class="f">Email*<input type="email" name="email" required value="{{ old('email',$user->email??'') }}"></label>
@if(auth()->user()->isUtama())
<label class="f">Peran*<select name="role" required><option value="operator" @selected(old('role',$user->role??'operator')==='operator')>Pengelola web</option><option value="superadmin" @selected(old('role',$user->role??'')==='superadmin')>Admin utama</option></select></label>
@endif
<label class="f">Password {{ isset($user)?'(kosongkan = tidak diubah)':'*'}}<input type="password" name="password" {{ isset($user)?'':'required' }} minlength="8" placeholder="min. 8 karakter"></label>
<label class="f">Konfirmasi password<input type="password" name="password_confirmation" placeholder="ulangi password"></label>
<label class="check" style="margin:4px 0 14px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$user->is_active??true))> Akun aktif (bisa login)</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan Akun</button><a class="btn ghost" href="{{ route('admin.users.index') }}">Batal</a></div>
</form>
</div>
@endsection
