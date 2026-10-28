<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDiverifikasiNotification extends Notification
{
    use Queueable;

    public $periksa;

    public function __construct(Periksa $periksa)
    {
        $this->periksa = $periksa;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $poli = $this->periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik';
        $biaya = number_format($this->periksa->biaya_periksa, 0, ',', '.');

        return [
            'periksa_id' => $this->periksa->id,
            'title' => 'Pembayaran Terverifikasi (Lunas)',
            'message' => "Pembayaran Anda sebesar Rp {$biaya} untuk layanan {$poli} telah diverifikasi dan lunas.",
            'action_url' => route('pasien.riwayat.show', $this->periksa->daftarPoli->id),
            'type' => 'pembayaran_diverifikasi',
        ];
    }
}
