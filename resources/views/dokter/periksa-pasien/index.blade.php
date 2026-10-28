<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('message'))
        <div class="mb-6 p-4 rounded-xl flex items-center gap-3 border shadow-sm {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
            <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse($daftarPasien as $daftar)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                            {{-- ID --}}
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Nama Pasien --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $daftar->pasien->nama ?? ($daftar->pasien->name ?? '-') }}
                            </td>

                            {{-- Keluhan --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $daftar->keluhan ?? '-' }}
                            </td>

                            {{-- Nomor Antrian --}}
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $daftar->no_antrian }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    @if($daftar->periksa)
                                        {{-- Status: Sudah Diperiksa --}}
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sudah Diperiksa
                                        </span>
                                    @else
                                        {{-- Button: Panggil Antrian --}}
                                        <form action="{{ route('dokter.panggil-antrian', $daftar->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    title="Panggil nomor antrian ini (update live antrian)"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold rounded-lg transition">
                                                <i class="fas fa-bullhorn text-xs"></i>
                                                Panggil
                                            </button>
                                        </form>

                                        {{-- Button: Periksa --}}
                                        <a href="{{ route('periksa-pasien.create', $daftar->id) }}" 
                                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                            <i class="fas fa-stethoscope text-xs"></i>
                                            Periksa
                                        </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada antrian pasien untuk diperiksa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>