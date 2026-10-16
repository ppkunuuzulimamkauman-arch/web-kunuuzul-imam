<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Pendaftaran;
use App\Models\Pesan;
use App\Models\Pengasuh;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $groups = Setting::groups();
        $tab = $request->query('tab', 'footer');
        if (! array_key_exists($tab, $groups)) {
            $tab = 'umum';
        }
        return view('admin.setting.index', [
            'defaults' => Setting::defaults(),
            'stored' => Setting::pluck('value', 'key')->toArray(),
            'groups' => $groups,
            'tab' => $tab,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(['settings' => 'nullable|array', 'settings.*' => 'nullable|string']);
        // Upload gambar untuk field bertipe image (mis. sampul sejarah).
        $request->validate(['settings_file' => 'nullable|array', 'settings_file.*' => 'nullable|image|max:4096']);
        $images = [];
        foreach ((array) $request->file('settings_file', []) as $key => $file) {
            if (! $file || ! $file->isValid() || ! array_key_exists($key, Setting::defaults())) continue;
            $old = Setting::get($key, '');
            if ($old && ! str_starts_with($old, 'http') && ! str_starts_with($old, '/')) {
                try { \Illuminate\Support\Facades\Storage::disk('public')->delete($old); } catch (\Throwable $e) {}
            }
            $images[$key] = $file->store('pengaturan', 'public');
        }
        foreach ((array) $request->input('settings', []) as $key => $value) {
            if (array_key_exists($key, Setting::defaults())) {
                // Nilai upload menang atas teks (agar gambar lama tidak menimpa).
                Setting::set($key, $images[$key] ?? $value);
            }
        }
        $tab = $request->input('tab', 'footer');
        if (! array_key_exists($tab, Setting::groups())) {
            $tab = 'umum';
        }
        return redirect()->route('admin.setting.index', ['tab' => $tab])->with('sukses', 'Pengaturan website berhasil disimpan.');
    }
}
