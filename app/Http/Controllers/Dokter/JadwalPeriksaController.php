<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        // Menampilkan jadwal periksa milik dokter yang sedang login
        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', Auth::id())->get();
        
        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|string|max:10',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'id_dokter'   => Auth::id(),
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status ?? 'Y', // opsional default aktif
        ]);

        return redirect()
            ->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(JadwalPeriksa $jadwalPeriksa)
    {
        // Pastikan hanya bisa mengedit jadwal miliknya sendiri
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403);
        }

        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'hari'        => 'required|string|max:10',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwalPeriksa->update([
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status ?? $jadwalPeriksa->status,
        ]);

        return redirect()
            ->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diubah')
            ->with('type', 'success');
    }

    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403);
        }

        $jadwalPeriksa->delete();

        return redirect()
            ->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil dihapus')
            ->with('type', 'success');
    }
}