<x-layouts.app title="Dashboard Admin">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[#1e2d6b]">Dashboard Admin</h1>
        <p class="text-sm text-slate-400 mt-1">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Dokter --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-user-doctor text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Dokter</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\User::where('role', 'dokter')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pasien --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-hospital-user text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Pasien</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\User::where('role', 'pasien')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Poli --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-hospital text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Poli</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\Poli::count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Obat --}}
        <div class="card bg-base-100 shadow-md rounded-xl">
            <div class="card-body p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                        <i class="fas fa-pills text-rose-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Obat</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\Obat::count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
