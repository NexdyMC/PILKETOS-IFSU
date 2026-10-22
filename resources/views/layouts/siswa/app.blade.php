@php
  $set = \App\Models\Settings::ambil();
  $logo = $set->logoUrl();
  $token = session('token');
  $currentUser = $token ? \App\Models\Siswa::where('token', $token)->first() : null;
  $isGuru = strcasecmp(trim((string) ($currentUser?->kelas ?? session('kelas'))), 'guru') === 0;
  $sudahVoting = ($currentUser?->status ?? session('status', 0)) == 1;
  $canViewHasil = $isGuru || $sudahVoting;
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
  
  <title>@yield('title', 'E-Vote OSIS')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  @yield('cdn')
  @include('partials.pwa-head')
</head>
<body class="bg-slate-100">
  {{-- Navigation : Desktop Top --}}
  <header class="sticky top-0 z-40 border-b bg-navy-950/90 backdrop-blur-md border-slate-800">
    <div class="flex justify-between items-center px-6 py-4 mx-auto max-w-6xl">

      <div class="flex z-10 gap-3 items-center">
        @if ($logo)
        <img  src="{{ $logo ? asset('storage/logo/' . $logo) : asset('assets/images/logo-smk.png') }}" alt="Logo {{ $set->nama_sekolah }}" width="48" height="48" class="w-12 h-12 object-contain">
        @else
          <div class="flex overflow-hidden justify-center items-center w-12 h-12 bg-yellow-400 rounded-xl shadow-md shrink-0 text-navy-950">
            <i class="text-[24px] text-white fa-solid fa-check-to-slot"></i>
          </div>
          @endif
        <div>
          <h3 class="text-xl font-bold tracking-wide uppercase text-white">E-Vote <span class="text-brand-yellow">OSIS</span></h3>
          <p class="hidden text-xs text-slate-400 md:block">{{ $set->nama_sekolah }}</p>
          <p class="block text-xs uppercase truncate text-slate-400 md:hidden">{{ session('nama') ?? 'User' }}</p>
        </div>
      </div>

      <div class="hidden absolute right-0 left-0 gap-4 justify-center items-center md:flex">
        {{-- Tombol Voting --}}
        @if ($sudahVoting && !$isGuru)
          <a href="{{ route('siswa.hasil') }}" title="Anda sudah memberikan suara"
            class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold transition-all text-slate-400 hover:text-slate-200">
            <i class="text-[16px] fa-solid fa-circle-check text-emerald-400"></i>Voting
          </a>
        @else
          <a href="{{ route('siswa.voting') }}"
            class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold transition-all
            {{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="text-[16px] fa-solid fa-check-to-slot"></i>Voting
          </a>
        @endif

        {{-- Tombol Hasil --}}
        @if ($canViewHasil)
          <a href="{{ route('siswa.hasil') }}"
            class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold transition-all
            {{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
            <i class="fa-solid fa-chart-line"></i>Hasil
          </a>
        @else
          <span title="Lakukan voting terlebih dahulu untuk melihat hasil pemilihan"
            class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold text-slate-500 bg-slate-800/40 border border-slate-700/50 rounded-lg cursor-not-allowed select-none opacity-60">
            <i class="fa-solid fa-lock text-xs text-slate-500"></i>Hasil
          </span>
        @endif
      </div>

      <div class="flex z-10 gap-3 items-center">
        <div class="hidden text-right md:block">
          <p class="text-sm font-semibold uppercase text-white">{{ session('nama') ?? 'Users' }}</p>
          <p class="text-xs uppercase text-slate-400">{{ session('kelas') ?? 'Kelas' }}</p>
        </div>
        <a href="{{ route('siswa.logout') }}"
          class="flex items-center p-2 text-sm font-semibold text-white bg-red-500 rounded-lg transition-colors hover:bg-red-600 md:p-3 md:text-base">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span class="hidden ml-2 sm:block">Logout</span>
        </a>
      </div>
    </div>
  </header>

  {{-- Navigation : Mobile Bottom --}}
  <nav class="fixed right-0 bottom-0 left-0 z-40 px-2 py-2 border-t md:hidden bg-navy-950/90 backdrop-blur-md border-slate-800">
    <div class="flex gap-2 justify-center items-center w-full">
      @if ($sudahVoting && !$isGuru)
        <a href="{{ route('siswa.hasil') }}" class="inline-flex items-center gap-1.5 px-4 py-3.5 text-sm w-1/2 justify-center font-bold transition-all text-slate-400">
          <i class="text-[14px] fa-solid fa-circle-check text-emerald-400"></i>Voting
        </a>
      @else
        <a href="{{ route('siswa.voting') }}" class="inline-flex items-center gap-1.5 px-4 py-3.5 text-sm w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
          <i class="text-[14px] fa-solid fa-check-to-slot"></i>Voting
        </a>
      @endif

      @if ($canViewHasil)
        <a href="{{ route('siswa.hasil') }}" class="inline-flex items-center gap-1.5 px-4 py-3.5 text-sm w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
          <i class="fa-solid fa-chart-line"></i>Hasil
        </a>
      @else
        <span title="Lakukan voting terlebih dahulu untuk melihat hasil pemilihan"
          class="inline-flex items-center gap-1.5 px-4 py-3.5 text-sm w-1/2 justify-center font-bold text-slate-500 bg-slate-800/40 border border-slate-700/50 rounded-lg cursor-not-allowed select-none opacity-60">
          <i class="fa-solid fa-lock text-xs text-slate-500"></i>Hasil
        </span>
      @endif
    </div>
  </nav>

  {{-- Content utama --}}
  <main class="flex flex-col flex-1 justify-center px-6 py-8 mx-auto space-y-3 w-full max-w-6xl sm:px-6 min-h-screen">
    @if (session('error'))
      <div class="p-4 text-sm text-red-700 bg-red-100 rounded-2xl border border-red-200 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif
    @if (session('info'))
      <div class="p-4 text-sm text-blue-700 bg-blue-100 rounded-2xl border border-blue-200 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-base shrink-0"></i>
        <span>{{ session('info') }}</span>
      </div>
    @endif
    @yield('content')
  </main>


  {{-- ============ MODAL 2 : Dialog E-Vote (pengganti SweetAlert) ============ --}}
  <div id="evDialog" role="alertdialog" aria-modal="true" aria-labelledby="evTitle" aria-describedby="evText"
    class="fixed inset-0 z-[60] items-center justify-center hidden p-6 bg-slate-900/80 backdrop-blur-sm">
    <div id="evBox" class="p-6 w-full max-w-sm text-center bg-white rounded-3xl border-2 transition-all duration-200 scale-95 shadow-2xl opacity-0 border-slate-100">
      <div id="evIcon" class="flex justify-center items-center mx-auto mb-4 w-20 h-20 text-3xl text-white bg-gradient-to-br rounded-2xl shadow-md"></div>
      <h3 id="evTitle" class="text-2xl font-extrabold text-slate-800"></h3>
      <p id="evText" class="mt-2 text-sm leading-relaxed text-slate-600"></p>

      <div id="evProgressWrap" class="hidden mt-5">
        <div class="overflow-hidden h-2 rounded-full bg-slate-100">
          <div id="evProgress" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-300 rounded-full" style="width:100%"></div>
        </div>
      </div>

      <div id="evActions" class="grid grid-cols-2 gap-3 mt-6">
        <button id="evCancel" type="button"
          class="px-5 py-3 text-sm font-semibold bg-white rounded-xl border-2 transition-colors text-slate-600 border-slate-300 hover:bg-slate-100">Batal</button>
        <button id="evConfirm" type="button"
          class="px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl border-2 border-blue-600 transition-colors hover:bg-blue-700">OK</button>
      </div>
    </div>
  </div>

  {{-- footer --}}
  <footer class="py-6 text-sm text-center border-t md:py-4 bg-slate-950 border-slate-900 text-slate-500">
    &copy; {{ date('Y') }} Febri Pratama — All rights reserved.
  </footer>

  {{-- script & library (SweetAlert2 sudah dihapus) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    /**
     * EVoteDialog : dialog modern bergaya E-Vote OSIS
     * EVoteDialog.confirm({title, text, confirmText, cancelText}) -> Promise<boolean>
     * EVoteDialog.loading(title, text)
     * EVoteDialog.success({title, text, seconds}) -> Promise (resolve saat hitung mundur selesai)
     * EVoteDialog.error({title, text})
     * EVoteDialog.close()
     */
    window.EVoteDialog = (function ($) {
      const $root = $('#evDialog'), $box = $('#evBox'), $icon = $('#evIcon');
      const $title = $('#evTitle'), $text = $('#evText');
      const $actions = $('#evActions'), $cancel = $('#evCancel'), $confirm = $('#evConfirm');
      const $pWrap = $('#evProgressWrap'), $bar = $('#evProgress');

      const THEME = {
        question: { icon: 'fa-check-to-slot', grad: 'from-amber-500 to-amber-300' },
        loading:  { icon: 'fa-spinner fa-spin', grad: 'from-blue-600 to-blue-400' },
        success:  { icon: 'fa-check', grad: 'from-emerald-500 to-emerald-300' },
        error:    { icon: 'fa-xmark', grad: 'from-red-500 to-red-400' }
      };
      let closable = true, resolver = null, timer = null;

      function render(type, title, text) {
        const t = THEME[type];
        $icon.attr('class', 'flex items-center justify-center w-20 h-20 mx-auto mb-4 text-3xl text-white shadow-md rounded-2xl bg-gradient-to-br ' + t.grad)
             .html('<i class="fa-solid ' + t.icon + '"></i>');
        $title.text(title || '');
        $text.text(text || '');
        $pWrap.addClass('hidden');
        clearInterval(timer);
      }

      function open() {
        $root.removeClass('hidden').addClass('flex');
        requestAnimationFrame(function () {
          $box.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        });
      }

      function close() {
        clearInterval(timer);
        $box.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        $root.addClass('hidden').removeClass('flex');
        closable = true;
      }

      function confirm(o) {
        return new Promise(function (resolve) {
          resolver = resolve;
          closable = true;
          render('question', o.title, o.text);
          $actions.removeClass('hidden').addClass('grid');
          $cancel.removeClass('hidden').text(o.cancelText || 'Batal');
          $confirm.removeClass('hidden').text(o.confirmText || 'Ya');
          open();
          $confirm.trigger('focus');
        });
      }

      function loading(title, text) {
        closable = false; resolver = null;
        render('loading', title, text);
        $actions.addClass('hidden').removeClass('grid');
        open();
      }

      function error(o) {
        closable = true; resolver = null;
        render('error', o.title || 'Gagal!', o.text);
        $actions.removeClass('hidden').addClass('grid');
        $cancel.addClass('hidden');
        $confirm.removeClass('hidden').text('Tutup');
        open();
      }

      function success(o) {
        return new Promise(function (resolve) {
          closable = false; resolver = null;
          const total = o.seconds || 5;
          let left = total;
          render('success', o.title || 'Berhasil!', '');
          $text.html(''); // isi manual agar countdown bisa diperbarui
          const $msg = $('<span>').text(o.text || '');
          const $cd  = $('<b>').text(left);
          $text.append($msg, '<br><br>Halaman akan dialihkan dalam ', $cd, ' detik.');
          $actions.addClass('hidden').removeClass('grid');
          $pWrap.removeClass('hidden');
          $bar.css({ transition: 'none', width: '100%' });
          open();
          setTimeout(function () {
            $bar.css({ transition: 'width ' + total + 's linear', width: '0%' });
          }, 30);
          timer = setInterval(function () {
            left--;
            $cd.text(Math.max(left, 0));
            if (left <= 0) { clearInterval(timer); resolve(); }
          }, 1000);
        });
      }

      $confirm.on('click', function () {
        if (resolver) { const r = resolver; resolver = null; close(); r(true); }
        else close();
      });
      $cancel.on('click', function () {
        if (resolver) { const r = resolver; resolver = null; close(); r(false); }
        else close();
      });
      $root.on('click', function (e) {
        if (e.target === this && closable) $cancel.hasClass('hidden') ? $confirm.click() : $cancel.click();
      });
      $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && closable && $root.hasClass('flex')) {
          $cancel.hasClass('hidden') ? $confirm.click() : $cancel.click();
        }
      });

      return {
        confirm: confirm, loading: loading, success: success, error: error, close: close,
        isOpen: function () { return $root.hasClass('flex'); }
      };
    })(jQuery);
  </script>
  @yield('script')
</body>
</html>