<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiverifikasiNotification;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli'
        ])
        ->whereNotNull('status_pembayaran')
        ->orderByRaw("FIELD(status_pembayaran, 'menunggu_verifikasi', 'belum_bayar', 'lunas')")
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi($id)
    {
        $periksa = Periksa::with('daftarPoli.pasien')->findOrFail($id);

        $periksa->update([
            'status_pembayaran' => 'lunas',
        ]);

        // Kirim notifikasi ke Pasien
        $pasien = $periksa->daftarPoli->pasien;
        if ($pasien) {
            $pasien->notify(new PembayaranDiverifikasiNotification($periksa));
        }

        return redirect()->route('admin.pembayaran.index')
            ->with('success', "Pembayaran untuk pasien {$pasien->nama} berhasil diverifikasi (Lunas).");
    }
}
