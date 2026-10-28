<?php

namespace App\Http\Controllers;

use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private function streamCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel to open accents/Indonesian properly
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    // 1. Export Data Dokter (Role Admin)
    public function dokter(): StreamedResponse
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();
        $headers = ['No', 'Nama Dokter', 'Email', 'No. KTP', 'No. HP', 'Alamat', 'Poli'];

        $rows = [];
        foreach ($dokters as $idx => $d) {
            $rows[] = [
                $idx + 1,
                $d->nama,
                $d->email,
                $d->no_ktp ?? '-',
                $d->no_hp ?? '-',
                $d->alamat ?? '-',
                $d->poli->nama_poli ?? 'Belum Ditentukan',
            ];
        }

        return $this->streamCsv('data_dokter_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    // 2. Export Data Pasien (Role Admin)
    public function pasien(): StreamedResponse
    {
        $pasiens = User::where('role', 'pasien')->get();
        $headers = ['No', 'Nama Pasien', 'Email', 'No. Rekam Medis', 'No. KTP', 'No. HP', 'Alamat'];

        $rows = [];
        foreach ($pasiens as $idx => $p) {
            $rows[] = [
                $idx + 1,
                $p->nama,
                $p->email,
                $p->no_rm ?? '-',
                $p->no_ktp ?? '-',
                $p->no_hp ?? '-',
                $p->alamat ?? '-',
            ];
        }

        return $this->streamCsv('data_pasien_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    // 3. Export Data Obat termasuk Stok dan Harga (Role Admin)
    public function obat(): StreamedResponse
    {
        $obats = Obat::all();
        $headers = ['No', 'Nama Obat', 'Kemasan', 'Harga (Rp)', 'Stok'];

        $rows = [];
        foreach ($obats as $idx => $o) {
            $rows[] = [
                $idx + 1,
                $o->nama_obat,
                $o->kemasan ?? '-',
                $o->harga,
                $o->stok ?? 0,
            ];
        }

        return $this->streamCsv('data_obat_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    // 4. Export Jadwal Periksa Dokter Login (Role Dokter)
    public function jadwalPeriksa(): StreamedResponse
    {
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())->get();
        $headers = ['No', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Antrian Sekarang'];

        $rows = [];
        foreach ($jadwals as $idx => $j) {
            $rows[] = [
                $idx + 1,
                $j->hari,
                substr($j->jam_mulai, 0, 5),
                substr($j->jam_selesai, 0, 5),
                $j->antrian_sekarang ?? 0,
            ];
        }

        return $this->streamCsv('jadwal_periksa_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    // 5. Export Riwayat Pasien Dokter Login (Role Dokter)
    public function riwayatPasien(): StreamedResponse
    {
        $riwayats = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($q) {
            $q->where('id_dokter', Auth::id());
        })
        ->with(['daftarPoli.pasien', 'detailPeriksas.obat'])
        ->orderBy('tgl_periksa', 'desc')
        ->get();

        $headers = [
            'No',
            'Tanggal Periksa',
            'No. Antrian',
            'Nama Pasien',
            'Keluhan',
            'Diagnosa/Catatan',
            'Obat Diresepkan',
            'Total Biaya (Rp)',
            'Status Pembayaran'
        ];

        $rows = [];
        foreach ($riwayats as $idx => $r) {
            $obats = $r->detailPeriksas->map(fn($d) => $d->obat->nama_obat ?? '')->filter()->implode(', ');

            $rows[] = [
                $idx + 1,
                \Carbon\Carbon::parse($r->tgl_periksa)->format('d/m/Y H:i'),
                $r->daftarPoli->no_antrian ?? '-',
                $r->daftarPoli->pasien->nama ?? '-',
                $r->daftarPoli->keluhan ?? '-',
                $r->catatan ?? '-',
                $obats ?: '-',
                $r->biaya_periksa,
                ucfirst(str_replace('_', ' ', $r->status_pembayaran ?? 'belum bayar')),
            ];
        }

        return $this->streamCsv('riwayat_pasien_' . date('Y-m-d') . '.csv', $headers, $rows);
    }
}
