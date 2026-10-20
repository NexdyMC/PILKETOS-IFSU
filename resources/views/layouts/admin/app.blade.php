@php
  $set = \App\Models\Settings::ambil();
  $logo = $set->logoUrl();
  $inisial = collect(preg_split('/\s+/', trim($set->nama_sekolah)))
      ->filter()->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))->take(2)->implode('');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Aplikasi {{ $set->judul_pemilihan }} untuk {{ $set->nama_sekolah }}" />

  <link rel="canonical" href="https://evoteosis.smkifsu.sch.id/">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  
  <title>{{ ($title ?? 'Dashboard') . ' - ' . $set->judul_pemilihan }}</title>
  
  {{-- tailwind css --}}
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.tailwind-config')
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" data-navigate-once></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> 
  @livewireStyles
  @include('partials.pwa-head')
</head>

<body class="h-screen bg-slate-100">
  <div class="flex min-h-screen w-full">
    {{-- Overlay Backdrop untuk Mobile Drawer --}}
    <div id="sidebarOverlay" onclick="toggleSidebar(false)" class="hidden fixed inset-0 z-40 bg-navy-950/60 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>
    
    {{-- sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 h-full border-r bg-navy-900 border-white/5 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:z-auto lg:h-screen lg:sticky lg:top-0 thin-scroll overflow-y-auto shrink-0 shadow-2xl lg:shadow-none">
    
      {{-- title & mobile close button --}}
      <div class="flex gap-3 items-center justify-between px-6 h-20 border-b border-white/5 shrink-0">
        <div class="flex gap-3 items-center min-w-0">
          <div class="flex overflow-hidden justify-center items-center w-10 h-10 font-bold rounded-xl bg-primary-700 font-display text-accent-400 shrink-0">
            @if ($logo)
              <img src="{{ $logo }}" alt="Logo {{ $set->nama_sekolah }}" class="w-full h-full object-contain">
            @else
              {{ $inisial }}
            @endif
          </div>
          <div class="min-w-0">
            <p class="text-sm font-semibold leading-tight truncate text-white font-display">{{ $set->judul_pemilihan }}</p>
            <p class="text-[11px] text-slate-400 truncate">{{ $set->nama_sekolah }}</p>
          </div>
        </div>
        <button type="button" onclick="toggleSidebar(false)" class="p-2 -mr-2 text-slate-400 rounded-lg lg:hidden hover:text-white hover:bg-white/10" aria-label="Tutup navigasi">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      
      {{-- navbar --}}
      <nav class="flex-1 px-3 py-6 space-y-1.5">
        <p class="px-3 text-[11px] font-semibold tracking-widest text-slate-500 uppercase mb-2">Menu Utama</p>
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
          <i class="fa-solid fa-chart-bar fa-lg"></i> Dashboard </a>
        <a href="{{ route('admin.dashboard.kandidat') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.kandidat') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
          <i class="fa-solid fa-check-to-slot fa-lg"></i> Kandidat </a>
        <a href="{{ route('admin.dashboard.siswa') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.siswa') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
          <i class="fa-solid fa-users fa-lg"></i> Siswa </a>
        <a href="{{ route('admin.dashboard.settings') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.settings') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
          <i class="fa-solid fa-gear fa-lg"></i> Settings </a>
        <a href="{{ route('admin.dashboard.export') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border-transparent border-l-4 {{ request()->routeIs('admin.dashboard.export') ? 'text-yellow-400 border-yellow-400 bg-gradient-to-l to-yellow-400/20 from-yellow-400/5' : 'text-slate-400 hover:bg-slate-400/20' }}">
          <i class="fa-solid fa-file-export"></i> Export </a>
      </nav>
      {{-- logout --}}
      <div class="flex-2 items-center gap-3 px-3 py-6 space-y-1.5 h-20 shrink-0 border-t border-white/10">
        <a href="{{ route('admin.logout') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 font-medium border-l-4 border-transparent hover:border-red-600 hover:text-red-400 hover:bg-red-600/20">
          <i class="fa-solid fa-right-from-bracket fa-lg"></i> Logout </a>
      </div>
    </aside>

    <div id="main-wrapper" class="flex flex-col flex-1 min-w-0 min-h-screen bg-slate-50">
      <header class="sticky top-0 z-30 w-full border-b bg-white/90 backdrop-blur-md border-slate-100">
        <div class="flex gap-4 justify-between items-center px-4 h-20 sm:px-8">
          <div class="flex gap-3 items-center min-w-0">
            <!-- Hamburger: mobile only -->
            <button type="button" onclick="toggleSidebar(true)" class="flex justify-center items-center w-10 h-10 rounded-xl border lg:hidden shrink-0 border-slate-200 text-navy-700 hover:bg-slate-50 transition active:scale-95" aria-label="Buka menu navigasi">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <div class="min-w-0">
              <h1 id="pageTitleText" class="text-lg font-semibold truncate font-display sm:text-xl text-navy-900">{{ $title ?? 'Statistik Voting' }}</h1>
              <div id="breadcrumbText" class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 truncate">
                <span class="truncate">Admin</span>
                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i> <span class="font-medium truncate text-primary-600">{{ $title ?? 'Statistik Voting' }}</span>
              </div>
            </div>
          </div>
          <!-- Profil Admin -->
          <div class="flex gap-3 items-center shrink-0">
            @if ($logo)
              <img src="{{ $logo }}" alt="Foto Admin" class="w-10 h-10 rounded-full border-2 object-cover border-primary-100">
            @else
              <div class="flex justify-center items-center w-10 h-10 rounded-full bg-primary-700 text-accent-400 font-bold border-2 border-primary-100 text-xs">
                {{ $inisial }}
              </div>
            @endif
            <div class="hidden text-right sm:block">
              <p class="text-sm font-semibold leading-tight text-navy-900">Febri Pratama</p>
              <p class="text-xs text-slate-500">Admin Pemilu</p>
            </div>
            <i data-lucide="chevron-down" class="hidden w-4 h-4 sm:block text-slate-400"></i>
          </div>
        </div>
      </header>
      <main id="main-content" class="overflow-hidden w-full pb-20 lg:pb-6">
        {{ $slot }}
      </main>

      {{-- Mobile Bottom Navigation Bar (Identik Desain Halaman Siswa) --}}
      <nav class="fixed right-0 bottom-0 left-0 z-40 px-2 py-2 border-t lg:hidden bg-navy-950/90 backdrop-blur-md border-slate-800 shadow-xl">
        <div class="flex gap-1 sm:gap-2 justify-around items-center w-full max-w-lg mx-auto overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
          {{-- Dashboard --}}
          <a href="{{ route('admin.dashboard') }}" wire:navigate
             class="inline-flex items-center gap-1.5 px-3 py-3 text-xs sm:text-sm justify-center font-bold transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[14px] fa-solid fa-chart-bar"></i>
            <span>Dashboard</span>
          </a>

          {{-- Kandidat --}}
          <a href="{{ route('admin.dashboard.kandidat') }}" wire:navigate
             class="inline-flex items-center gap-1.5 px-3 py-3 text-xs sm:text-sm justify-center font-bold transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard.kandidat') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[14px] fa-solid fa-check-to-slot"></i>
            <span>Kandidat</span>
          </a>

          {{-- Siswa --}}
          <a href="{{ route('admin.dashboard.siswa') }}" wire:navigate
             class="inline-flex items-center gap-1.5 px-3 py-3 text-xs sm:text-sm justify-center font-bold transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard.siswa') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[14px] fa-solid fa-users"></i>
            <span>Siswa</span>
          </a>

          {{-- Export --}}
          <a href="{{ route('admin.dashboard.export') }}" wire:navigate
             class="inline-flex items-center gap-1.5 px-3 py-3 text-xs sm:text-sm justify-center font-bold transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard.export') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[14px] fa-solid fa-file-export"></i>
            <span>Export</span>
          </a>

          {{-- Menu (Settings & Logout) --}}
          <button type="button" onclick="toggleMenuSheet(true)"
             class="inline-flex items-center gap-1.5 px-3 py-3 text-xs sm:text-sm justify-center font-bold transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard.settings') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[14px] fa-solid fa-bars"></i>
            <span>Menu</span>
          </button>
        </div>
      </nav>

      {{-- Mobile Bottom Sheet Menu (Settings & Logout) --}}
      <div id="menuSheetOverlay" onclick="toggleMenuSheet(false)" class="hidden fixed inset-0 z-50 bg-navy-950/70 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

      <div id="menuSheet" class="fixed right-0 bottom-0 left-0 z-50 p-4 bg-white rounded-t-3xl border-t shadow-2xl transition-transform duration-300 ease-out transform translate-y-full border-slate-200 lg:hidden">
        {{-- Handle bar --}}
        <div class="mx-auto mb-4 w-12 h-1.5 rounded-full bg-slate-300"></div>

        {{-- Header Info Akun / Admin --}}
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
          <div class="flex overflow-hidden justify-center items-center w-12 h-12 font-bold rounded-2xl bg-primary-700 font-display text-accent-400 shrink-0 shadow-sm">
            @if ($logo)
              <img src="{{ $logo }}" alt="Logo {{ $set->nama_sekolah }}" class="w-full h-full object-contain">
            @else
              {{ $inisial }}
            @endif
          </div>
          <div class="min-w-0 flex-1">
            <h4 class="text-base font-bold text-slate-800 leading-tight">Febri Pratama</h4>
            <p class="text-xs text-slate-500 font-medium">Admin Pemilu • <span class="text-primary-700 font-semibold">{{ $set->nama_sekolah }}</span></p>
          </div>
          <button type="button" onclick="toggleMenuSheet(false)" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>

        {{-- Menu Items: Settings & Logout --}}
        <div class="space-y-2.5">
          {{-- Settings --}}
          <a href="{{ route('admin.dashboard.settings') }}" wire:navigate onclick="toggleMenuSheet(false)"
             class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all {{ request()->routeIs('admin.dashboard.settings') ? 'bg-amber-50/80 border-amber-300 text-amber-900 shadow-sm' : 'bg-slate-50 border-slate-200/80 text-slate-700 hover:bg-slate-100' }}">
            <div class="flex justify-center items-center w-11 h-11 rounded-xl {{ request()->routeIs('admin.dashboard.settings') ? 'bg-amber-400 text-white' : 'bg-white text-slate-700 shadow-sm border border-slate-200/60' }} shrink-0">
              <i class="fa-solid fa-gear text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-bold text-slate-900">Pengaturan Sistem</p>
              <p class="text-xs text-slate-500 truncate">Judul pemilihan, jadwal, & logo sekolah</p>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
          </a>

          {{-- Logout --}}
          <a href="{{ route('admin.logout') }}" wire:navigate onclick="toggleMenuSheet(false)"
             class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-red-200/80 bg-red-50/60 text-red-700 transition-all hover:bg-red-100/70">
            <div class="flex justify-center items-center w-11 h-11 rounded-xl bg-white text-red-600 shadow-sm border border-red-200 shrink-0">
              <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-bold text-red-700">Keluar Akun</p>
              <p class="text-xs text-red-500/80 truncate">Akhiri sesi login admin pemilu</p>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-red-400"></i>
          </a>
        </div>

        {{-- Tutup Button --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
          <button type="button" onclick="toggleMenuSheet(false)" class="w-full py-2.5 text-center text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/sidebar.js') }}?v={{ filemtime(public_path('js/sidebar.js')) }}"></script> 
@stack('scripts') 
@livewireScripts
</body>
</html>