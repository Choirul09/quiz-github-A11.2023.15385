<x-layouts.app title="Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Jadwal Periksa
        </h2>

        <div class="flex items-center gap-3">
            <a href="{{ route('export.jadwal') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <i class="fas fa-file-excel text-xs"></i>
                Export Excel
            </a>

            <a href="{{ route('jadwal-periksa.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5
                      bg-primary hover:bg-primary/90
                      text-white text-sm font-semibold
                      rounded-xl transition">
                <i class="fas fa-plus text-xs"></i>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('message'))
        <div class="mb-4 p-4 rounded-xl bg-green-100 border border-green-200 text-green-700 text-sm">
            {{ session('message') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Mulai</th>
                            <th class="px-6 py-4">Jam Selesai</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse($jadwalPeriksas as $jadwal)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $jadwal->hari }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                @if($jadwal->status == 'Y' || $jadwal->status == 'Aktif')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('jadwal-periksa.edit', $jadwal->id) }}" class="inline-flex items-center gap-1 px-4 py-2
                                              bg-amber-500 hover:bg-amber-600
                                              text-white text-xs font-semibold
                                              rounded-lg transition">
                                        <i class="fas fa-pen text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('jadwal-periksa.destroy', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline-flex items-center gap-1 px-4 py-2
                                                   bg-red-500 hover:bg-red-600
                                                   text-white text-xs font-semibold
                                                   rounded-lg transition">
                                            <i class="fas fa-trash text-xs"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                                Belum ada data jadwal periksa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>