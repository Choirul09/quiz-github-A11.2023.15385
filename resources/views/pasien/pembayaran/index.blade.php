<x-layouts.app title="Pembayaran Pemeriksaan">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Pembayaran Pemeriksaan</h2>
                <p class="text-sm text-slate-500 mt-1">Daftar tagihan biaya pemeriksaan dan unggah bukti transfer.</p>
            </div>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm">
                <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Grid Tagihan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($tagihans as $tagihan)
            <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    {{-- Header Card --}}
                    <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider block">
                                {{ $tagihan->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik' }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 mt-0.5">
                                {{ $tagihan->daftarPoli->jadwalPeriksa->dokter->nama ?? 'Dokter' }}
                            </h3>
                            <span class="text-xs text-slate-400 block mt-0.5">
                                Periksa: {{ \Carbon\Carbon::parse($tagihan->tgl_periksa)->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div>
                            @if($tagihan->status_pembayaran === 'lunas')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                                    <i class="fas fa-circle-check"></i> Lunas
                                </span>
                            @elseif($tagihan->status_pembayaran === 'menunggu_verifikasi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">
                                    <i class="fas fa-clock"></i> Menunggu Verifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">
                                    <i class="fas fa-circle-exclamation"></i> Belum Dibayar
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Biaya Rincian --}}
                    <div class="py-4 space-y-2 text-sm">
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Jasa Pemeriksaan Dokter:</span>
                            <span class="font-medium">Rp 150.000</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Total Resep Obat ({{ $tagihan->detailPeriksas->count() }} item):</span>
                            <span class="font-medium">
                                Rp {{ number_format(max(0, $tagihan->biaya_periksa - 150000), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Tagihan:</span>
                            <span class="text-xl font-extrabold text-indigo-700">
                                Rp {{ number_format($tagihan->biaya_periksa, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action / Form Upload --}}
                <div class="pt-4 border-t border-slate-100">
                    @if($tagihan->status_pembayaran === 'lunas')
                        <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 flex items-center justify-between text-xs text-emerald-800">
                            <span><i class="fas fa-circle-check text-emerald-600 mr-1.5"></i> Pembayaran telah diverifikasi oleh Admin.</span>
                            @if($tagihan->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $tagihan->bukti_pembayaran) }}" target="_blank"
                                   class="text-indigo-600 hover:underline font-semibold">
                                    Lihat Bukti
                                </a>
                            @endif
                        </div>
                    @else
                        {{-- Form Upload Bukti --}}
                        <form action="{{ route('pasien.pembayaran.upload', $tagihan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            @if($tagihan->bukti_pembayaran)
                                <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800 flex items-center justify-between">
                                    <span>Bukti sudah diunggah. Ingin mengganti?</span>
                                    <a href="{{ asset('storage/' . $tagihan->bukti_pembayaran) }}" target="_blank" class="text-indigo-600 underline font-semibold">
                                        Lihat File
                                    </a>
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Unggah Bukti Transfer / Bayar (JPG, PNG max 2MB)
                                </label>
                                <input type="file" name="bukti_pembayaran" required accept="image/*"
                                       class="file-input file-input-bordered file-input-sm w-full rounded-xl bg-slate-50 text-xs text-slate-700">
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                <i class="fas fa-upload"></i>
                                {{ $tagihan->bukti_pembayaran ? 'Unggah Ulang Bukti Bayar' : 'Kirim Bukti Pembayaran' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200/80">
                <i class="fas fa-receipt text-4xl mb-3 block text-slate-300"></i>
                <span class="font-medium text-slate-600 block">Belum ada tagihan pemeriksaan</span>
                <span class="text-xs text-slate-400">Tagihan akan otomatis muncul setelah dokter menyelesaikan pemeriksaan Anda.</span>
            </div>
            @endforelse
        </div>

    </div>
</x-layouts.app>
