<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div id="export-app" class="p-4 space-y-6 sm:p-8"
     data-url-list="{{ url('/api/admin/siswa') }}"
     data-url-pdf="{{ route('admin.export.pdf') }}"
     data-url-zip="{{ route('admin.export.pdfkelas') }}"
     data-url-excel="{{ route('admin.export.excel') }}"
     data-url-import="{{ route('admin.siswa.import') }}"
     data-url-template="{{ route('admin.siswa.template') }}"
     data-csrf="{{ csrf_token() }}">

    {{-- 1. Header export --}}
    <div class="flex flex-wrap items-center justify-between gap-4 p-6 bg-white border shadow-sm rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="flex items-center justify-center w-12 h-12 text-xl rounded-2xl shrink-0 bg-primary-50 text-primary-700">
                <i class="fa-solid fa-file-export"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold font-display text-navy-900">Export Data Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Unduh daftar token pemilih dalam bentuk PDF atau Excel, per kelas maupun seluruh sekolah.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 text-center rounded-xl bg-slate-50">
                <p id="statTotal" class="text-xl font-bold text-navy-900">-</p>
                <p class="text-xs text-slate-500">Siswa</p>
            </div>
            <div class="px-4 py-2 text-center rounded-xl bg-slate-50">
                <p id="statKelas" class="text-xl font-bold text-navy-900">-</p>
                <p class="text-xs text-slate-500">Kelas</p>
            </div>
        </div>
    </div>

    {{-- 2. Sorter / filter --}}
    <div class="p-4 bg-white border shadow-sm rounded-2xl">
        <div class="grid items-end gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1.4fr_auto]">
            <div>
                <label for="fKelas" class="block mb-1.5 text-xs font-semibold tracking-wide uppercase text-slate-500">Kelas</label>
                <select id="fKelas" wire:ignore class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="">Semua kelas</option>
                </select>
            </div>
            <div>
                <label for="fUrut" class="block mb-1.5 text-xs font-semibold tracking-wide uppercase text-slate-500">Urutkan</label>
                <select id="fUrut" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="kelas">Kelas, lalu nama</option>
                    <option value="nama_asc">Nama A &rarr; Z</option>
                    <option value="nama_desc">Nama Z &rarr; A</option>
                </select>
            </div>
            <div>
                <label for="fCari" class="block mb-1.5 text-xs font-semibold tracking-wide uppercase text-slate-500">Cari</label>
                <div class="relative">
                    <i class="absolute text-sm -translate-y-1/2 fa-solid fa-magnifying-glass left-3.5 top-1/2 text-slate-400"></i>
                    <input id="fCari" type="search" autocomplete="off" placeholder="Nama atau token..."
                           class="w-full py-2.5 pl-10 pr-3 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
            </div>
            <button type="button" id="fReset" class="px-4 py-2.5 text-sm font-semibold transition border rounded-xl border-slate-200 text-slate-600 hover:bg-slate-50">
                <i class="mr-1.5 fa-solid fa-rotate-left"></i>Reset
            </button>
        </div>
    </div>

    {{-- 3. Tabel pratinjau --}}
    <div class="p-6 overflow-x-auto bg-white border shadow-sm rounded-2xl">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-lg font-semibold font-display">Pratinjau Data</h3>
            <span id="infoTabel" class="px-3 py-1 text-xs font-semibold rounded-full bg-primary-50 text-primary-700">Memuat data...</span>
        </div>

        <table class="w-full text-sm text-left">
            <thead wire:ignore class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="w-16 p-3 rounded-l-lg">No</th>
                    <th class="p-3">Token</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3 rounded-r-lg">Kelas</th>
                </tr>
            </thead>
            <tbody id="tabelExport" wire:ignore class="divide-y divide-slate-100"></tbody>
        </table>
    </div>

    {{-- 4. Opsi dokumen & unduh --}}
    <div class="p-6 bg-white border shadow-sm rounded-2xl">
        <h3 class="text-lg font-semibold font-display">Unduh Dokumen</h3>
        <p class="mt-1 text-sm text-slate-500">Pilih bentuk PDF, lalu unduh sesuai kebutuhan. PDF dan Excel mengikuti filter di atas.</p>

        <div class="grid gap-4 mt-5 sm:grid-cols-2">
            <label class="block cursor-pointer">
                <input type="radio" name="layoutPdf" value="daftar" class="sr-only peer" checked>
                <div class="h-full p-4 transition border-2 rounded-2xl border-slate-200 hover:border-slate-300 peer-checked:border-primary-600 peer-checked:bg-primary-50/60">
                    <p class="flex items-center gap-2 font-semibold text-navy-900"><i class="fa-solid fa-list-ol text-primary-700"></i> Daftar token</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">Tabel nama dan token, satu halaman per kelas. Cocok untuk guru atau wali kelas.</p>
                </div>
            </label>
            <label class="block cursor-pointer">
                <input type="radio" name="layoutPdf" value="kartu" class="sr-only peer">
                <div class="h-full p-4 transition border-2 rounded-2xl border-slate-200 hover:border-slate-300 peer-checked:border-primary-600 peer-checked:bg-primary-50/60">
                    <p class="flex items-center gap-2 font-semibold text-navy-900"><i class="fa-regular fa-id-card text-primary-700"></i> Kartu token</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">Satu kartu per siswa, tinggal digunting lalu dibagikan ke murid.</p>
                </div>
            </label>
        </div>

        <div class="grid gap-3 mt-5 sm:grid-cols-3">
            <button type="button" data-aksi="pdf"
                    class="btn-export flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-white transition rounded-xl bg-primary-700 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-pdf"></i> Unduh PDF
            </button>
            <button type="button" data-aksi="excel"
                    class="btn-export flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-white transition rounded-xl bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-excel"></i> Unduh Excel
            </button>
            <button type="button" data-aksi="zip"
                    class="btn-export flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold transition bg-white border rounded-xl border-slate-200 text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-zipper"></i> PDF per kelas (ZIP)
            </button>
        </div>

        <ul class="mt-4 space-y-1 text-xs text-slate-500">
            <li><b>PDF</b>: sesuai filter. Kalau semua kelas dipilih, tiap kelas dimulai di halaman baru.</li>
            <li><b>Excel</b>: kalau satu kelas dipilih, hasilnya satu sheet. Kalau semua kelas, ada sheet "Semua Kelas" dan satu sheet per kelas.</li>
            <li><b>ZIP</b>: satu file PDF untuk setiap kelas (seluruh sekolah), siap diberikan ke masing-masing wali kelas.</li>
        </ul>
    </div>

    {{-- 5. Import data siswa --}}
    <div class="flex flex-wrap items-center justify-between gap-4 p-6 bg-white border shadow-sm rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="flex items-center justify-center w-12 h-12 text-xl rounded-2xl shrink-0 bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-file-import"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold font-display text-navy-900">Import Data Siswa</h3>
                <p class="mt-1 text-sm text-slate-500">Tambahkan banyak siswa sekaligus dari file Excel. Hanya kolom <b>nama</b> dan <b>kelas</b> yang dibaca, token dibuat otomatis.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.siswa.template') }}" class="px-4 py-2.5 text-sm font-semibold transition border rounded-xl border-slate-200 text-slate-700 hover:bg-slate-50">
                <i class="mr-1.5 fa-solid fa-download"></i>Template
            </a>
            <button type="button" id="btnImportExcel" class="px-4 py-2.5 text-sm font-semibold text-white transition rounded-xl bg-emerald-600 hover:bg-emerald-500">
                <i class="mr-1.5 fa-solid fa-file-excel"></i>Import Excel
            </button>
        </div>
    </div>
</div>

@script
<script>
(function () {
    const $root = $('#export-app');
    if (!$root.length) return;

    const NS = '.export';
    const cfg = {
        list:  $root.attr('data-url-list'),
        pdf:   $root.attr('data-url-pdf'),
        zip:   $root.attr('data-url-zip'),
        excel: $root.attr('data-url-excel'),
        import:   $root.attr('data-url-import'),
        template: $root.attr('data-url-template'),
        csrf:     $root.attr('data-csrf'),
    };

    // lepas listener lama (aman untuk wire:navigate)
    $(document).off(NS);

    let siswa = [];
    const filter = { kelas: '', q: '', urut: 'kelas' };
    let timerCari = null;

    /* ---------- helpers ---------- */
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
    const aktif = () => $('#export-app').length > 0;
    const bandingTeks = (a, b) => String(a).localeCompare(String(b), 'id', { numeric: true, sensitivity: 'base' });

    function toast(icon, title) {
        Swal.fire({
            toast: true, position: 'top-end', icon: icon, title: title,
            showConfirmButton: false, timer: 2800, timerProgressBar: true
        });
    }

    /* ---------- filter + urutan (hanya kelas terpilih yang tampil) ---------- */
    function terfilter() {
        const q = filter.q.trim().toLowerCase();

        const hasil = siswa.filter(function (s) {
            if (filter.kelas && s.kelas !== filter.kelas) return false;
            if (q && String(s.nama).toLowerCase().indexOf(q) === -1 && String(s.token).toLowerCase().indexOf(q) === -1) return false;
            return true;
        });

        hasil.sort(function (a, b) {
            if (filter.urut === 'nama_asc')  return bandingTeks(a.nama, b.nama);
            if (filter.urut === 'nama_desc') return bandingTeks(b.nama, a.nama);
            return bandingTeks(a.kelas, b.kelas) || bandingTeks(a.nama, b.nama);
        });
        return hasil;
    }

    function isiPilihanKelas() {
        const daftar = [];
        siswa.forEach(function (s) { if (daftar.indexOf(s.kelas) === -1) daftar.push(s.kelas); });
        daftar.sort(bandingTeks);

        let opsi = '<option value="">Semua kelas (' + daftar.length + ')</option>';
        daftar.forEach(function (k) { opsi += '<option value="' + esc(k) + '">' + esc(k) + '</option>'; });

        $('#fKelas').html(opsi);
        // pertahankan pilihan jika kelasnya masih ada
        if (filter.kelas && daftar.indexOf(filter.kelas) === -1) filter.kelas = '';
        $('#fKelas').val(filter.kelas);

        $('#statTotal').text(siswa.length);
        $('#statKelas').text(daftar.length);
    }

    function perbaruiTombol(jumlah) {
        $('.btn-export[data-aksi="pdf"], .btn-export[data-aksi="excel"]').prop('disabled', jumlah === 0);
        $('.btn-export[data-aksi="zip"]').prop('disabled', siswa.length === 0);
    }

    function renderTabel() {
        if (!aktif()) return;

        const data = terfilter();
        let rows = '';
        data.forEach(function (s, i) {
            rows += '<tr class="hover:bg-slate-50">'
                  + '<td class="p-3 text-slate-400">' + (i + 1) + '</td>'
                  + '<td class="p-3 font-mono font-medium text-blue-600">' + esc(s.token) + '</td>'
                  + '<td class="p-3 font-medium text-navy-900">' + esc(s.nama) + '</td>'
                  + '<td class="p-3 font-medium text-navy-900">' + esc(s.kelas) + '</td>'
                  + '</tr>';
        });
        if (!rows) {
            rows = '<tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada siswa yang cocok dengan filter.</td></tr>';
        }
        $('#tabelExport').html(rows);

        const label = filter.kelas ? 'Kelas ' + filter.kelas + ': ' : '';
        $('#infoTabel').text(label + data.length + ' dari ' + siswa.length + ' siswa');
        perbaruiTombol(data.length);
    }

    function loadSiswa() {
        $.ajax({
            url: cfg.list,
            method: 'GET',
            success: function (data) {
                if (!aktif()) return;
                siswa = data;
                isiPilihanKelas();
                renderTabel();
            },
            error: function (xhr) {
                console.error('Gagal ambil data siswa:', xhr);
                $('#infoTabel').text('Gagal memuat data');
            }
        });
    }

    /* ---------- unduh file (fetch -> blob, error tampil sebagai toast) ---------- */
    async function unduh(url, $btn) {
        const labelAsal = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Menyiapkan...');

        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': '*/*' },
                credentials: 'same-origin'
            });

            if (!res.ok) {
                let pesan = 'Gagal membuat file (kode ' + res.status + ').';
                try {
                    const j = await res.json();
                    if (j && j.message) pesan = j.message;
                } catch (e) { /* respons bukan JSON */ }
                throw new Error(pesan);
            }

            const blob = await res.blob();
            const cd   = res.headers.get('Content-Disposition') || '';
            const m    = /filename\*?=(?:UTF-8'')?"?([^";]+)"?/i.exec(cd);
            const nama = m ? decodeURIComponent(m[1]) : 'export';

            const objUrl = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = objUrl;
            a.download = nama;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(function () { URL.revokeObjectURL(objUrl); }, 3000);

            toast('success', 'File berhasil dibuat');
        } catch (err) {
            toast('error', err.message || 'Gagal membuat file.');
        } finally {
            $btn.html(labelAsal);
            perbaruiTombol(terfilter().length);
        }
    }

    function ekspor($btn) {
        const aksi   = $btn.attr('data-aksi');
        const layout = $('input[name="layoutPdf"]:checked').val() || 'daftar';
        const dasar  = { kelas: filter.kelas, q: filter.q.trim(), urut: filter.urut };
        let url;

        if (aksi === 'pdf') {
            url = cfg.pdf + '?' + $.param($.extend({}, dasar, { layout: layout }));
        } else if (aksi === 'zip') {
            url = cfg.zip + '?' + $.param({ layout: layout });
        } else {
            url = cfg.excel + '?' + $.param($.extend({}, dasar, { per_kelas: filter.kelas ? 0 : 1 }));
        }
        unduh(url, $btn);
    }

    /* ---------- import excel ---------- */
    const Modal = Swal.mixin({
        buttonsStyling: false,
        reverseButtons: true,
        focusConfirm: false,
        customClass: {
            popup: '!rounded-3xl !p-0 !w-[28rem] !max-w-[calc(100vw-2rem)] !text-left',
            htmlContainer: '!m-0 !p-0 !text-left !overflow-visible',
            actions: '!w-full !m-0 !px-6 !pb-6 !pt-5 !gap-3 !flex-nowrap',
            confirmButton: 'flex-1 px-4 py-3 text-sm font-semibold text-white rounded-xl bg-primary-700 hover:bg-primary-600',
            cancelButton: 'flex-1 px-4 py-3 text-sm font-semibold border rounded-xl border-slate-200 text-slate-700 hover:bg-slate-50',
            validationMessage: '!mx-6 !mt-3 !rounded-xl !bg-red-50 !text-red-700 !text-xs'
        }
    });

    const header = (iconCls, tone, title, desc) => `
        <div class="px-6 pt-6 pb-4">
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-2xl shrink-0 ${tone}"><i class="text-lg ${iconCls}"></i></div>
                <div class="min-w-0 pt-0.5">
                    <h3 class="text-lg font-semibold leading-tight text-slate-900">${title}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">${desc}</p>
                </div>
            </div>
        </div>`;

    function pesanError(xhr) {
        const res = (xhr && xhr.responseJSON) || {};
        return res.errors ? Object.values(res.errors).flat().join(' ') : (res.message || 'Terjadi kesalahan pada server.');
    }

    function kirimFile(file) {
        return new Promise(function (resolve, reject) {
            const fd = new FormData();
            fd.append('file', file);
            $.ajax({
                url: cfg.import, method: 'POST', data: fd, dataType: 'json',
                processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'X-Requested-With': 'XMLHttpRequest' }
            }).done(resolve).fail(reject);
        });
    }

    function importExcel() {
        Modal.fire({
            html: header('fa-solid fa-file-excel', 'bg-emerald-50 text-emerald-600', 'Import Siswa dari Excel',
                         'Hanya kolom <b>nama</b> dan <b>kelas</b> yang dibaca. Token dibuat otomatis.') + `
                <div class="px-6 pb-1 space-y-4">
                    <label id="swDrop" for="swFile"
                           class="flex flex-col items-center justify-center gap-1 p-6 text-center transition border-2 border-dashed cursor-pointer rounded-2xl border-slate-300 bg-slate-50 hover:border-primary-400 hover:bg-primary-50/50">
                        <i class="mb-1 text-2xl fa-solid fa-cloud-arrow-up text-slate-400"></i>
                        <span id="swFileNama" class="text-sm font-semibold break-all text-slate-800">Klik atau tarik file ke sini</span>
                        <span class="text-xs text-slate-500">.xlsx, .xls, atau .csv (maks 2MB)</span>
                        <input id="swFile" type="file" accept=".xlsx,.xls,.csv" class="hidden">
                    </label>
                    <div class="p-3 text-xs leading-relaxed border rounded-xl border-slate-200 bg-slate-50 text-slate-600">
                        Baris pertama harus berisi judul kolom <b>nama</b> dan <b>kelas</b>. Siswa yang namanya dan kelasnya sama dengan data yang sudah ada akan dilewati.
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'Import',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                const input = document.getElementById('swFile');
                const file  = input && input.files ? input.files[0] : null;
                if (!file) { Swal.showValidationMessage('Pilih file Excel terlebih dahulu.'); return false; }
                if (file.size > 2 * 1024 * 1024) { Swal.showValidationMessage('Ukuran file maksimal 2MB.'); return false; }
                return kirimFile(file).catch((xhr) => { Swal.showValidationMessage(pesanError(xhr)); return false; });
            }
        }).then((result) => {
            if (!result.isConfirmed || !result.value) return;
            loadSiswa();     // jumlah siswa, kelas, dan tabel pratinjau ikut diperbarui
            hasilImport(result.value);
        });
    }

    function hasilImport(res) {
        const sukses = res.berhasil > 0;
        const stat = (angka, label, cls) =>
            '<div class="p-3 text-center rounded-xl ' + cls + '"><p class="text-2xl font-bold">' + angka + '</p><p class="text-xs font-medium">' + label + '</p></div>';
        const daftar = (judul, total, items, fmt) => {
            if (!items.length) return '';
            const sisa = total - items.length;
            return '<div><p class="mb-1.5 text-xs font-semibold tracking-wide uppercase text-slate-500">' + judul + '</p>'
                 + '<ul class="p-3 space-y-1 overflow-y-auto text-xs border max-h-36 rounded-xl border-slate-200 text-slate-600">'
                 + items.map(fmt).join('')
                 + (sisa > 0 ? '<li class="text-slate-400">... dan ' + sisa + ' lainnya</li>' : '')
                 + '</ul></div>';
        };

        const gagalHtml = daftar('Baris gagal', res.gagal_total, res.gagal, (g) =>
            '<li><b>Baris ' + esc(g.baris) + '</b>: ' + esc(g.alasan) + (g.nama ? ' (' + esc(g.nama) + ')' : '') + '</li>');
        const dupHtml = daftar('Baris dilewati (sudah ada)', res.duplikat_total, res.duplikat, (d) =>
            '<li><b>Baris ' + esc(d.baris) + '</b>: ' + esc(d.nama) + ' - ' + esc(d.kelas) + '</li>');

        Modal.fire({
            html: header(
                    sukses ? 'fa-solid fa-check' : 'fa-solid fa-triangle-exclamation',
                    sukses ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600',
                    sukses ? 'Import selesai' : 'Tidak ada data yang diimpor',
                    sukses ? 'Siswa baru sudah masuk dan token dibuat otomatis.' : 'Periksa isi file lalu coba lagi.') + `
                <div class="px-6 pb-1 space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        ${stat(res.berhasil, 'Berhasil', 'bg-emerald-50 text-emerald-700')}
                        ${stat(res.duplikat_total, 'Dilewati', 'bg-amber-50 text-amber-700')}
                        ${stat(res.gagal_total, 'Gagal', 'bg-red-50 text-red-700')}
                    </div>
                    ${gagalHtml}${dupHtml}
                </div>`,
            confirmButtonText: 'Selesai'
        });
    }

    /* ---------- events (delegasi, namespace .export) ---------- */
    $(document).on('change' + NS, '#fKelas', function () { filter.kelas = this.value; renderTabel(); });
    $(document).on('change' + NS, '#fUrut',  function () { filter.urut  = this.value; renderTabel(); });

    $(document).on('input' + NS, '#fCari', function () {
        const nilai = this.value;
        clearTimeout(timerCari);
        timerCari = setTimeout(function () { filter.q = nilai; renderTabel(); }, 200);
    });

    $(document).on('click' + NS, '#fReset', function () {
        filter.kelas = ''; filter.q = ''; filter.urut = 'kelas';
        $('#fKelas').val('');
        $('#fUrut').val('kelas');
        $('#fCari').val('');
        renderTabel();
    });

    $(document).on('click' + NS, '#btnImportExcel', importExcel);
    $(document).on('change' + NS, '#swFile', function () {
        const f = this.files && this.files[0];
        $('#swFileNama').text(f ? f.name : 'Klik atau tarik file ke sini');
    });
    $(document).on('dragover' + NS, '#swDrop', function (e) { e.preventDefault(); $(this).addClass('border-primary-400 bg-primary-50/50'); });
    $(document).on('dragleave' + NS, '#swDrop', function (e) { e.preventDefault(); $(this).removeClass('border-primary-400 bg-primary-50/50'); });
    $(document).on('drop' + NS, '#swDrop', function (e) {
        e.preventDefault();
        $(this).removeClass('border-primary-400 bg-primary-50/50');
        const files = e.originalEvent.dataTransfer.files;
        if (files && files.length > 0) {
            $('#swFile')[0].files = files;
            $('#swFileNama').text(files[0].name);
        }
    });

    $(document).on('click' + NS, '.btn-export', function () { ekspor($(this)); });

    /* ---------- init ---------- */
    loadSiswa();
})();
</script>
@endscript