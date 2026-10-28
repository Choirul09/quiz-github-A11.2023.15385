<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function panggilAntrian($id)
    {
        $daftarPoli = DaftarPoli::with('jadwalPeriksa')->findOrFail($id);
        $jadwal = $daftarPoli->jadwalPeriksa;

        if ($jadwal && $jadwal->id_dokter == Auth::id()) {
            $jadwal->update([
                'antrian_sekarang' => $daftarPoli->no_antrian,
            ]);

            try {
                broadcast(new \App\Events\AntrianUpdated($jadwal->id, $daftarPoli->no_antrian));
            } catch (\Throwable $e) {
                // broadcast silent catch jika reverb belum running
            }

            return redirect()->back()
                ->with('message', "Nomor antrian {$daftarPoli->no_antrian} sedang dipanggil.")
                ->with('type', 'success');
        }

        return redirect()->back()
            ->with('message', 'Akses tidak diizinkan.')
            ->with('type', 'error');
    }

    public function create($id)
    {
        $daftarPoli = DaftarPoli::with('pasien')->findOrFail($id);
        $obats = Obat::all();

        return view('dokter.periksa-pasien.create', compact('daftarPoli', 'obats'));
    }

   public function store(Request $request)
{
    $request->validate([
        'id_daftar_poli' => 'required|exists:daftar_poli,id',
        'tgl_periksa'    => 'required',
        'catatan'        => 'required|string',
        'biaya_periksa'  => 'required|integer',
        'obat'           => 'nullable|array',
    ]);

    // 1. Simpan Data Periksa
    $periksa = Periksa::create([
        'id_daftar_poli' => $request->id_daftar_poli,
        'tgl_periksa'    => $request->tgl_periksa ?? now(),
        'catatan'        => $request->catatan,
        'biaya_periksa'  => $request->biaya_periksa,
        'status_pembayaran' => 'belum_bayar',
    ]);

    // 2. Update antrian sekarang pada jadwal periksa jika nomor ini lebih besar
    $daftarPoli = DaftarPoli::with('jadwalPeriksa')->find($request->id_daftar_poli);
    if ($daftarPoli && $daftarPoli->jadwalPeriksa) {
        $jadwal = $daftarPoli->jadwalPeriksa;
        $nomorBerikutnya = $daftarPoli->no_antrian;
        $jadwal->update(['antrian_sekarang' => $nomorBerikutnya]);

        try {
            broadcast(new \App\Events\AntrianUpdated($jadwal->id, $nomorBerikutnya));
        } catch (\Throwable $e) {
            // silent catch
        }
    }

    // 3. Simpan Rincian Obat jika ada yang dipilih
    if ($request->has('obat') && is_array($request->obat)) {
        foreach ($request->obat as $idObat) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $idObat,
            ]);
        }
    }

    return redirect()
        ->route('periksa-pasien.index')
        ->with('message', 'Data periksa berhasil disimpan.')
        ->with('type', 'success');
}
}