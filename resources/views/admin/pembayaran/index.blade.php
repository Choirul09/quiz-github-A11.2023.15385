<x-layouts.app title="Verifikasi Pembayaran">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran Pasien</h2>
                <p class="text-sm text-slate-500 mt-1">Daftar tagihan dan bukti transfer yang memerlukan konfirmasi Admin.</p>
            </div>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm">
                <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Poliklinik & Dokter</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4">Bukti Pembayaran</th>
                            <th class="px-6 py-4">Waktu Unggah</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($pembayarans as $bayar)
                        <tr class="hover:bg-slate-50/80 transition">
                            {{-- Pasien --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ $bayar->daftarPoli->pasien->nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $bayar->daftarPoli->pasien->no_rm ?? '-' }}
                                </div>
                            </td>

                            {{-- Poli & Dokter --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $bayar->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $bayar->daftarPoli->jadwalPeriksa->dokter->nama ?? 'Dokter' }}
                                </div>
                            </td>

                            {{-- Total Biaya --}}
                            <td class="px-6 py-4 font-bold text-indigo-700">
                                Rp {{ number_format($bayar->biaya_periksa, 0, ',', '.') }}
                            </td>

                            {{-- Bukti Bayar --}}
                            <td class="px-6 py-4">
                                @if($bayar->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $bayar->bukti_pembayaran) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition border border-slate-200">
                                        <i class="fas fa-image text-indigo-600"></i>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum diunggah</span>
                                @endif
                            </td>

                            {{-- Waktu Bayar --}}
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $bayar->tgl_bayar ? \Carbon\Carbon::parse($bayar->tgl_bayar)->format('d M Y, H:i') : '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($bayar->status_pembayaran === 'lunas')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                                        <i class="fas fa-circle-check"></i> Lunas
                                    </span>
                                @elseif($bayar->status_pembayaran === 'menunggu_verifikasi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">
                                        <i class="fas fa-clock"></i> Perlu Verifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">
                                        <i class="fas fa-circle-exclamation"></i> Belum Bayar
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                @if($bayar->status_pembayaran === 'menunggu_verifikasi')
                                    <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini sebagai LUNAS?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fas fa-check"></i>
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                @elseif($bayar->status_pembayaran === 'lunas')
                                    <span class="text-xs text-emerald-600 font-semibold">
                                        <i class="fas fa-check-double mr-1"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Menunggu Pasien</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <i class="fas fa-wallet text-4xl mb-3 block text-slate-300"></i>
                                <span class="font-medium text-slate-600 block">Belum ada data pembayaran</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.app>
