<x-layouts.app title="Dashboard Dokter">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[#1e2d6b]">Dashboard Dokter</h1>
        <p class="text-sm text-slate-400 mt-1">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        {{-- Jadwal Periksa --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Jadwal Periksa</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\JadwalPeriksa::where('id_dokter', Auth::id())->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pasien Terdaftar --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Pasien Terdaftar</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\DaftarPoli::whereHas('jadwalPeriksa', function($q) { $q->where('id_dokter', Auth::id()); })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-stethoscope text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Pemeriksaan</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\Periksa::whereHas('daftarPoli.jadwalPeriksa', function($q) { $q->where('id_dokter', Auth::id()); })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
