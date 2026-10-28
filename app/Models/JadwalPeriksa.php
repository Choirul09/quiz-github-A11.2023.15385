<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JadwalPeriksa extends Model
{
    protected $table = 'jadwal_periksa';

    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'antrian_sekarang',
    ];

    /**
     * Mapping nama hari Indonesia ke format Carbon (dayOfWeek).
     */
    protected static array $hariMap = [
        'Senin'  => Carbon::MONDAY,
        'Selasa' => Carbon::TUESDAY,
        'Rabu'   => Carbon::WEDNESDAY,
        'Kamis'  => Carbon::THURSDAY,
        'Jumat'  => Carbon::FRIDAY,
        'Sabtu'  => Carbon::SATURDAY,
        'Minggu' => Carbon::SUNDAY,
    ];

    /**
     * Cek apakah jadwal ini sedang aktif:
     * aktif jika hari ini sama dengan hari jadwal
     * DAN waktu sekarang berada di antara jam_mulai dan jam_selesai.
     */
    public function getIsAktifAttribute(): bool
    {
        // Gunakan timezone WIB secara eksplisit
        $now = Carbon::now('Asia/Jakarta');

        // Cocokkan hari
        $hariJadwal = self::$hariMap[$this->hari] ?? null;
        if ($hariJadwal === null || $now->dayOfWeek !== $hariJadwal) {
            return false;
        }

        // Bandingkan jam (set ke tanggal hari ini agar perbandingan akurat)
        $jamMulai   = Carbon::createFromFormat('H:i:s', $this->jam_mulai, 'Asia/Jakarta')
                        ->setDateFrom($now);
        $jamSelesai = Carbon::createFromFormat('H:i:s', $this->jam_selesai, 'Asia/Jakarta')
                        ->setDateFrom($now);

        return $now->between($jamMulai, $jamSelesai);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    public function daftarPolis()
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }
}