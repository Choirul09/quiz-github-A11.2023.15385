<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardPasienController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        // 1. Ambil antrian aktif pasien saat ini (yang belum diperiksa)
        $antrianAktif = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksa'
        ])
        ->where('id_pasien', $pasienId)
        ->whereDoesntHave('periksa')
        ->latest()
        ->first();

        // 2. Ambil semua jadwal poli beserta dokter & antrian sekarang
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get();

        return view('pasien.dashboard', compact('antrianAktif', 'jadwals'));
    }

    // Endpoint API untuk Live Update tanpa reload
    public function liveQueue(): JsonResponse
    {
        $pasienId = Auth::id();

        $antrianAktif = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli'
        ])
        ->where('id_pasien', $pasienId)
        ->whereDoesntHave('periksa')
        ->latest()
        ->first();

        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get();

        return response()->json([
            'has_active' => $antrianAktif ? true : false,
            'active_data' => $antrianAktif ? [
                'poli' => $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik',
                'dokter' => $antrianAktif->jadwalPeriksa->dokter->nama ?? 'Dokter',
                'jadwal' => ($antrianAktif->jadwalPeriksa->hari ?? '-') . ' (' . substr($antrianAktif->jadwalPeriksa->jam_mulai, 0, 5) . ' - ' . substr($antrianAktif->jadwalPeriksa->jam_selesai, 0, 5) . ')',
                'no_antrian' => $antrianAktif->no_antrian,
                'sedang_dilayani' => $antrianAktif->jadwalPeriksa->antrian_sekarang ?? 0,
            ] : null,
            'jadwals' => $jadwals->map(function ($j) {
                return [
                    'id'               => $j->id,
                    'poli'             => $j->dokter->poli->nama_poli ?? 'Poli Umum',
                    'dokter'           => $j->dokter->nama ?? 'Dokter',
                    'hari'             => $j->hari,
                    'jam'              => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                    'antrian_sekarang' => $j->antrian_sekarang ?? 0,
                    'is_aktif'         => $j->is_aktif,
                ];
            }),
        ]);
    }
}
