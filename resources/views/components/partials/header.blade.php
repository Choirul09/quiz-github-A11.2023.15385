<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost text-slate-600 hover:text-slate-900">
        <i id="fsIcon" class="fas fa-expand w-4 h-4"></i>
    </button>

    @auth
    {{-- Notification Dropdown --}}
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
        $recentNotifs = auth()->user()->notifications()->take(5)->get();
    @endphp
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle relative text-slate-600 hover:text-indigo-600">
            <i class="fas fa-bell text-lg"></i>
            @if($unreadCount > 0)
                <span class="badge badge-xs badge-error absolute top-2 right-2 p-1 font-bold text-[10px] text-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </div>
        <div tabindex="0" class="dropdown-content z-[50] card card-compact w-80 sm:w-96 p-0 shadow-xl bg-white border border-slate-200 rounded-2xl overflow-hidden mt-3">
            <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <span class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fas fa-bell text-indigo-600"></i> Notifikasi
                </span>
                @if($unreadCount > 0)
                    <span class="badge badge-sm bg-indigo-50 text-indigo-600 font-bold border-indigo-200">
                        {{ $unreadCount }} baru
                    </span>
                @endif
            </div>

            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                @forelse($recentNotifs as $n)
                    <a href="{{ route('notifications.read', $n->id) }}"
                       class="p-3.5 block transition {{ $n->read_at ? 'bg-white hover:bg-slate-50' : 'bg-indigo-50/50 hover:bg-indigo-50 font-medium' }}">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-xs {{ $n->read_at ? 'bg-slate-100 text-slate-500' : 'bg-indigo-100 text-indigo-600' }}">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">
                                    {{ $n->data['title'] ?? 'Pemberitahuan' }}
                                </p>
                                <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">
                                    {{ $n->data['message'] ?? '-' }}
                                </p>
                                <span class="text-[10px] text-slate-400 mt-1 block">
                                    {{ $n->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs">
                        <i class="fas fa-bell-slash text-2xl mb-1 text-slate-300 block"></i>
                        Tidak ada notifikasi saat ini
                    </div>
                @endforelse
            </div>

            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Lihat Semua Notifikasi &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- User Profile Dropdown --}}
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="flex items-center gap-3 cursor-pointer py-1 px-2 rounded-xl hover:bg-slate-100/80 transition">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-bold text-slate-800 leading-tight">
                    {{ auth()->user()->nama ?? (auth()->user()->name ?? 'Pengguna') }}
                </div>
                <div class="text-xs text-slate-400 font-semibold uppercase leading-tight mt-0.5">
                    {{ auth()->user()->role ?? 'User' }}
                </div>
            </div>

            <div class="avatar">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center shadow-sm font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->nama ?? (auth()->user()->name ?? 'U'), 0, 1)) }}
                </div>
            </div>
        </div>

        <ul tabindex="0" class="dropdown-content z-[50] menu p-2 shadow-xl bg-white border border-slate-200 rounded-2xl w-52 mt-3 text-slate-700">
            <li class="menu-title text-xs text-slate-400 uppercase font-bold px-3 py-1">Akun Saya</li>
            <li>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 py-2 px-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <i class="fas fa-user-gear w-4 text-slate-400"></i>
                    <span>Profil & Password</span>
                </a>
            </li>
            <div class="divider my-1"></div>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="p-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 py-2 px-3 text-rose-600 hover:bg-rose-50 rounded-lg font-semibold text-left">
                        <i class="fas fa-right-from-bracket w-4"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    @endauth

</header>

<script>
    function toggleFullscreen() {
    const icon = document.getElementById('fsIcon');

    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
        icon.classList.remove('fa-expand');
        icon.classList.add('fa-compress');
    } else {
        document.exitFullscreen();
        icon.classList.remove('fa-compress');
        icon.classList.add('fa-expand');
    }
}
</script>