<x-layouts.app title="Riwayat Pendaftaran Poli">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Riwayat Pendaftaran Poli</h2>
                <p class="text-sm text-slate-500 mt-1">Daftar histori pendaftaran dan status pemeriksaan poliklinik Anda.</p>
            </div>
            <a href="{{ route('pasien.daftar') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <i class="fas fa-plus text-xs"></i>
                Daftar Poli Baru
            </a>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('error'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-rose-50 text-rose-800 border border-rose-200 shadow-sm">
                <i class="fas fa-circle-xmark text-rose-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No. Antrian</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Jadwal Periksa</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($riwayats as $riwayat)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-sm border border-indigo-100">
                                    {{ $riwayat->no_antrian }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli Umum' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $riwayat->jadwalPeriksa->dokter->nama ?? 'Dokter' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <span class="font-medium">{{ $riwayat->jadwalPeriksa->hari ?? '-' }}</span>
                                <span class="text-xs text-slate-400 block">
                                    {{ substr($riwayat->jadwalPeriksa->jam_mulai ?? '', 0, 5) }} - {{ substr($riwayat->jadwalPeriksa->jam_selesai ?? '', 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                {{ $riwayat->keluhan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $riwayat->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($riwayat->periksa)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sudah Diperiksa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Pemeriksaan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($riwayat->periksa)
                                    <a href="{{ route('pasien.riwayat.show', $riwayat->id) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition border border-indigo-200">
                                        <i class="fas fa-file-waveform text-xs"></i>
                                        Detail
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <i class="fas fa-notes-medical text-4xl mb-3 block text-slate-300"></i>
                                <span class="font-medium text-slate-600 block">Belum ada riwayat pendaftaran poli</span>
                                <span class="text-xs text-slate-400">Silakan lakukan pendaftaran poli terlebih dahulu</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.app>
