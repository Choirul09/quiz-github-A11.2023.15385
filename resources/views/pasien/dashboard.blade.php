<x-layouts.app title="Dashboard Pasien">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[#1e2d6b]">Dashboard Pasien</h1>
        <p class="text-sm text-slate-400 mt-1">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    {{-- Info Pasien --}}
    <div class="card bg-base-100 shadow-md rounded-xl mb-6">
        <div class="card-body p-5">
            <h2 class="text-lg font-bold text-slate-700 mb-4">
                <i class="fas fa-id-card text-indigo-500 mr-2"></i>
                Informasi Pasien
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-400 font-semibold">Nama</p>
                    <p class="text-slate-800 font-medium">{{ Auth::user()->nama }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">No. Rekam Medis</p>
                    <p class="text-slate-800 font-medium">{{ Auth::user()->no_rm ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">No. KTP</p>
                    <p class="text-slate-800 font-medium">{{ Auth::user()->no_ktp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">No. HP</p>
                    <p class="text-slate-800 font-medium">{{ Auth::user()->no_hp ?? '-' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-slate-400 font-semibold">Alamat</p>
                    <p class="text-slate-800 font-medium">{{ Auth::user()->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Pendaftaran Poli --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Pendaftaran Poli</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\DaftarPoli::where('id_pasien', Auth::id())->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Periksa --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-file-medical text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Riwayat Periksa</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\Periksa::whereHas('daftarPoli', function($q) { $q->where('id_pasien', Auth::id()); })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
