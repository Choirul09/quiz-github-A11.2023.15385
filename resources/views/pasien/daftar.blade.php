<x-layouts.app title="Daftar Poli">
    <div class="flex justify-center items-center py-6 px-4">
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl w-full max-w-2xl p-8">

            {{-- Judul Form --}}
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-slate-800 flex items-center justify-center gap-2">
                    <span>📑</span> Pendaftaran Poli
                </h2>
            </div>

            {{-- Alert Notifikasi --}}
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3 border shadow-sm {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                    <i class="fas {{ session('type') == 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-rose-600' }} text-lg"></i>
                    <span class="font-medium text-sm">{{ session('message') }}</span>
                </div>
            @endif

            @if(isset($antrianAktif) && $antrianAktif)
                <div class="p-6 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 space-y-4">
                    <div class="flex items-center gap-3 font-bold text-base text-amber-800">
                        <i class="fas fa-triangle-exclamation text-xl text-amber-600"></i>
                        Anda Masih Memiliki Antrian Aktif
                    </div>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        Sesuai ketentuan, pasien tidak dapat mendaftar ke dua poli sekaligus. Anda hanya dapat mendaftar kembali setelah pemeriksaan selesai dilakukan oleh dokter.
                    </p>
                    <div class="bg-white p-4 rounded-xl border border-amber-200/80 text-xs text-slate-700 space-y-1.5">
                        <div><span class="font-semibold text-slate-500">Poli Tujuan:</span> <span class="font-bold text-slate-800">{{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poliklinik' }}</span></div>
                        <div><span class="font-semibold text-slate-500">Dokter:</span> {{ $antrianAktif->jadwalPeriksa->dokter->nama ?? 'Dokter' }}</div>
                        <div><span class="font-semibold text-slate-500">Nomor Antrian Anda:</span> <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 font-bold rounded">{{ $antrianAktif->no_antrian }}</span></div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('pasien.dashboard') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition">
                            <i class="fas fa-gauge-high"></i> Lihat Status di Dashboard
                        </a>
                    </div>
                </div>
            @else
            <form action="{{ route('pasien.daftar.submit') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Hidden Input Pasien ID --}}
                <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                {{-- Nomor Rekam Medis --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        Nomor Rekam Medis
                    </label>
                    <input type="text" 
                           value="{{ $user->no_rm ?? ($user->nomor_rekam_medis ?? '202603-005') }}" 
                           readonly 
                           class="w-full px-4 py-2.5 bg-slate-100/80 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed focus:outline-none" />
                </div>

                {{-- Pilih Poli --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        Pilih Poli
                    </label>
                    <select id="select_poli" name="id_poli" required 
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_poli') border-rose-500 @enderror">
                        <option value="" disabled selected>-- Pilih Poli --</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_poli')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Jadwal Periksa --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        Pilih Jadwal Periksa
                    </label>
                    <select id="select_jadwal" name="id_jadwal" required 
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_jadwal') border-rose-500 @enderror">
                        <option value="" disabled selected>-- Pilih Jadwal --</option>
                        @foreach($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" 
                                    data-poli="{{ $jadwal->dokter->id_poli ?? ($jadwal->dokter->poli->id ?? '') }}"
                                    {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                                {{ $jadwal->dokter->nama ?? 'Dokter' }} | {{ $jadwal->hari }} ({{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_jadwal')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        Keluhan
                    </label>
                    <textarea name="keluhan" rows="3" required
                              placeholder="Tulis keluhan anda..."
                              class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('keluhan') border-rose-500 @enderror">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Daftar Poli (Biru Cerah di Kanan Bawah) --}}
                <div style="display: flex; justify-content: flex-end; padding-top: 10px;">
                    <button type="submit" 
                            style="background-color: #2563eb !important; color: #ffffff !important; border: none; border-radius: 8px; padding: 10px 24px; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: inline-flex; align-items: center; justify-content: center;">
                        Daftar Poli
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>

    {{-- Script filter otomatis dropdown jadwal sesuai poli yang dipilih --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectPoli = document.getElementById('select_poli');
            const selectJadwal = document.getElementById('select_jadwal');
            const jadwalOptions = Array.from(selectJadwal.querySelectorAll('option'));

            selectPoli.addEventListener('change', function () {
                const selectedPoliId = this.value;

                selectJadwal.value = '';

                jadwalOptions.forEach(option => {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }
                    const optPoli = option.getAttribute('data-poli');
                    if (optPoli && optPoli !== selectedPoliId) {
                        option.hidden = true;
                    } else {
                        option.hidden = false;
                    }
                });
            });
        });
    </script>
</x-layouts.app>