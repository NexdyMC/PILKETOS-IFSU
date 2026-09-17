<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — E-Voting OSIS</title>
    
    <!-- link : CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script href="../assets/script/tailwind.config.js"></script>
    
    <!-- CDN : Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
</head>

<body class="bg-[#0F172A] min-h-screen flex flex-col justify-between items-center p-4 font-sans text-slate-800">
    <main class="w-full max-w-md py-6 my-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border-t-8 border-[#FACC15] relative overflow-hidden">
            <!-- Branding 3 Logo (SMK, IFSU [Tengah Besar], OSIS) -->
            <div class="flex flex-col items-center mb-2 text-center">
                <div class="flex items-center justify-center gap-5 mb-6">
                
                    <!-- 1. Logo SMK (Kiri - Ukuran Sedang) -->
                    <img src="../assets/images/logo-osis.png" alt="Logo SMK"
                        class="object-contain w-auto h-20 p-2 duration-300 bg-gray-100 rounded-full drop-shadow-md hover:scale-110">

                    <!-- 2. Logo IFSU (Tengah - Ukuran Lebih Besar) -->
                    <img src="../assets/images/logo-smk.png" alt="Logo IFSU"
                        class="object-contain w-auto h-24 p-2 duration-300 scale-105 bg-gray-100 rounded-full drop-shadow-md hover:scale-110">

                    <!-- 3. Logo OSIS (Kanan - Ukuran Sedang) -->
                    <img src="../assets/images/logo-mpk.png" alt="Logo OSIS"
                        class="object-contain w-auto h-20 p-2 duration-300 bg-gray-100 rounded-full drop-shadow-md hover:scale-110">
                </div>
                

                <h1 class="mb-2 text-2xl font-extrabold">
                    Login Pemilihan Osis
                </h1>
                <p class="mb-2 text-xs text-center sm:text-sm text-slate-600">
                    Masukkan Token Anda untuk memulai votin.
                </p>
                <form id="form-login" class="w-full space-y-5">
                    <div id="pesan-error" class="hidden"></div>
                    <label for="token" class="block mb-2 text-xs font-bold tracking-wider text-left uppercase text-slate-700">
                        Username
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" id="str-username" name="token" placeholder="Masukan username" required autocomplete="off"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-mono font-bold tracking-wider placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:bg-white transition-all uppercase">
                    </div>
                    
                    <label for="token" class="block mb-2 text-xs font-bold tracking-wider text-left uppercase text-slate-700">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input type="text" id="str-password" name="token" placeholder="Masukan Password" required autocomplete="off"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-mono font-bold tracking-wider placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:bg-white transition-all uppercase">
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" name="login"
                    class="w-full py-3.5 px-4 bg-yellow-400 hover:bg-yellow-500 text-white font-display font-extrabold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 group">
                        <span>Masuk ke Bilik Suara</span>
                        <i class="text-[16px] transition-transform fa-solid fa-arrow-right group-hover:translate-x-1"></i>
                    </button>
                </form>

                <div class="pt-6 text-center border-t border-slate-100">
                    <p class="text-xs text-slate-500 flex items-center justify-center gap-1.5">
                        <i class="text-[16px] fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>Satu token untuk akses memilih seluruh siswa.</span>
                    </p>
                </div>
            </div>
        </div>
        <p class="pt-6 text-center text-slate-300">&copy; 2026 Febri Pratama — All Right Reserved.</p>
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

                btnSubmit.prop('disabled', true).text('Memproses...');
                $('#pesan-error').text('');
                let rows = '';
                $.ajax({
                    url: '{{ route("admin.check") }}',
                    method: 'POST',
                    data: { username: username, password: password },
                    success: function(res) {
                        if (res.success) {
                            // redirect ke halaman voting
                            window.location.href = res.redirect;
                        }
                    },
                    error: function(xhr) {
                        btnSubmit.prop('disabled', false).text('Masuk');
                        if (xhr.status === 422) {
                            // error validasi (token kosong)
                            let errors = xhr.responseJSON.errors;
                            rows += `
                                <div class="flex items-center justify-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold p-3.5 rounded-xl mb-4 shadow-sm text-center">
                                    <i class="text-[16px] text-red-500 fa-solid fa-circle-exclamation shrink-0"></i>
                                    <span>${errors.token[0]}</span>
                                </div>`
                            $('#pesan-error').removeClass('hidden');
                            $('#pesan-error').html(rows);
                        } else {
                            rows += `
                            <div class="flex items-center justify-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold p-3.5 rounded-xl mb-4 shadow-sm text-center">
                                <i class="text-[16px] text-red-500 fa-solid fa-circle-exclamation shrink-0"></i>
                                <span>${xhr.responseJSON.message}</span>
                            </div>`
                            $('#pesan-error').removeClass('hidden');
                            $('#pesan-error').html(rows);
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>