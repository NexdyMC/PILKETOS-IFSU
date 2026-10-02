<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    {{-- tailwind css --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @livewireStyles
</head>
<body class="flex">

    <aside id="sidebar" class="thin-scroll w-72 shrink-0 h-screen sticky top-0 bg-navy-900 border-r border-white/5 flex flex-col overflow-y-auto open">
        <div class="flex items-center gap-3 px-6 h-20 border-b border-white/10 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-primary-700 flex items-center justify-center font-display font-bold text-accent-400 shrink-0">SI</div>
            <div class="min-w-0">
                <p class="font-display font-semibold text-sm text-white leading-tight truncate">E-Voting OSIS</p>
                <p class="text-[11px] text-slate-400 truncate">SMK Informatika Sumedang</p>
            </div>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-1.5">
            <p class="px-3 text-[11px] font-semibold tracking-widest text-slate-500 uppercase mb-2">Menu Utama</p>
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
                <i class="fa-solid fa-chart-bar fa-lg"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.dashboard.kandidat') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.kandidat') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
                <i class="fa-solid fa-check-to-slot fa-lg"></i>
                Kandidat
            </a>
            <a href="{{ route('admin.dashboard.siswa') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.siswa') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
                <i class="fa-solid fa-users fa-lg"></i>
                Siswa
            </a>
            <a href="{{ route('admin.dashboard.settings') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.settings') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
                <i class="fa-solid fa-gear fa-lg"></i>
                Settings
            </a>
            <a href="{{ route('admin.dashboard.export') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.export') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
                <i class="fa-solid fa-file-export"></i>
                Export
            </a>
        </nav>
        <div class="flex-2 items-center gap-3 px-3 py-6 space-y-1.5 h-20 shrink-0 border-t border-white/10">
            <a href="{{ route('admin.logout') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 font-medium border-l-4 border-transparent hover:border-red-600 hover:text-red-400 hover:bg-red-600/20">
                <i class="fa-solid fa-right-from-bracket fa-lg"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>