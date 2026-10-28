<x-layouts.app title="Profil & Ganti Password">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Profil Pengguna</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola data profil dan keamanan kata sandi akun Anda.</p>
            </div>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm">
                <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-rose-50 text-rose-800 border border-rose-200 shadow-sm">
                <i class="fas fa-circle-xmark text-rose-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Info Profil Card --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-id-card text-indigo-600"></i> Informasi Akun
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Nama Lengkap</span>
                    <span class="font-medium text-slate-700">{{ $user->nama ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Email</span>
                    <span class="font-medium text-slate-700">{{ $user->email }}</span>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Peran / Role</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 text-xs font-bold rounded-md uppercase bg-indigo-50 text-indigo-600 border border-indigo-200">
                        {{ $user->role }}
                    </span>
                </div>
                @if($user->role === 'pasien' && $user->no_rm)
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-xs text-slate-400 font-semibold block uppercase">No. Rekam Medis</span>
                    <span class="font-medium text-slate-700">{{ $user->no_rm }}</span>
                </div>
                @endif
                @if($user->role === 'dokter' && $user->poli)
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Poli</span>
                    <span class="font-medium text-slate-700">{{ $user->poli->nama_poli }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Form Ganti Password Card --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6">
            <h3 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                <i class="fas fa-lock text-amber-500"></i> Ganti Password
            </h3>
            <p class="text-xs text-slate-500 mb-6">Pastikan password baru Anda minimal 8 karakter dan berbeda dari password lama.</p>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="current_password">
                        Password Lama <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                           placeholder="Masukkan password saat ini"
                           class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('current_password') border-rose-500 @enderror">
                    @error('current_password')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="password">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-rose-500 @enderror">
                    @error('password')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="password_confirmation">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ketik ulang password baru"
                           class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Submit Button --}}
                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <i class="fas fa-key text-xs"></i>
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>
