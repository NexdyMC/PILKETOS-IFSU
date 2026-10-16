<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Aplikasi E-Vote OSIS Untuk SMK INFORMATIKA SUMEDANG" />
  <link rel="canonical" href="https://evoteosisifsu-xyz.co.id">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  <title>Login Admin — E-Voting OSIS</title>
  
  <!-- link : CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.tailwind-config')
  
  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @include('partials.pwa-head')
</head>
<body class="bg-[#0F172A] min-h-screen flex flex-col justify-between items-center p-4 font-sans text-slate-800">
  <main class="py-6 my-auto w-full max-w-md">
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border-t-8 border-[#FACC15] relative overflow-hidden">
      
      <!-- Branding 3 Logo (SMK, IFSU [Tengah Besar], OSIS) -->
      <div class="flex flex-col items-center mb-2 text-center">

        <!-- Logo -->
        <div class="flex gap-5 justify-center items-center mb-6">
          <img src="{{ asset('assets/images/logo-osis.png') }}" alt="Logo SMK" class="p-2 w-auto h-20 bg-gray-100 rounded-full object-contain duration-300 drop-shadow-md hover:scale-110" width="80" height="80">
          <img src="{{ asset('assets/images/logo-smk.png') }}" alt="Logo IFSU" class="p-2 w-auto h-24 bg-gray-100 rounded-full object-contain duration-300 scale-105 drop-shadow-md hover:scale-110" width="100" height="100">
          <img src="{{ asset('assets/images/logo-mpk.png') }}" alt="Logo OSIS" class="p-2 w-auto h-20 bg-gray-100 rounded-full object-contain duration-300 drop-shadow-md hover:scale-110" width="80" height="80">
        </div>
        <h1 class="mb-2 text-2xl font-extrabold text-navy-900">Login Admin OSIS</h1>
        <p class="mb-4 text-xs text-center sm:text-sm text-slate-600"> Masukkan Username dan Password untuk mengakses panel admin. </p>
        <form id="form-login" class="space-y-4 w-full text-left">
          <div id="pesan-error" class="hidden"></div>
          <div>
            <label for="str-username" class="block mb-2 text-xs font-bold tracking-wider uppercase text-slate-700"> Username </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-user"></i>
              </div>
              <input type="text" id="str-username" name="username" placeholder="Masukkan username" required autocomplete="username" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:bg-white transition-all">
            </div>
          </div>
          <div>
            <label for="str-password" class="block mb-2 text-xs font-bold tracking-wider uppercase text-slate-700"> Password </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-key"></i>
              </div>
              <input type="password" id="str-password" name="password" placeholder="Masukkan password" required autocomplete="current-password" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:bg-white transition-all">
            </div>
          </div>
          
          <!-- Tombol Submit -->
          <button type="submit" name="login" class="w-full mt-2 py-3.5 px-4 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-extrabold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 group cursor-pointer">
            <span>Masuk ke Dashboard</span>
            <i class="text-[16px] transition-transform fa-solid fa-arrow-right group-hover:translate-x-1"></i>
          </button>
        </form>
        <div class="pt-6 mt-6 q border-t border-slate-100 w-full">
          <p class="text-xs text-slate-500 flex items-center justify-center gap-1.5">
            <i class="text-[16px] fa-solid fa-shield-halved text-emerald-600"></i>
            <span>Akses khusus panitia dan administrator pemilihan.</span>
          </p>
        </div>
      </div>
    </div>
    <p class="pt-6 text-center text-slate-400 text-xs">&copy; 2026 Febri Pratama — All Rights Reserved.</p>
  </main>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $(document).ready(function() {
      $('#form-login').on('submit', function(e) {
        e.preventDefault();
        let username = $('#str-username').val();
        let password = $('#str-password').val();
        let btnSubmit = $(this).find('button');
        let originalText = btnSubmit.html();
        btnSubmit.prop('disabled', true).html('<span>Memproses...</span>');
        $('#pesan-error').addClass('hidden').empty();

        $.ajax({
          url: '{{ route("admin.check") }}',
          method: 'POST',
          data: {
            username: username,
            password: password
          },
          success: function(res) {
            if (res.success) {
              window.location.href = res.redirect;
            }
          },
          error: function(xhr) {
            btnSubmit.prop('disabled', false).html(originalText);
            let message = 'Terjadi kesalahan, silakan coba lagi.';
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              let errors = xhr.responseJSON.errors;
              let firstErr = Object.values(errors)[0];
              message = Array.isArray(firstErr) ? firstErr[0] : firstErr;
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
            }

            let alertHtml = `
              <div class="flex items-center justify-center gap-2 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold p-3 rounded-xl mb-4 text-center">
                  <i class="text-[14px] text-red-500 fa-solid fa-circle-exclamation shrink-0"></i>
                  <span>${message}</span>
              </div>`;
            $('#pesan-error').removeClass('hidden').html(alertHtml);
          }
        });
      });
    });
  </script>
</body>
</html>