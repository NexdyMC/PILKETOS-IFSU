<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    {{-- tailwind css --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
	
    <script src="https://unpkg.com/lucide@latest"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
	<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" data-navigate-once></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @livewireStyles
</head>
<body class="h-screen bg-slate-100">
    <div class="flex min-h-screen min-w-screen">
    
        <div id="sidebarOverlay" onclick="toggleSidebar(false)" class="hidden fixed inset-0 bg-navy-950/60 z-40 lg:hidden"></div>

        {{-- sidebar --}}
        <aside id="sidebar" class="thin-scroll w-64 shrink-0 h-screen sticky top-0 bg-navy-900 border-r border-white/5 flex flex-col overflow-y-auto">

            {{-- title --}}
            <div class="flex items-center gap-3 px-6 h-20 border-b border-white/5 shrink-0">
                <div class="w-10 h-10 rounded-xl bg-primary-700 flex items-center justify-center font-display font-bold text-accent-400 shrink-0">SI</div>
                <div class="min-w-0">
                    <p class="font-display font-semibold text-sm text-white leading-tight truncate">E-Voting OSIS</p>
                    <p class="text-[11px] text-slate-400 truncate">SMK Informatika Sumedang</p>
                </div>
            </div>

            {{-- navbar --}}
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

            {{-- logout --}}
            <div class="flex-2 items-center gap-3 px-3 py-6 space-y-1.5 h-20 shrink-0 border-t border-white/10">
                <a href="{{ route('admin.logout') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 font-medium border-l-4 border-transparent hover:border-red-600 hover:text-red-400 hover:bg-red-600/20">
                    <i class="fa-solid fa-right-from-bracket fa-lg"></i>
                    Logout
                </a>
            </div>
        </aside>
        
        <div id="main-wrapper" class="flex flex-col flex-1 min-w-0 min-h-screen bg-slate-50">
            <header class="sticky top-0 z-30 w-full border-b bg-white/90 backdrop-blur-md border-slate-100">
                <div class="flex items-center justify-between h-20 gap-4 px-4 sm:px-8">

                    <div class="flex items-center min-w-0 gap-3">
                        <!-- Hamburger: mobile only -->
                        <button onclick="toggleSidebar(true)" class="flex items-center justify-center border rounded-lg lg:hidden shrink-0 w-9 h-9 border-slate-200 text-navy-700 hover:bg-slate-50">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>

                        <div class="min-w-0">
                            <h1 id="pageTitleText" class="text-lg font-semibold truncate font-display sm:text-xl text-navy-900">Statistik Voting</h1>
                            <div id="breadcrumbText" class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 truncate">
                                <span class="truncate ">Admin</span>
                                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>              <span class="truncate text-primary-600 font-medium">Statistik Voting</span>
                            </div>
                        </div>
                    </div>

                    <!-- Profil Admin -->
                    <div class="flex items-center gap-3 shrink-0">
                        <img src="" alt="Foto Admin" class="object-cover w-10 h-10 border-2 rounded-full border-primary-100">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold leading-tight text-navy-900">Febri Pratama</p>
                            <p class="text-xs text-slate-500">Admin Pemilu</p>
                        </div>
                        <i data-lucide="chevron-down" class="hidden w-4 h-4 sm:block text-slate-400"></i>
                    </div>
                </div>
            </header>
            
            <main id="main-content" class="w-full overflow-hidden">
                {{ $slot }}
            </main>
        </div>
    </div>

	<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{ asset('js/sidebar.js') }}"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>