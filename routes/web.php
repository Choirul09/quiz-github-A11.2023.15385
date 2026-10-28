<?php

use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pasien\DashboardPasienController;
use App\Http\Controllers\Pasien\PembayaranPasienController;
use App\Http\Controllers\Pasien\PoliController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Authenticated Routes (All Roles)
Route::middleware('auth')->group(function () {
    // 7. Profile & Ganti Password
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // 2. Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::match(['get', 'post'], '/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Group Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    // 6. Verifikasi Pembayaran
    Route::get('/pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::patch('/pembayaran/{id}/verifikasi', [VerifikasiPembayaranController::class, 'verifikasi'])->name('admin.pembayaran.verifikasi');

    // 5. Export Excel (Admin)
    Route::get('/export/dokter', [ExportController::class, 'dokter'])->name('export.dokter');
    Route::get('/export/pasien', [ExportController::class, 'pasien'])->name('export.pasien');
    Route::get('/export/obat', [ExportController::class, 'obat'])->name('export.obat');
});

// Group Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');
    Route::post('/periksa-pasien/{id}/panggil', [PeriksaPasienController::class, 'panggilAntrian'])->name('dokter.panggil-antrian');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');

    // 5. Export Excel (Dokter)
    Route::get('/export/jadwal', [ExportController::class, 'jadwalPeriksa'])->name('export.jadwal');
    Route::get('/export/riwayat', [ExportController::class, 'riwayatPasien'])->name('export.riwayat');
});

// Group Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    // 1. Dashboard Pasien & Real-time Live Queue
    Route::get('/dashboard', [DashboardPasienController::class, 'index'])->name('pasien.dashboard');
    Route::get('/dashboard/live', [DashboardPasienController::class, 'liveQueue'])->name('pasien.dashboard.live');

    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');

    // 3. Riwayat Pendaftaran Poli
    Route::get('/riwayat', [RiwayatPendaftaranController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatPendaftaranController::class, 'show'])->name('pasien.riwayat.show');

    // 6. Upload Bukti Pembayaran
    Route::get('/pembayaran', [PembayaranPasienController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{id}/upload', [PembayaranPasienController::class, 'uploadBukti'])->name('pasien.pembayaran.upload');
});