<x-layouts.app title="Detail Hasil Pemeriksaan">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Detail Hasil Pemeriksaan</h2>
                <p class="text-sm text-slate-500 mt-1">Rincian diagnosa dokter, obat yang diresepkan, dan biaya.</p>
            </div>
            <a href="{{ route('pasien.riwayat.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Riwayat
            </a>
        </div>

        {{-- Info Card --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Poliklinik & Dokter</span>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli' }}
                    </h3>
                    <p class="text-sm text-slate-600 mt-0.5">
                        <i class="fas fa-user-doctor text-indigo-500 mr-1.5"></i>
                        {{ $daftarPoli->jadwalPeriksa->dokter->nama ?? 'Dokter' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        Jadwal: {{ $daftarPoli->jadwalPeriksa->hari ?? '-' }} ({{ substr($daftarPoli->jadwalPeriksa->jam_mulai ?? '', 0, 5) }} - {{ substr($daftarPoli->jadwalPeriksa->jam_selesai ?? '', 0, 5) }} WIB)
                    </p>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Informasi Antrian</span>
                    <div class="flex items-center gap-3">
                        <div class="px-3.5 py-1.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl border border-indigo-100 text-sm">
                            Nomor Antrian: {{ $daftarPoli->no_antrian }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Waktu Periksa: <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($daftarPoli->periksa->tgl_periksa)->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">
                        <span class="font-semibold text-slate-600">Keluhan Awal:</span> {{ $daftarPoli->keluhan ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Diagnosa & Catatan Dokter --}}
            <div class="py-6 border-b border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fas fa-notes-medical text-indigo-500"></i> Catatan & Diagnosa Dokter
                </h4>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 leading-relaxed">
                    {{ $daftarPoli->periksa->catatan ?? 'Tidak ada catatan khusus.' }}
                </div>
            </div>

            {{-- Resep Obat & Rincian Biaya --}}
            <div class="py-6 border-b border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <i class="fas fa-pills text-indigo-500"></i> Resep Obat yang Diberikan
                </h4>

                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                            <tr>
                                <th class="py-2.5 px-4">Nama Obat</th>
                                <th class="py-2.5 px-4">Kemasan</th>
                                <th class="py-2.5 px-4 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($daftarPoli->periksa->detailPeriksas as $detail)
                            <tr>
                                <td class="py-3 px-4 font-medium text-slate-800">
                                    {{ $detail->obat->nama_obat ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $detail->obat->kemasan ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-right font-medium text-slate-700">
                                    Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-slate-400 text-xs">
                                    Tidak ada resep obat pada pemeriksaan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Ringkasan Total Biaya & Status Pembayaran --}}
            <div class="pt-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Status Pembayaran</span>
                    @if($daftarPoli->periksa->status_pembayaran === 'lunas')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                            <i class="fas fa-check-circle"></i> Lunas
                        </span>
                    @elseif($daftarPoli->periksa->status_pembayaran === 'menunggu_verifikasi')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">
                            <i class="fas fa-clock"></i> Menunggu Verifikasi Admin
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">
                            <i class="fas fa-circle-exclamation"></i> Belum Dibayar
                        </span>
                    @endif
                </div>

                <div class="text-right w-full md:w-auto">
                    <span class="text-xs text-slate-400 block">Total Biaya (Jasa Dokter + Obat)</span>
                    <span class="text-2xl font-extrabold text-indigo-700">
                        Rp {{ number_format($daftarPoli->periksa->biaya_periksa, 0, ',', '.') }}
                    </span>
                    @if($daftarPoli->periksa->status_pembayaran !== 'lunas')
                        <div class="mt-2">
                            <a href="{{ route('pasien.pembayaran.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                <i class="fas fa-credit-card"></i>
                                Upload Bukti Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>
