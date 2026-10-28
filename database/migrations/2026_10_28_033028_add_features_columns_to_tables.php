<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('obat') && !Schema::hasColumn('obat', 'stok')) {
            Schema::table('obat', function (Blueprint $table) {
                $table->integer('stok')->default(0)->after('harga');
            });
        }

        if (Schema::hasTable('periksa')) {
            Schema::table('periksa', function (Blueprint $table) {
                if (!Schema::hasColumn('periksa', 'status_pembayaran')) {
                    $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])->default('belum_bayar')->after('biaya_periksa');
                }
                if (!Schema::hasColumn('periksa', 'bukti_pembayaran')) {
                    $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
                }
                if (!Schema::hasColumn('periksa', 'tgl_bayar')) {
                    $table->timestamp('tgl_bayar')->nullable()->after('bukti_pembayaran');
                }
            });
        }

        if (Schema::hasTable('jadwal_periksa') && !Schema::hasColumn('jadwal_periksa', 'antrian_sekarang')) {
            Schema::table('jadwal_periksa', function (Blueprint $table) {
                $table->integer('antrian_sekarang')->default(0)->after('jam_selesai');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('obat') && Schema::hasColumn('obat', 'stok')) {
            Schema::table('obat', function (Blueprint $table) {
                $table->dropColumn('stok');
            });
        }

        if (Schema::hasTable('periksa')) {
            Schema::table('periksa', function (Blueprint $table) {
                $table->dropColumn(['status_pembayaran', 'bukti_pembayaran', 'tgl_bayar']);
            });
        }

        if (Schema::hasTable('jadwal_periksa') && Schema::hasColumn('jadwal_periksa', 'antrian_sekarang')) {
            Schema::table('jadwal_periksa', function (Blueprint $table) {
                $table->dropColumn('antrian_sekarang');
            });
        }
    }
};
