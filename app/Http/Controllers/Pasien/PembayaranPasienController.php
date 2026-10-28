<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiunggahNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranPasienController extends Controller
{
    public function index()
    {
        $tagihans = Periksa::with([
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat'
        ])
        ->whereHas('daftarPoli', function ($q) {
            $q->where('id_pasien', Auth::id());
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $periksa = Periksa::with('daftarPoli.jadwalPeriksa.dokter')
            ->whereHas('daftarPoli', function ($q) {
                $q->where('id_pasien', Auth::id());
            })
            ->findOrFail($id);

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'bukti_pembayaran.max' => 'Ukuran maksimal file adalah 2MB.',
        ]);

        // Hapus file lama jika ada
        if ($periksa->bukti_pembayaran && Storage::disk('public')->exists($periksa->bukti_pembayaran)) {
            Storage::disk('public')->delete($periksa->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $periksa->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
            'tgl_bayar' => now(),
        ]);

        // Kirim notifikasi ke Dokter yang memeriksa
        $dokter = $periksa->daftarPoli->jadwalPeriksa->dokter;
        if ($dokter) {
            $dokter->notify(new PembayaranDiunggahNotification($periksa));
        }

        return redirect()->route('pasien.pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi Admin.');
    }
}
