<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div id="siswa-app" class="p-4 space-y-8 sm:p-8"
     data-url-list="{{ url('/api/admin/siswa') }}"
     data-url-store="{{ route('admin.siswa.store') }}"
     data-url-reset="{{ route('admin.siswa.reset') }}"
     data-csrf="{{ csrf_token() }}">

    <div class="mb-6">
        <h2 class="text-2xl font-semibold font-display text-navy-900">Statistik Voting</h2>
        <p class="mt-1 text-sm text-slate-500">Pantau perolehan suara sementara dan tingkat partisipasi pemilih secara real-time.</p>
    </div>

    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>

    <div class="grid gap-8 lg:grid-cols-3">

        <div class="p-6 overflow-x-auto bg-white border shadow-sm lg:col-span-2 rounded-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold font-display">Data Pemilih (Siswa)</h3>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="btnTambahSiswa"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition rounded-xl bg-primary-700 hover:bg-primary-600">
                        <i class="fa-solid fa-user-plus"></i> Tambah Siswa
                    </button>
                    <button type="button" id="btnResetVoting"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 transition border border-red-200 rounded-xl bg-red-50 hover:bg-red-100">
                        <i class="fa-solid fa-rotate-left"></i> Reset Voting
                    </button>
                </div>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="p-3 rounded-l-lg">Token</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody id="tabel-siswa" wire:ignore class="divide-y divide-slate-100">

                </tbody>
            </table>
        </div>
    </div>

    <style>
        /* ===== Popup modern (SweetAlert2 di-restyle) ===== */
        .swal2-container.sw-backdrop.swal2-backdrop-show {
            background: rgb(15 23 42 / .55);
            backdrop-filter: blur(4px);
        }
        .swal2-popup.sw-popup {
            width: 28rem;
            max-width: calc(100vw - 2rem);
            padding: 0;
            border-radius: 1.5rem;
            text-align: left;
            box-shadow: 0 30px 70px -15px rgb(15 23 42 / .45);
        }
        .swal2-popup.sw-popup .swal2-html-container { margin: 0; padding: 0; overflow: visible; text-align: left; }
        .swal2-popup.sw-popup .swal2-actions { width: 100%; margin: 0; padding: 1.25rem 1.5rem 1.5rem; gap: .75rem; flex-wrap: nowrap; }
        .swal2-popup.sw-popup .swal2-validation-message {
            margin: .75rem 1.5rem 0; padding: .65rem .9rem; border-radius: .75rem;
            background: #fef2f2; color: #b91c1c; font-size: .8rem; justify-content: flex-start;
        }
        .swal2-popup.sw-popup .swal2-validation-message::before { background: #dc2626; }

        .sw-btn {
            flex: 1; padding: .75rem 1rem; border: 1px solid transparent; border-radius: .9rem;
            font-size: .875rem; font-weight: 600; cursor: pointer; transition: background .15s, box-shadow .15s, opacity .15s;
        }
        .sw-btn:focus-visible { outline: none; box-shadow: 0 0 0 4px rgb(59 130 246 / .25); }
        .sw-btn:disabled { opacity: .45; cursor: not-allowed; }
        .sw-btn-primary { background: #1d4ed8; color: #fff; }
        .sw-btn-primary:hover:not(:disabled) { background: #1e40af; }
        .sw-btn-danger { background: #dc2626; color: #fff; }
        .sw-btn-danger:hover:not(:disabled) { background: #b91c1c; }
        .sw-btn-danger:focus-visible { box-shadow: 0 0 0 4px rgb(220 38 38 / .25); }
        .sw-btn-ghost { background: #fff; color: #334155; border-color: #e2e8f0; }
        .sw-btn-ghost:hover { background: #f8fafc; }

        .sw-in  { animation: swIn .22s ease-out; }
        .sw-out { animation: swOut .15s ease-in forwards; }
        @keyframes swIn  { from { opacity: 0; transform: translateY(14px) scale(.96); } to { opacity: 1; transform: none; } }
        @keyframes swOut { to   { opacity: 0; transform: translateY(8px) scale(.97); } }

        .swal2-popup.sw-toast {
            padding: .7rem 1rem; border-radius: 1rem;
            box-shadow: 0 12px 30px -8px rgb(15 23 42 / .3);
        }
        .swal2-popup.sw-toast .swal2-title { font-size: .875rem; font-weight: 600; color: #0f172a; }
    </style>
</div>

@script
<script>
(function () {
    const $root = $('#siswa-app');
    if (!$root.length) return;

    const NS = '.siswa';
    const cfg = {
        list:  $root.attr('data-url-list'),
        store: $root.attr('data-url-store'),
        reset: $root.attr('data-url-reset'),
        csrf:  $root.attr('data-csrf'),
    };
    const TOKEN_CHARS = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    // lepas listener & timer lama (aman untuk wire:navigate)
    $(document).off(NS);
    if (window.__siswaTimer) clearInterval(window.__siswaTimer);

    let siswaList = [];

    /* ---------- popup modern ---------- */
    const baseModal = {
        buttonsStyling: false,
        reverseButtons: true,
        focusConfirm: false,
        showClass: { popup: 'sw-in' },
        hideClass: { popup: 'sw-out' },
    };
    const Modal = Swal.mixin(Object.assign({}, baseModal, {
        customClass: {
            container: 'sw-backdrop', popup: 'sw-popup',
            confirmButton: 'sw-btn sw-btn-primary', cancelButton: 'sw-btn sw-btn-ghost',
        },
    }));
    const ModalDanger = Swal.mixin(Object.assign({}, baseModal, {
        customClass: {
            container: 'sw-backdrop', popup: 'sw-popup',
            confirmButton: 'sw-btn sw-btn-danger', cancelButton: 'sw-btn sw-btn-ghost',
        },
    }));
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timerProgressBar: true,
        customClass: { popup: 'sw-toast' },
    });

    const toast = (icon, title, timer) => Toast.fire({ icon, title, timer: timer || 2500 });

    const header = (iconCls, tone, title, desc) => `
        <div class="px-6 pt-6 pb-4">
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-2xl shrink-0 ${tone}">
                    <i class="text-lg ${iconCls}"></i>
                </div>
                <div class="min-w-0 pt-0.5">
                    <h3 class="text-lg font-semibold leading-tight text-slate-900">${title}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">${desc}</p>
                </div>
            </div>
        </div>`;

    const INPUT = 'w-full px-4 py-2.5 text-sm text-slate-900 bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100';
    const LABEL = 'block mb-1.5 text-sm font-medium text-slate-700';

    /* ---------- helpers ---------- */
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
    const aktif = () => $('#siswa-app').length > 0;

    // $.ajax dibungkus Promise: resolve(json) / reject(jqXHR)
    function request(url, method, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url, method, data, dataType: 'json',
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'X-Requested-With': 'XMLHttpRequest' }
            }).done(resolve).fail(reject);
        });
    }

    function pesanError(xhr) {
        const res = (xhr && xhr.responseJSON) || {};
        return res.errors
            ? Object.values(res.errors).flat().join(' ')
            : (res.message || 'Terjadi kesalahan pada server.');
    }

    function tokenAcak() {
        let t = '';
        for (let i = 0; i < 4; i++) t += TOKEN_CHARS.charAt(Math.floor(Math.random() * TOKEN_CHARS.length));
        return t;
    }

    function salinToken(token, $icon) {
        navigator.clipboard.writeText(token).then(function () {
            toast('success', 'Token disalin: ' + token, 1500);
            if ($icon && $icon.length) {
                $icon.removeClass('fa-regular fa-copy text-slate-400').addClass('fa-solid fa-check text-emerald-500');
                setTimeout(function () {
                    $icon.removeClass('fa-solid fa-check text-emerald-500').addClass('fa-regular fa-copy text-slate-400');
                }, 1500);
            }
        }).catch(function (err) {
            console.error('Gagal menyalin token:', err);
            toast('error', 'Tidak bisa menyalin token.');
        });
    }

    /* ---------- tabel siswa ---------- */
    function loadSiswa() {
        if (!aktif()) return;
        $.ajax({
            url: cfg.list,
            method: 'GET',
            success: function (data) {
                siswaList = data;
                let rows = '';
                $.each(data, function (i, s) {
                    const status = s.status == 1
                        ? '<span class="px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded">Sudah</span>'
                        : '<span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded">Belum</span>';
                    rows += `<tr class="hover:bg-slate-50">
                        <td class="p-3">
                            <button type="button" class="inline-flex items-center gap-2 font-mono font-medium text-blue-600 transition-colors btn-copy-token hover:text-blue-800" data-token="${esc(s.token)}"> ${esc(s.token)}<i class="text-xs fa-regular fa-copy text-slate-400"></i></button>
                        </td>
                        <td class="p-3 font-medium text-navy-900">${esc(s.nama)}</td>
                        <td class="p-3 font-medium text-navy-900">${esc(s.kelas)}</td>
                        <td class="p-3">${status}</td>
                    </tr>`;
                });
                $('#tabel-siswa').html(rows);
            },
            error: function (xhr) {
                console.error('Gagal ambil data siswa:', xhr);
            }
        });
    }

    /* ---------- tambah siswa ---------- */
    function tambahSiswa() {
        Modal.fire({
            html: header('fa-solid fa-user-plus', 'bg-primary-50 text-primary-700', 'Tambah Siswa',
                         'Daftarkan pemilih baru. Token dipakai siswa untuk login saat voting.') + `
                <div class="px-6 pb-1 space-y-4">
                    <div>
                        <label class="${LABEL}" for="swNama">Nama Siswa</label>
                        <input id="swNama" type="text" maxlength="100" autocomplete="off" placeholder="cth. Aditya Pratama" class="${INPUT}">
                    </div>
                    <div>
                        <label class="${LABEL}" for="swKelas">Kelas</label>
                        <input id="swKelas" type="text" maxlength="50" autocomplete="off" placeholder="cth. 11 RPL 1" class="${INPUT}">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-medium text-slate-700" for="swToken">Token <span class="font-normal text-slate-400">(opsional)</span></label>
                            <button type="button" id="swAcak" class="text-xs font-semibold text-primary-700 hover:underline">
                                <i class="mr-1 fa-solid fa-shuffle"></i>Acak
                            </button>
                        </div>
                        <input id="swToken" type="text" maxlength="8" autocomplete="off" spellcheck="false"
                               placeholder="Kosongkan agar dibuat otomatis"
                               class="${INPUT} font-mono tracking-widest">
                        <p class="mt-1.5 text-xs text-slate-500">4-8 karakter, hanya huruf dan angka (otomatis huruf besar).</p>
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: () => { $('#swNama').trigger('focus'); },
            preConfirm: () => {
                const nama  = $('#swNama').val().trim();
                const kelas = $('#swKelas').val().trim();
                const token = $('#swToken').val().trim();

                if (!nama || !kelas) {
                    Swal.showValidationMessage('Nama dan kelas wajib diisi.');
                    return false;
                }
                if (token && !/^[A-Z0-9]{4,8}$/.test(token)) {
                    Swal.showValidationMessage('Token harus 4-8 karakter, hanya huruf dan angka.');
                    return false;
                }
                return request(cfg.store, 'POST', { nama, kelas, token })
                    .then((res) => ({ res, nama }))
                    .catch((xhr) => { Swal.showValidationMessage(pesanError(xhr)); return false; });
            }
        }).then((result) => {
            if (!result.isConfirmed || !result.value) return;
            const { res, nama } = result.value;
            loadSiswa();

            Modal.fire({
                html: header('fa-solid fa-check', 'bg-emerald-50 text-emerald-600', 'Siswa ditambahkan',
                             'Catat atau salin token ini untuk <b>' + esc(nama) + '</b>.') + `
                    <div class="px-6 pb-1">
                        <div class="p-5 text-center border rounded-2xl border-slate-200 bg-slate-50">
                            <p class="text-xs font-medium tracking-wide uppercase text-slate-500">Token login</p>
                            <p class="mt-2 font-mono text-4xl font-bold tracking-[0.3em] text-primary-700">${esc(res.token)}</p>
                            <button type="button" class="sw-copy inline-flex items-center gap-2 px-3 py-1.5 mt-3 text-xs font-semibold transition bg-white border rounded-lg text-slate-700 border-slate-200 hover:bg-slate-100" data-token="${esc(res.token)}">
                                <i class="fa-regular fa-copy"></i> Salin token
                            </button>
                        </div>
                    </div>`,
                confirmButtonText: 'Selesai',
            });
        });
    }

    /* ---------- reset voting ---------- */
    function resetVoting() {
        const total = siswaList.length;
        const sudah = siswaList.filter((s) => s.status == 1).length;

        ModalDanger.fire({
            html: header('fa-solid fa-triangle-exclamation', 'bg-red-50 text-red-600', 'Reset voting?',
                         'Tindakan ini mengosongkan seluruh hasil pemilihan.') + `
                <div class="px-6 pb-1 space-y-4">
                    <div class="p-4 space-y-1.5 text-sm text-red-800 border border-red-100 rounded-2xl bg-red-50/70">
                        <p><b>${sudah}</b> dari <b>${total}</b> siswa yang sudah memilih akan dikembalikan ke status <b>Belum</b>.</p>
                        <p>Pilihan kandidat semua siswa dihapus. <b>Perolehan suara hilang dan tidak bisa dikembalikan.</b></p>
                    </div>
                    <div>
                        <label class="${LABEL}" for="swKonfirmasi">Ketik <span class="font-mono font-bold text-red-600">RESET</span> untuk melanjutkan</label>
                        <input id="swKonfirmasi" type="text" autocomplete="off" spellcheck="false" placeholder="RESET" class="${INPUT} font-mono tracking-widest">
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'Reset Voting',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: () => {
                Swal.getConfirmButton().disabled = true;   // aktif setelah mengetik RESET
                $('#swKonfirmasi').trigger('focus');
            },
            preConfirm: () => request(cfg.reset, 'POST')
                .catch((xhr) => { Swal.showValidationMessage(pesanError(xhr)); return false; })
        }).then((result) => {
            if (!result.isConfirmed || !result.value) return;
            toast('success', result.value.message, 3500);
            loadSiswa();
        });
    }

    /* ---------- events (delegasi, namespace .siswa) ---------- */
    $(document).on('click' + NS, '#btnTambahSiswa', tambahSiswa);
    $(document).on('click' + NS, '#btnResetVoting', resetVoting);

    // tombol "Acak" + input token otomatis huruf besar tanpa simbol
    $(document).on('click' + NS, '#swAcak', function () {
        $('#swToken').val(tokenAcak()).trigger('focus');
    });
    $(document).on('input' + NS, '#swToken', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // tombol Reset baru aktif jika kolom konfirmasi berisi RESET
    $(document).on('input' + NS, '#swKonfirmasi', function () {
        const cocok = this.value.trim().toUpperCase() === 'RESET';
        const btn = Swal.getConfirmButton();
        if (btn) btn.disabled = !cocok;
    });

    // Enter = konfirmasi
    $(document).on('keydown' + NS, '#swNama, #swKelas, #swToken, #swKonfirmasi', function (e) {
        if (e.key === 'Enter') Swal.clickConfirm();
    });

    $(document).on('click' + NS, '.btn-copy-token', function () {
        salinToken($(this).attr('data-token'), $(this).find('i'));
    });
    $(document).on('click' + NS, '.sw-copy', function () {
        salinToken($(this).attr('data-token'));
    });

    /* ---------- init ---------- */
    loadSiswa();
    window.__siswaTimer = setInterval(function () {
        if (aktif()) loadSiswa(); else clearInterval(window.__siswaTimer);
    }, 30000);
})();
</script>
@endscript