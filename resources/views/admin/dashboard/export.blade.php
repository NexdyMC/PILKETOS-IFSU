<div id="export-app" class="p-4 space-y-6 sm:p-8"
     data-url-list="{{ url('/api/admin/siswa') }}"
     data-url-pdf="{{ route('admin.export.pdf') }}"
     data-url-zip="{{ route('admin.export.pdfkelas') }}"
     data-url-excel="{{ route('admin.export.excel') }}"
     data-url-import="{{ route('admin.siswa.import') }}"
     data-url-template="{{ route('admin.siswa.template') }}"
     data-csrf="{{ csrf_token() }}">

    {{-- 1. Header export --}}
    <div class="flex flex-wrap gap-4 justify-between items-center p-6 bg-white rounded-2xl border shadow-sm">
        <div class="flex gap-4 items-start">
            <div class="flex justify-center items-center w-12 h-12 text-xl rounded-2xl shrink-0 bg-primary-50 text-primary-700">
                <i class="fa-solid fa-file-export"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold font-display text-navy-900">Export Data Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Unduh daftar token pemilih dalam bentuk PDF atau Excel, per kelas maupun seluruh sekolah.</p>
            </div>
        </div>
        <div class="flex gap-3 items-center">
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
    <div class="p-4 bg-white rounded-2xl border shadow-sm">
        <div class="grid items-end gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_1.4fr_auto]">
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
                <label for="fPerPage" class="block mb-1.5 text-xs font-semibold tracking-wide uppercase text-slate-500">Tampilkan</label>
                <select id="fPerPage" title="Jumlah baris pratinjau (tidak memengaruhi isi file unduhan)" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="30">30 baris</option>
                    <option value="40">40 baris</option>
                    <option value="50">50 baris</option>
                    <option value="75">75 baris</option>
                    <option value="0">Semua baris</option>
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

    {{-- 4. Opsi dokumen & unduh --}}
    <div class="p-6 bg-white rounded-2xl border shadow-sm">
        <h3 class="text-lg font-semibold font-display">Unduh Dokumen</h3>
        <p class="mt-1 text-sm text-slate-500">Pilih bentuk PDF, lalu unduh sesuai kebutuhan. PDF dan Excel mengikuti filter di atas.</p>

        <div class="grid gap-4 mt-5 sm:grid-cols-2">
            <label class="block cursor-pointer">
                <input type="radio" name="layoutPdf" value="daftar" class="sr-only peer" checked>
                <div class="p-4 h-full rounded-2xl border-2 transition border-slate-200 hover:border-slate-300 peer-checked:border-primary-600 peer-checked:bg-primary-50/60">
                    <p class="flex gap-2 items-center font-semibold text-navy-900"><i class="fa-solid fa-list-ol text-primary-700"></i> Daftar token</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">Tabel nama dan token, satu halaman per kelas. Cocok untuk guru atau wali kelas.</p>
                </div>
            </label>
            <label class="block cursor-pointer">
                <input type="radio" name="layoutPdf" value="kartu" class="sr-only peer">
                <div class="p-4 h-full rounded-2xl border-2 transition border-slate-200 hover:border-slate-300 peer-checked:border-primary-600 peer-checked:bg-primary-50/60">
                    <p class="flex gap-2 items-center font-semibold text-navy-900"><i class="fa-regular fa-id-card text-primary-700"></i> Kartu token</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">Satu kartu per siswa, tinggal digunting lalu dibagikan ke murid.</p>
                </div>
            </label>
        </div>

        <div class="grid gap-3 mt-5 sm:grid-cols-3">
            <button type="button" data-aksi="pdf"
                    class="flex gap-2 justify-center items-center px-4 py-3 text-sm font-semibold text-white rounded-xl btn-export transition bg-primary-700 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-pdf"></i> Unduh PDF
            </button>
            <button type="button" data-aksi="excel"
                    class="flex gap-2 justify-center items-center px-4 py-3 text-sm font-semibold text-white bg-emerald-600 rounded-xl btn-export transition hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-excel"></i> Unduh Excel
            </button>
            <button type="button" data-aksi="zip"
                    class="flex gap-2 justify-center items-center px-4 py-3 text-sm font-semibold bg-white rounded-xl border btn-export transition border-slate-200 text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-file-zipper"></i> PDF per kelas (ZIP)
            </button>
        </div>

        <ul class="mt-4 space-y-1 text-xs text-slate-500">
            <li><b>PDF</b>: sesuai filter. Kalau semua kelas dipilih, tiap kelas dimulai di halaman baru.</li>
            <li><b>Excel</b>: kalau satu kelas dipilih, hasilnya satu sheet. Kalau semua kelas, ada sheet "Semua Kelas" dan satu sheet per kelas.</li>
            <li><b>ZIP</b>: satu file PDF untuk setiap kelas (seluruh sekolah), siap diberikan ke masing-masing wali kelas.</li>
        </ul>
    </div>

    {{-- 3. Tabel pratinjau --}}
    <div class="overflow-x-auto p-6 bg-white rounded-2xl border shadow-sm">
        <div class="flex flex-wrap gap-2 justify-between items-center mb-4">
            <h3 class="text-lg font-semibold font-display">Pratinjau Data</h3>
            <span id="infoTabel" class="px-3 py-1 text-xs font-semibold rounded-full bg-primary-50 text-primary-700">Memuat data...</span>
        </div>

        <table class="w-full text-sm text-left">
            <thead wire:ignore class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="p-3 w-16 rounded-l-lg">No</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3 rounded-r-lg">Kelas</th>
                    <th class="p-3">Token</th>
                </tr>
            </thead>
            <tbody id="tabelExport" wire:ignore class="divide-y divide-slate-100"></tbody>
        </table>

        <div id="paginasiExport" wire:ignore class="flex flex-wrap gap-3 justify-between items-center mt-4"></div>
    </div>

    {{-- 5. Import data siswa --}}
    <div class="flex flex-wrap gap-4 justify-between items-center p-6 bg-white rounded-2xl border shadow-sm">
        <div class="flex gap-4 items-start">
            <div class="flex justify-center items-center w-12 h-12 text-xl text-emerald-600 bg-emerald-50 rounded-2xl shrink-0">
                <i class="fa-solid fa-file-import"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold font-display text-navy-900">Import Data Siswa</h3>
                <p class="mt-1 text-sm text-slate-500">Tambahkan banyak siswa sekaligus dari file Excel. Hanya kolom <b>nama</b> dan <b>kelas</b> yang dibaca, token dibuat otomatis.</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 items-center">
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

    // pagination pratinjau: perPage 0 = tampilkan semua (file unduhan tetap memuat semua data sesuai filter)
    const PER_PAGE_OPSI = [30, 40, 50, 75, 0];
    const PER_PAGE_KEY  = 'evote.export.perPage';
    let perPage = 30;
    let halaman = 1;
    try {
        const simpan = parseInt(localStorage.getItem(PER_PAGE_KEY), 10);
        if (PER_PAGE_OPSI.indexOf(simpan) !== -1) perPage = simpan;
    } catch (e) { /* localStorage tidak tersedia */ }
    $('#fPerPage').val(String(perPage));

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

    // nomor halaman ringkas: 1 ... 4 5 [6] 7 8 ... 20
    function daftarHalaman(sekarang, jumlah) {
        const set = { 1: true };
        set[jumlah] = true;
        for (let i = sekarang - 2; i <= sekarang + 2; i++) { if (i >= 1 && i <= jumlah) set[i] = true; }
        const nomor = Object.keys(set).map(Number).sort(function (a, b) { return a - b; });

        const hasil = [];
        nomor.forEach(function (n, i) {
            if (i > 0 && n - nomor[i - 1] > 1) hasil.push(null);
            hasil.push(n);
        });
        return hasil;
    }

    function renderPaginasi(jumlahHalaman) {
        const $box = $('#paginasiExport');
        if (!$box.length) return;

        if (perPage === 0 || jumlahHalaman <= 1) { $box.empty(); return; }

        const BTN = 'px-3 py-1.5 text-sm font-semibold transition border rounded-lg pg-export ';
        const OFF = 'border-slate-200 text-slate-600 hover:bg-slate-50';
        const ON  = 'border-primary-700 bg-primary-700 text-white';
        const DIS = 'opacity-40 cursor-not-allowed';

        let html = '<p class="text-xs text-slate-500">Halaman <b>' + halaman + '</b> dari <b>' + jumlahHalaman + '</b></p>'
                 + '<div class="flex flex-wrap gap-1.5 items-center">';
        html += '<button type="button" class="' + BTN + OFF + (halaman === 1 ? ' ' + DIS : '') + '" data-hal="' + (halaman - 1) + '"' + (halaman === 1 ? ' disabled' : '') + '>Sebelumnya</button>';
        daftarHalaman(halaman, jumlahHalaman).forEach(function (n) {
            if (n === null) { html += '<span class="px-1 text-slate-400">...</span>'; return; }
            html += '<button type="button" class="' + BTN + (n === halaman ? ON : OFF) + '" data-hal="' + n + '"' + (n === halaman ? ' aria-current="page"' : '') + '>' + n + '</button>';
        });
        html += '<button type="button" class="' + BTN + OFF + (halaman === jumlahHalaman ? ' ' + DIS : '') + '" data-hal="' + (halaman + 1) + '"' + (halaman === jumlahHalaman ? ' disabled' : '') + '>Berikutnya</button>';
        html += '</div>';
        $box.html(html);
    }

    function renderTabel() {
        if (!aktif()) return;

        const data  = terfilter();
        const total = data.length;
        const per   = perPage === 0 ? Math.max(total, 1) : perPage;
        const jumlahHalaman = Math.max(1, Math.ceil(total / per));
        if (halaman > jumlahHalaman) halaman = jumlahHalaman;
        if (halaman < 1) halaman = 1;

        const awal   = perPage === 0 ? 0 : (halaman - 1) * per;
        const tampil = perPage === 0 ? data : data.slice(awal, awal + per);

        let rows = '';
        tampil.forEach(function (s, i) {
            rows += '<tr class="hover:bg-slate-50">'
                  + '<td class="p-3 text-slate-400">' + (awal + i + 1) + '</td>'
                  + '<td class="p-3 font-medium text-navy-900">' + esc(s.nama) + '</td>'
                  + '<td class="p-3 font-medium text-navy-900">' + esc(s.kelas) + '</td>'
                  + '<td class="p-3 font-mono font-medium text-blue-600">' + esc(s.token) + '</td>'
                  + '</tr>';
        });
        if (!rows) {
            rows = '<tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada siswa yang cocok dengan filter.</td></tr>';
        }
        $('#tabelExport').html(rows);

        const label = filter.kelas ? 'Kelas ' + filter.kelas + ': ' : '';
        const rentang = total === 0 ? '0' : (awal + 1) + '-' + (awal + tampil.length);
        $('#infoTabel').text(label + 'Menampilkan ' + rentang + ' dari ' + total + ' siswa' + (total !== siswa.length ? ' (total ' + siswa.length + ')' : ''));
        renderPaginasi(jumlahHalaman);
        perbaruiTombol(total);   // tombol unduh mengikuti jumlah data terfilter, bukan jumlah baris yang tampil
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
            <div class="flex gap-4 items-start">
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
                           class="flex flex-col gap-1 justify-center items-center p-6 text-center rounded-2xl border-2 border-dashed transition cursor-pointer border-slate-300 bg-slate-50 hover:border-primary-400 hover:bg-primary-50/50">
                        <i class="mb-1 text-2xl fa-solid fa-cloud-arrow-up text-slate-400"></i>
                        <span id="swFileNama" class="text-sm font-semibold break-all text-slate-800">Klik atau tarik file ke sini</span>
                        <span class="text-xs text-slate-500">.xlsx, .xls, atau .csv (maks 2MB)</span>
                        <input id="swFile" type="file" accept=".xlsx,.xls,.csv" class="hidden">
                    </label>
                    <div class="p-3 text-xs leading-relaxed rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
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
                 + '<ul class="overflow-y-auto p-3 space-y-1 max-h-36 text-xs rounded-xl border border-slate-200 text-slate-600">'
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
    $(document).on('change' + NS, '#fKelas', function () { filter.kelas = this.value; halaman = 1; renderTabel(); });
    $(document).on('change' + NS, '#fUrut',  function () { filter.urut  = this.value; halaman = 1; renderTabel(); });

    $(document).on('change' + NS, '#fPerPage', function () {
        const nilai = parseInt(this.value, 10);
        perPage = PER_PAGE_OPSI.indexOf(nilai) !== -1 ? nilai : 30;
        halaman = 1;
        try { localStorage.setItem(PER_PAGE_KEY, String(perPage)); } catch (e) { /* abaikan */ }
        renderTabel();
    });
    $(document).on('click' + NS, '.pg-export', function () {
        const tujuan = parseInt($(this).attr('data-hal'), 10);
        if (!tujuan || $(this).prop('disabled')) return;
        halaman = tujuan;
        renderTabel();
    });

    $(document).on('input' + NS, '#fCari', function () {
        const nilai = this.value;
        clearTimeout(timerCari);
        timerCari = setTimeout(function () { filter.q = nilai; halaman = 1; renderTabel(); }, 200);
    });

    $(document).on('click' + NS, '#fReset', function () {
        filter.kelas = ''; filter.q = ''; filter.urut = 'kelas'; halaman = 1;
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