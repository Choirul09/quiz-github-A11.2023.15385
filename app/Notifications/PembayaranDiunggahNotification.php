<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDiunggahNotification extends Notification
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
        $pasienNama = $this->periksa->daftarPoli->pasien->nama ?? 'Pasien';
        $biaya = number_format($this->periksa->biaya_periksa, 0, ',', '.');

        return [
            'periksa_id' => $this->periksa->id,
            'title' => 'Pembayaran Pasien Masuk',
            'message' => "Pasien {$pasienNama} telah mengunggah bukti pembayaran sebesar Rp {$biaya}.",
            'action_url' => route('riwayat-pasien.show', $this->periksa->id),
            'type' => 'pembayaran_diunggah',
        ];
    }
}
