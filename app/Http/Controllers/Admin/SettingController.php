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
        foreach ((array) $request->input('settings', []) as $key => $value) {
            if (array_key_exists($key, Setting::defaults())) {
                Setting::set($key, $value);
            }
        }
        $tab = $request->input('tab', 'footer');
        if (! array_key_exists($tab, Setting::groups())) {
            $tab = 'umum';
        }
        return redirect()->route('admin.setting.index', ['tab' => $tab])->with('sukses', 'Pengaturan website berhasil disimpan.');
    }
}
