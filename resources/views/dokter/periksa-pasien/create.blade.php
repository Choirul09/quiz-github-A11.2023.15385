<x-layouts.app title="Form Pemeriksaan Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Form Pemeriksaan Pasien
        </h2>

        <a href="{{ route('periksa-pasien.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border max-w-3xl">
        <div class="card-body p-6">
            <form action="{{ route('periksa-pasien.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Hidden Input ID Daftar Poli --}}
                <input type="hidden" name="id_daftar_poli" value="{{ $daftarPoli->id }}">

                {{-- Nama Pasien (Read-only) --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Nama Pasien</span>
                    </label>
                    <input type="text" 
                           value="{{ $daftarPoli->pasien->nama ?? ($daftarPoli->pasien->name ?? '-') }}" 
                           readonly 
                           class="input input-bordered w-full rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed" />
                </div>

                {{-- Keluhan Pasien (Read-only) --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Keluhan Pasien</span>
                    </label>
                    <textarea readonly rows="2" 
                              class="textarea textarea-bordered w-full rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed">{{ $daftarPoli->keluhan ?? '-' }}</textarea>
                </div>

                {{-- Tanggal Periksa --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Tanggal Periksa</span>
                    </label>
                    <input type="datetime-local" name="tgl_periksa" 
                           value="{{ old('tgl_periksa', now()->format('Y-m-d\TH:i')) }}" required
                           class="input input-bordered w-full rounded-xl @error('tgl_periksa') input-error @enderror" />
                    @error('tgl_periksa')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Catatan Dokter --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Catatan / Diagnosa Dokter</span>
                    </label>
                    <textarea name="catatan" rows="3" required placeholder="Tuliskan catatan diagnosa pemeriksaan..."
                              class="textarea textarea-bordered w-full rounded-xl @error('catatan') textarea-error @enderror">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pilihan Obat --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Resep Obat</span>
                    </label>
                    <div class="border rounded-xl p-4 bg-slate-50/50 max-h-48 overflow-y-auto space-y-2">
                        @forelse($obats as $obat)
                            <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                <input type="checkbox" name="obat[]" value="{{ $obat->id }}" 
                                       data-harga="{{ $obat->harga }}"
                                       class="checkbox checkbox-primary checkbox-sm obat-checkbox"
                                       {{ is_array(old('obat')) && in_array($obat->id, old('obat')) ? 'checked' : '' }}>
                                <div class="flex-1 text-sm">
                                    <span class="font-medium text-slate-800">{{ $obat->nama_obat }}</span>
                                    <span class="text-xs text-slate-500 ml-2">({{ $obat->kemasan }})</span>
                                </div>
                                <span class="text-xs font-semibold text-emerald-600">
                                    + Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                </span>
                            </label>
                        @empty
                            <p class="text-slate-400 text-xs text-center py-2">Tidak ada data obat tersedia</p>
                        @endforelse
                    </div>
                </div>

                {{-- Total Biaya Periksa (Jasa Dokter 150.000 + Total Obat) --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Total Biaya Periksa (Rp)</span>
                    </label>
                    <input type="number" id="biaya_periksa" name="biaya_periksa" 
                           value="{{ old('biaya_periksa', 150000) }}" readonly
                           class="input input-bordered w-full rounded-xl bg-slate-100 font-bold text-slate-800 cursor-not-allowed" />
                    <span class="text-xs text-slate-500 mt-1">*Sudah termasuk biaya jasa periksa dokter Rp 150.000 + harga resep obat.</span>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition">
                        <i class="fas fa-check text-xs"></i>
                        Simpan Hasil Periksa
                    </button>
                    <a href="{{ route('periksa-pasien.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Hitung Biaya Otomatis Saat Checkbox Obat Dipilih --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const BIAYA_DOKTER = 150000;
            const inputBiaya = document.getElementById('biaya_periksa');
            const checkboxes = document.querySelectorAll('.obat-checkbox');

            function hitungTotal() {
                let totalObat = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        totalObat += parseInt(cb.getAttribute('data-harga')) || 0;
                    }
                });
                inputBiaya.value = BIAYA_DOKTER + totalObat;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', hitungTotal);
            });

            // Jalankan sekali saat pertama load jika ada old input
            hitungTotal();
        });
    </script>
</x-layouts.app>