<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $riwayats = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksa'
        ])
        ->where('id_pasien', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    public function show($id)
    {
        $daftarPoli = DaftarPoli::with([
            'pasien',
            'jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksas.obat'
        ])
        ->where('id_pasien', Auth::id())
        ->findOrFail($id);

        if (!$daftarPoli->periksa) {
            return redirect()->route('pasien.riwayat.index')
                ->with('error', 'Pemeriksaan untuk pendaftaran ini belum dilakukan.');
        }

        return view('pasien.riwayat.show', compact('daftarPoli'));
    }
}
