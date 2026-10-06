<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{-- WAJIB: dibaca oleh $.ajaxSetup untuk header X-CSRF-TOKEN --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'E-Vote OSIS')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  @yield('cdn')
</head>
<body class="bg-slate-100">
  {{-- Navigation : Desktop Top --}}
  <header class="sticky top-0 z-40 border-b bg-navy-950/90 backdrop-blur-md border-slate-800">
    <div class="flex items-center justify-between max-w-6xl px-6 py-4 mx-auto">

      <div class="z-10 flex items-center gap-3">
        <div class="flex items-center justify-center w-12 h-12 text-lg font-bold bg-yellow-400 shadow-md rounded-xl text-navy-950">
          <i class="text-[24px] fa-solid fa-check-to-slot"></i>
        </div>
        <div>
          <h1 class="text-xl font-bold tracking-wide text-white uppercase">E-Vote <span class="text-brand-yellow">OSIS</span></h1>
          <p class="hidden text-xs text-slate-400 md:block">SMK Informatika Sumedang</p>
          <p class="block text-xs uppercase text-slate-400 md:hidden">{{ session('nama') ?? 'User' }}</p>
        </div>
      </div>

      <div class="absolute left-0 right-0 items-center justify-center hidden gap-4 md:flex">
        <a href="{{ route('siswa.voting') }}"
          class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold transition-all
          {{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
          <i class="text-[16px] fa-solid fa-check-to-slot"></i>Voting
        </a>
        <a href="{{ route('siswa.hasil') }}"
          class="inline-flex items-center gap-1.5 px-4 py-3 text-md font-bold transition-all
          {{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
          <i class="fa-solid fa-chart-line"></i>Hasil
        </a>
      </div>

      <div class="z-10 flex items-center gap-3">
        <div class="hidden text-right md:block">
          <p class="text-sm font-semibold text-white uppercase">{{ session('nama') ?? 'Users' }}</p>
          <p class="text-xs uppercase text-slate-400">{{ session('kelas') ?? 'Kelas' }}</p>
        </div>
        <a href="{{ route('siswa.logout') }}"
          class="flex items-center p-2 text-sm font-semibold text-white transition-colors bg-red-500 rounded-lg hover:bg-red-600 md:p-3 md:text-base">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span class="hidden ml-2 sm:block">Logout</span>
        </a>
      </div>
    </div>
  </header>

  {{-- Navigation : Mobile Bottom --}}
  <nav class="fixed bottom-0 left-0 right-0 z-40 px-2 py-2 border-t md:hidden bg-navy-950/90 backdrop-blur-md border-slate-800">
    <div class="flex items-center justify-center w-full gap-2">
      <a href="{{ route('siswa.voting') }}" class="inline-flex items-center gap-1.5 px-4 py-4 text-md w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
        <i class="text-[16px] fa-solid fa-check-to-slot"></i>Voting
      </a>
      <a href="{{ route('siswa.hasil') }}" class="inline-flex items-center gap-1.5 px-4 py-4 text-md w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
        <i class="fa-solid fa-chart-line"></i>Hasil
      </a>
    </div>
  </nav>

  {{-- Content utama --}}
  <main class="flex flex-col justify-center flex-1 w-full max-w-6xl px-6 py-8 mx-auto space-y-3 sm:px-6">
    @yield('content')
  </main>

  {{-- ============ MODAL 1 : Detail Visi & Misi ============ --}}
  <div id="popupModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
    class="fixed inset-0 z-50 items-center justify-center hidden p-6 bg-slate-900/80 backdrop-blur-sm">
    <div class="relative flex flex-col w-full max-w-lg max-h-[90vh] overflow-y-auto bg-white border-2 shadow-2xl rounded-3xl border-slate-100">

      <button id="closeBtn" type="button" aria-label="Tutup detail kandidat"
        class="absolute z-10 flex items-center justify-center rounded-full shadow top-4 right-4 w-9 h-9 text-slate-500 bg-white/90 hover:text-slate-700 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="text-[20px] fa-solid fa-xmark"></i>
      </button>

      <div class="relative flex items-center justify-center overflow-hidden border-b aspect-video bg-slate-50 border-slate-100 shrink-0">
        <img id="modalImg" src="" alt="" class="object-cover w-full h-full">
      </div>

      <div class="p-4 sm:p-6">
        <h3 id="modalTitle" class="mb-4 text-3xl font-bold text-center text-slate-900"></h3>

        <div class="h-48 space-y-4 overflow-y-auto text-slate-600">
          <div class="p-4 border-2 border-yellow-200 bg-yellow-50 rounded-xl">
            <h4 class="flex items-center gap-1 mb-1 text-lg font-bold uppercase text-slate-900">
              <i class="text-[20px] text-yellow-600 fa-solid fa-compass"></i> Visi
            </h4>
            <div id="modalVisi" class="text-sm leading-relaxed text-slate-600"></div>
          </div>
          <div class="p-4 border-2 border-blue-200 bg-blue-50 rounded-xl">
            <h4 class="flex items-center gap-1 mb-1 text-lg font-bold uppercase text-slate-900">
              <i class="text-[20px] text-blue-600 fa-solid fa-bullseye"></i> Misi
            </h4>
            <div id="modalMisi" class="text-sm leading-relaxed text-slate-600"></div>
          </div>
        </div>

        <div class="grid gap-3 pt-4 mt-4 border-t border-slate-100">
          {{-- ID unik: hanya satu #modalVoteBtn di seluruh halaman --}}
          <button id="modalVoteBtn" type="button"
            class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-blue-600 transition-colors bg-white border-2 border-blue-600 rounded-xl hover:bg-blue-600 hover:text-white">
            <i class="text-[16px] fa-solid fa-check-to-slot"></i> Pilih Kandidat Ini
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- ============ MODAL 2 : Dialog E-Vote (pengganti SweetAlert) ============ --}}
  <div id="evDialog" role="alertdialog" aria-modal="true" aria-labelledby="evTitle" aria-describedby="evText"
    class="fixed inset-0 z-[60] items-center justify-center hidden p-6 bg-slate-900/80 backdrop-blur-sm">
    <div id="evBox" class="w-full max-w-sm p-6 text-center transition-all duration-200 scale-95 bg-white border-2 shadow-2xl opacity-0 rounded-3xl border-slate-100">
      <div id="evIcon" class="flex items-center justify-center w-20 h-20 mx-auto mb-4 text-3xl text-white shadow-md rounded-2xl bg-gradient-to-br"></div>
      <h3 id="evTitle" class="text-2xl font-extrabold text-slate-800"></h3>
      <p id="evText" class="mt-2 text-sm leading-relaxed text-slate-600"></p>

      <div id="evProgressWrap" class="hidden mt-5">
        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
          <div id="evProgress" class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-300" style="width:100%"></div>
        </div>
      </div>

      <div id="evActions" class="grid grid-cols-2 gap-3 mt-6">
        <button id="evCancel" type="button"
          class="px-5 py-3 text-sm font-semibold transition-colors bg-white border-2 rounded-xl text-slate-600 border-slate-300 hover:bg-slate-100">Batal</button>
        <button id="evConfirm" type="button"
          class="px-5 py-3 text-sm font-semibold text-white transition-colors bg-blue-600 border-2 border-blue-600 rounded-xl hover:bg-blue-700">OK</button>
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