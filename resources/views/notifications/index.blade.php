<x-layouts.app title="Daftar Notifikasi">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Notifikasi</h2>
                <p class="text-sm text-slate-500 mt-1">Pemberitahuan aktivitas pembayaran dan layanan klinik Anda.</p>
            </div>

            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-xl border border-indigo-200 transition">
                        <i class="fas fa-check-double text-xs"></i>
                        Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 rounded-xl flex items-center gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm">
                <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Notification List --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden divide-y divide-slate-100">
            @forelse($notifications as $notif)
                <div class="p-5 flex items-start gap-4 transition {{ $notif->read_at ? 'bg-white hover:bg-slate-50/60' : 'bg-indigo-50/40 hover:bg-indigo-50/70 border-l-4 border-l-indigo-600' }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notif->read_at ? 'bg-slate-100 text-slate-500' : 'bg-indigo-100 text-indigo-600' }}">
                        @if(($notif->data['type'] ?? '') === 'pembayaran_diverifikasi')
                            <i class="fas fa-circle-check text-lg text-emerald-600"></i>
                        @else
                            <i class="fas fa-wallet text-lg text-indigo-600"></i>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="text-sm font-bold text-slate-800 {{ $notif->read_at ? '' : 'text-indigo-950 font-extrabold' }}">
                                {{ $notif->data['title'] ?? 'Pemberitahuan' }}
                            </h4>
                            <span class="text-xs text-slate-400 shrink-0">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">
                            {{ $notif->data['message'] ?? '-' }}
                        </p>

                        <div class="mt-3 flex items-center gap-3">
                            @if(isset($notif->data['action_url']))
                                <a href="{{ route('notifications.read', $notif->id) }}"
                                   class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    Lihat Rincian <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @endif

                            @if(!$notif->read_at)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-slate-500 hover:text-slate-700 underline">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-slate-400">
                                    <i class="fas fa-check"></i> Sudah dibaca
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-slate-400">
                    <i class="fas fa-bell-slash text-4xl mb-3 block text-slate-300"></i>
                    <span class="font-medium text-slate-600 block">Tidak ada notifikasi</span>
                    <span class="text-xs text-slate-400">Anda belum memiliki riwayat notifikasi baru.</span>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>
</x-layouts.app>
