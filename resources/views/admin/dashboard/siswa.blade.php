<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="p-4 space-y-8 sm:p-8">
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
            <h3 class="mb-4 text-lg font-semibold font-display">Data Pemilih (Siswa)</h3>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="p-3 rounded-l-lg">Token</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody id="tabel-siswa" class="divide-y divide-slate-100">
                
                </tbody>
            </table>
        </div>
    </div>
</div>

@script
<script>
    function loadSiswa() {
        $.ajax({
            url: '/api/admin/siswa',
            method: 'GET',
            success: function(data) {
                let rows = '';
                $.each(data, function(i, s) {
                    let status = s.status == 1 ? '<span class="px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded">Sudah</span>' : '<span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded">Belum</span>';
                    rows += `<tr class="hover:bg-slate-50">
                        <td class="p-3">
                            <button type="button" class="btn-copy-token inline-flex items-center gap-2 text-blue-600 font-mono font-medium hover:text-blue-800 transition-colors" data-token="${s.token}"> ${s.token}<i class="fa-regular fa-copy text-xs text-slate-400"></i></button>
                        </td>
                        <td class="p-3 font-medium text-navy-900">${s.nama}</td>
                        <td class="p-3 font-medium text-navy-900">${s.kelas}</td>
                        <td class="p-3">${status}</td>
                    </tr>`;
                });
                $('#tabel-siswa').html(rows);
            },
            error: function(xhr) {
                console.error('Gagal ambil data siswa:', xhr);
            }
        });
        console.log("reload data api");
    }

    $(document).on('click', '.btn-copy-token', function () {
        const token = $(this).data('token');
        const $btn = $(this);
        const $icon = $btn.find('i');

        navigator.clipboard.writeText(token).then(function () {
            // feedback visual sementara: icon berubah jadi centang
            $icon.removeClass('fa-regular fa-copy text-slate-400')
                .addClass('fa-solid fa-check text-emerald-500');

            // opsional: toast kecil pakai SweetAlert2 (non-blocking)
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Token disalin: ' + token,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });

            // balikin icon ke semula setelah 1.5 detik
            setTimeout(function () {
                $icon.removeClass('fa-solid fa-check text-emerald-500')
                    .addClass('fa-regular fa-copy text-slate-400');
            }, 1500);

        }).catch(function (err) {
            console.error('Gagal menyalin token:', err);
            Swal.fire('Gagal', 'Tidak bisa menyalin token ke clipboard.', 'error');
        });
    });

    $(document).ready(function() {
        loadSiswa();
        setInterval(function() {
            loadSiswa();
        }, 30000);
    });
</script>
@endscript