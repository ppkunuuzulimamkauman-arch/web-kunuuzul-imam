<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        return view('admin.agenda.index', ['agendas' => Agenda::orderBy('tanggal')->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.agenda.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'tempat' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        Agenda::create($data);
        return redirect()->route('admin.agenda.index')->with('sukses', 'Agenda ditambahkan, tampil di Majelis Terdekat & Agenda Pondok.');
    }

    public function edit(Agenda $agenda): View
    {
        return view('admin.agenda.form', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'tempat' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif');
        $agenda->update($data);
        return redirect()->route('admin.agenda.index')->with('sukses', 'Agenda diperbarui.');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        $agenda->delete();
        return back()->with('sukses', 'Agenda dihapus.');
    }
}
