<x-layouts.app title="Dashboard Pasien">
    <div class="space-y-6">

        {{-- Alert Notifikasi --}}
        @if(session('message'))
            <div class="p-4 rounded-xl flex items-center gap-3 border shadow-sm {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                <i class="fas {{ session('type') == 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-rose-600' }} text-lg"></i>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        {{-- 1. BANNER ANTRIAN AKTIF ANDA --}}
        <div id="antrianBannerContainer">
            @if($antrianAktif)
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 text-white p-6 sm:p-8 shadow-lg shadow-indigo-500/20">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                        {{-- Info Poli & Dokter --}}
                        <div class="space-y-2">
                            <span class="inline-block text-xs uppercase tracking-widest font-extrabold text-blue-200 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                                Antrian Aktif Anda
                            </span>

                            <div>
                                <span class="text-xs text-blue-200 block font-medium">Poliklinik</span>
                                <h3 id="bannerPoli" class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                                    {{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik' }}
                                </h3>
                            </div>

                            <div>
                                <span class="text-xs text-blue-200 block font-medium">Dokter</span>
                                <p id="bannerDokter" class="text-base sm:text-lg font-semibold text-white/95">
                                    {{ $antrianAktif->jadwalPeriksa->dokter->nama ?? 'Dokter' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs text-blue-200 block font-medium">Jadwal Periksa</span>
                                <p id="bannerJadwal" class="text-xs sm:text-sm text-blue-100">
                                    {{ $antrianAktif->jadwalPeriksa->hari ?? '-' }} ({{ substr($antrianAktif->jadwalPeriksa->jam_mulai, 0, 5) }} - {{ substr($antrianAktif->jadwalPeriksa->jam_selesai, 0, 5) }} WIB)
                                </p>
                            </div>
                        </div>

                        {{-- Box Nomor Antrian --}}
                        <div class="flex items-center gap-4">
                            {{-- Box 1: Nomor Anda --}}
                            <div class="bg-white/15 backdrop-blur-md border border-white/25 rounded-2xl p-4 sm:p-6 text-center w-32 sm:w-36">
                                <span class="text-xs font-bold text-blue-100 block mb-1 uppercase tracking-wide">
                                    Nomor Anda
                                </span>
                                <div id="bannerNoAnda" class="text-4xl sm:text-5xl font-black text-white">
                                    {{ $antrianAktif->no_antrian }}
                                </div>
                            </div>

                            {{-- Box 2: Sedang Dilayani --}}
                            <div class="bg-white text-slate-800 rounded-2xl p-4 sm:p-6 text-center w-36 sm:w-40 shadow-xl">
                                <span class="text-[11px] font-extrabold text-slate-500 block mb-1 uppercase tracking-wider">
                                    Sedang Dilayani
                                </span>
                                <div id="bannerSedangDilayani" class="text-4xl sm:text-5xl font-black text-indigo-700">
                                    {{ $antrianAktif->jadwalPeriksa->antrian_sekarang ?? 0 }}
                                </div>
                                <div class="mt-1 flex items-center justify-center gap-1.5 text-[10px] font-bold text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Live Update
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="rounded-3xl bg-white border border-slate-200/80 p-8 text-center shadow-sm">
                    <div class="max-w-md mx-auto space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl shadow-sm">
                            <i class="fas fa-ticket"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Anda Belum Memiliki Antrian Aktif</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Silakan lakukan pendaftaran poli terlebih dahulu untuk mendapatkan nomor antrian pemeriksaan hari ini.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('pasien.daftar') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                <i class="fas fa-calendar-plus"></i> Daftar Antrian Poli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. TABEL DAFTAR JADWAL POLIKLINIK DAN DOKTER --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                        <i class="fas fa-hospital text-indigo-600"></i>
                        Jadwal Poliklinik & Antrian Sedang Dilayani
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi live nomor antrian yang sedang dipanggil per poli dan dokter.</p>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Pembaruan Real-Time Aktif</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poliklinik</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Periksa</th>
                            <th class="px-6 py-4 text-center">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody id="tableJadwalBody" class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($jadwals as $idx => $j)
                        <tr class="hover:bg-slate-50/80 transition" id="row-jadwal-{{ $j->id }}">
                            <td class="px-6 py-4 font-semibold text-slate-500">
                                {{ $idx + 1 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $j->dokter->poli->nama_poli ?? 'Poli Umum' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">
                                {{ $j->dokter->nama ?? 'Dokter' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-md font-semibold text-xs text-slate-700">
                                    {{ $j->hari }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="badge-sedang-dilayani inline-flex items-center justify-center px-4 py-1.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 min-w-[70px]">
                                    {{ $j->antrian_sekarang ?? 0 }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Belum ada jadwal poliklinik tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- 3. JAVASCRIPT REAL-TIME AUTO UPDATE (Tanpa reload halaman) --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-polling live update setiap 3 detik
            function fetchLiveQueue() {
                fetch("{{ route('pasien.dashboard.live') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // Update banner sedang dilayani
                    if (data.has_active && data.active_data) {
                        const elSedangDilayani = document.getElementById('bannerSedangDilayani');
                        if (elSedangDilayani) {
                            elSedangDilayani.textContent = data.active_data.sedang_dilayani;
                        }
                    }

                    // Update nomor sedang dilayani per row jadwal
                    if (data.jadwals && data.jadwals.length > 0) {
                        data.jadwals.forEach(j => {
                            const row = document.getElementById('row-jadwal-' + j.id);
                            if (row) {
                                const badge = row.querySelector('.badge-sedang-dilayani');
                                if (badge && badge.textContent.trim() != j.antrian_sekarang) {
                                    badge.textContent = j.antrian_sekarang;
                                    badge.classList.add('bg-emerald-100', 'text-emerald-800');
                                    setTimeout(() => {
                                        badge.classList.remove('bg-emerald-100', 'text-emerald-800');
                                    }, 1500);
                                }
                            }
                        });
                    }
                })
                .catch(err => console.log('Live queue polling...', err));
            }

            // Jalankan polling setiap 3.5 detik
            setInterval(fetchLiveQueue, 3500);
        });
    </script>
    @endpush
</x-layouts.app>