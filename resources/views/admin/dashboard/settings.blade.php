<div id="settings-app" class="p-4 space-y-6 sm:p-8"
     data-url-data="{{ url('/api/admin/settings') }}"
     data-url-save="{{ url('/admin/settings') }}"
     data-url-voting="{{ url('/admin/settings/voting') }}"
     data-csrf="{{ csrf_token() }}">

    <div wire:ignore class="space-y-6">

        {{-- 1. Header --}}
        <div class="flex flex-wrap gap-4 justify-between items-center p-6 bg-white rounded-2xl border shadow-sm">
            <div class="flex gap-4 items-start">
                <div class="flex justify-center items-center w-12 h-12 text-xl rounded-2xl shrink-0 bg-primary-50 text-primary-700">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold font-display text-navy-900">Pengaturan Pemilihan</h2>
                    <p class="mt-1 text-sm text-slate-500">Atur status voting, jadwal, identitas sekolah, dan privasi pilihan siswa.</p>
                </div>
            </div>
            <div class="text-right">
                <span id="badgeStatus" class="inline-flex gap-2 items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-slate-100 text-slate-500">Memuat...</span>
                <p id="jamServer" class="mt-1.5 text-xs text-slate-400"></p>
            </div>
        </div>

        {{-- 2. Status & jadwal --}}
        <div class="p-6 bg-white rounded-2xl border shadow-sm">
            <div class="flex flex-wrap gap-4 justify-between items-start">
                <div class="min-w-0 max-w-2xl">
                    <h3 class="text-lg font-semibold font-display">Status &amp; Jadwal Voting</h3>
                    <p id="ketStatus" class="mt-1 text-sm text-slate-500">Memuat data...</p>
                </div>
                <label class="inline-flex gap-3 items-center cursor-pointer">
                    <span class="text-sm font-semibold text-slate-700">Saklar voting</span>
                    <input type="checkbox" id="swVoting" class="sr-only peer" disabled>
                    <span class="relative w-14 h-8 rounded-full transition bg-slate-300 peer-checked:bg-emerald-500 peer-disabled:opacity-50 peer-focus-visible:ring-4 peer-focus-visible:ring-primary-100 after:content-[''] after:absolute after:top-1 after:left-1 after:w-6 after:h-6 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-6"></span>
                </label>
            </div>

            <div class="grid gap-4 mt-5 sm:grid-cols-2">
                <div>
                    <label for="fMulai" class="block mb-1.5 text-sm font-medium text-slate-700">Waktu mulai</label>
                    <input id="fMulai" type="datetime-local" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <p data-err="waktu_mulai" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
                <div>
                    <label for="fSelesai" class="block mb-1.5 text-sm font-medium text-slate-700">Waktu selesai</label>
                    <input id="fSelesai" type="datetime-local" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <p data-err="waktu_selesai" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
            </div>

            <p class="mt-4 text-xs leading-relaxed text-slate-500">
                Voting hanya terbuka kalau saklar menyala <b>dan</b> waktu sekarang berada di antara waktu mulai dan selesai.
                Setelah waktu selesai lewat, voting tertutup otomatis dan saklar tidak bisa dinyalakan (hanya muncul peringatan).
                Saklar bekerja langsung, sedangkan jadwal baru berlaku setelah klik <b>Simpan Perubahan</b>.
            </p>
        </div>

        {{-- 3. Identitas --}}
        <div class="p-6 bg-white rounded-2xl border shadow-sm">
            <h3 class="text-lg font-semibold font-display">Identitas Pemilihan</h3>
            <p class="mt-1 text-sm text-slate-500">Dipakai di halaman siswa dan panel admin.</p>

            <div class="grid gap-4 mt-5 sm:grid-cols-2">
                <div>
                    <label for="fNama" class="block mb-1.5 text-sm font-medium text-slate-700">Nama sekolah</label>
                    <input id="fNama" type="text" maxlength="100" autocomplete="off" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <p data-err="nama_sekolah" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
                <div>
                    <label for="fJudul" class="block mb-1.5 text-sm font-medium text-slate-700">Judul pemilihan</label>
                    <input id="fJudul" type="text" maxlength="100" autocomplete="off" class="w-full px-3 py-2.5 text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <p data-err="judul_pemilihan" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
                <div>
                    <label for="fTahun" class="block mb-1.5 text-sm font-medium text-slate-700">Tahun ajaran</label>
                    <input id="fTahun" type="text" maxlength="9" autocomplete="off" placeholder="2026-2027" class="w-full px-3 py-2.5 font-mono text-sm bg-white border outline-none rounded-xl border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <p data-err="tahun_ajaran" class="hidden mt-1 text-xs text-red-600"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Logo sekolah</label>
                    <div class="flex flex-wrap gap-4 items-center">
                        <div id="logoPreview" class="flex overflow-hidden justify-center items-center w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50">
                            <i class="text-3xl fa-solid fa-school text-slate-300"></i>
                        </div>
                        <div>
                            <label for="fLogo" class="inline-flex gap-2 items-center px-4 py-2.5 text-sm font-semibold rounded-xl border transition cursor-pointer border-slate-200 text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-image"></i> Pilih logo baru
                            </label>
                            <input id="fLogo" type="file" accept="image/png,image/jpeg,image/webp" class="hidden">
                            <p id="namaLogo" class="mt-2 text-xs text-slate-500">JPG, PNG, atau WEBP, maksimal 2 MB.</p>
                        </div>
                    </div>
                    <p data-err="logo" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
            </div>
        </div>

        {{-- 4. Lihat pilihan siswa (view_voted) --}}
        <div class="p-6 bg-white rounded-2xl border shadow-sm">
            <div class="flex flex-wrap gap-4 justify-between items-start">
                <div class="max-w-2xl">
                    <h3 class="text-lg font-semibold font-display">Lihat Pilihan Siswa</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">
                        Jika aktif, halaman <b>Siswa</b> menampilkan kolom <b>Pilihan</b> (siapa memilih kandidat mana) dan admin bisa mengubahnya.
                        Jika nonaktif, informasi itu disembunyikan dan tidak dikirim ke browser.
                    </p>
                </div>
                <label class="inline-flex gap-3 items-center cursor-pointer">
                    <span class="text-sm font-semibold text-slate-700">Tampilkan</span>
                    <input type="checkbox" id="swViewVoted" class="sr-only peer">
                    <span class="relative w-14 h-8 rounded-full transition bg-slate-300 peer-checked:bg-amber-500 peer-focus-visible:ring-4 peer-focus-visible:ring-primary-100 after:content-[''] after:absolute after:top-1 after:left-1 after:w-6 after:h-6 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-6"></span>
                </label>
            </div>
            <div id="warnViewVoted" class="hidden p-3 mt-4 text-xs leading-relaxed text-amber-800 bg-amber-50 rounded-xl border border-amber-200">
                <i class="mr-1 fa-solid fa-triangle-exclamation"></i>
                Pemilihan bersifat rahasia. Aktifkan hanya untuk keperluan pengawasan oleh admin yang berwenang, lalu matikan kembali setelah selesai.
            </div>
        </div>

        {{-- 5. Bar simpan --}}
        <div class="flex sticky bottom-4 z-10 flex-wrap gap-3 justify-between items-center px-5 py-3 bg-white rounded-2xl border shadow-lg">
            <p id="infoDirty" class="text-sm text-slate-500">Belum ada perubahan.</p>
            <div class="flex gap-2 items-center">
                <button type="button" id="btnBatal" disabled class="px-4 py-2.5 text-sm font-semibold rounded-xl border transition border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">
                    Batalkan
                </button>
                <button type="button" id="btnSimpan" disabled class="px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition bg-primary-700 hover:bg-primary-600 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="mr-1.5 fa-solid fa-floppy-disk"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@script
<script>
(function () {
    const $root = $('#settings-app');
    if (!$root.length) return;

    const NS = '.settings';
    const cfg = {
        data:   $root.attr('data-url-data'),
        save:   $root.attr('data-url-save'),
        voting: $root.attr('data-url-voting'),
        csrf:   $root.attr('data-csrf'),
    };

    // lepas listener & timer lama (aman untuk wire:navigate)
    $(document).off(NS);
    if (window.__settingsTimer) clearInterval(window.__settingsTimer);

    let saved = null;               // data terakhir dari server
    let waktuBasis = 0;             // jam server (dalam "bingkai UTC") saat data dimuat
    let waktuT0 = 0;                // Date.now() saat data dimuat
    let logoBaru = null;            // file logo yang dipilih tapi belum disimpan
    let logoObjUrl = null;

    const aktif = () => $('#settings-app').length > 0;
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));

    /* ---------- popup ---------- */
    function buatModal(warnaTombol) {
        return Swal.mixin({
            buttonsStyling: false,
            reverseButtons: true,
            focusConfirm: false,
            customClass: {
                popup: '!rounded-3xl !p-0 !w-[28rem] !max-w-[calc(100vw-2rem)] !text-left',
                htmlContainer: '!m-0 !p-0 !text-left !overflow-visible',
                actions: '!w-full !m-0 !px-6 !pb-6 !pt-5 !gap-3 !flex-nowrap',
                confirmButton: 'flex-1 px-4 py-3 text-sm font-semibold text-white rounded-xl ' + warnaTombol,
                cancelButton: 'flex-1 px-4 py-3 text-sm font-semibold border rounded-xl border-slate-200 text-slate-700 hover:bg-slate-50',
                validationMessage: '!mx-6 !mt-3 !rounded-xl !bg-red-50 !text-red-700 !text-xs'
            }
        });
    }
    const Modal       = buatModal('bg-primary-700 hover:bg-primary-600');
    const ModalDanger = buatModal('bg-red-600 hover:bg-red-500');

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

    function toast(icon, title) {
        Swal.fire({
            toast: true, position: 'top-end', icon: icon, title: title,
            showConfirmButton: false, timer: 2800, timerProgressBar: true
        });
    }

    function pesanError(xhr) {
        const res = (xhr && xhr.responseJSON) || {};
        return res.errors ? Object.values(res.errors).flat().join(' ') : (res.message || 'Terjadi kesalahan pada server.');
    }

    function ajax(url, method, data, isForm) {
        return new Promise(function (resolve, reject) {
            const o = {
                url: url, method: method, dataType: 'json',
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'X-Requested-With': 'XMLHttpRequest' }
            };
            if (data !== undefined) o.data = data;
            if (isForm) { o.processData = false; o.contentType = false; }
            $.ajax(o).done(resolve).fail(reject);
        });
    }

    /* ---------- waktu (semua dibandingkan dalam bingkai waktu server) ---------- */
    // "2026-10-10T18:30" atau "2026-10-10T18:30:00" -> milidetik; dibaca sebagai UTC agar tidak terpengaruh zona waktu browser
    const parseMs = (s) => s ? Date.parse(s + (s.length === 16 ? ':00' : '') + 'Z') : null;
    const sekarangMs = () => waktuBasis + (Date.now() - waktuT0);
    const fmtWaktu = (ms) => ms == null ? '-' : new Date(ms).toLocaleString('id-ID', {
        timeZone: 'UTC', weekday: 'short', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });

    // sama persis dengan Settings::statusJadwal() di PHP
    function hitungStatus() {
        const now = sekarangMs();
        const mulai = parseMs(saved.waktu_mulai);
        const selesai = parseMs(saved.waktu_selesai);
        if (selesai !== null && now >= selesai) return 'berakhir';
        if (!saved.status_voting) return 'ditutup';
        if (mulai !== null && now < mulai) return 'terjadwal';
        return 'berlangsung';
    }

    const STATUS = {
        ditutup:     { label: 'Ditutup',            icon: 'fa-lock',           cls: 'bg-red-50 text-red-700' },
        terjadwal:   { label: 'Terjadwal',          icon: 'fa-clock',          cls: 'bg-amber-50 text-amber-700' },
        berlangsung: { label: 'Sedang Berlangsung', icon: 'fa-circle-play',    cls: 'bg-emerald-50 text-emerald-700' },
        berakhir:    { label: 'Berakhir',           icon: 'fa-flag-checkered', cls: 'bg-slate-100 text-slate-600' }
    };

    function renderStatus() {
        if (!saved || !aktif()) return;

        const st = hitungStatus();
        const s  = STATUS[st];
        const mulai   = fmtWaktu(parseMs(saved.waktu_mulai));
        const selesai = fmtWaktu(parseMs(saved.waktu_selesai));
        const ket = {
            ditutup:     'Saklar mati. Siswa tidak bisa memilih.',
            terjadwal:   'Saklar menyala. Voting terbuka otomatis pada ' + mulai + '.',
            berlangsung: 'Voting sedang berlangsung dan ditutup otomatis pada ' + selesai + '.',
            berakhir:    'Jadwal berakhir pada ' + selesai + '. Voting tertutup. Untuk membukanya lagi, perpanjang waktu selesai.'
        }[st];

        $('#badgeStatus')
            .attr('class', 'inline-flex gap-2 items-center px-3 py-1.5 text-sm font-semibold rounded-full ' + s.cls)
            .html('<i class="fa-solid ' + s.icon + '"></i> ' + s.label);
        $('#ketStatus').text(ket);
        $('#jamServer').text('Waktu server: ' + fmtWaktu(sekarangMs()));
    }

    /* ---------- form ---------- */
    function nilaiForm() {
        return {
            nama_sekolah:    $('#fNama').val().trim(),
            judul_pemilihan: $('#fJudul').val().trim(),
            tahun_ajaran:    $('#fTahun').val().trim(),
            waktu_mulai:     $('#fMulai').val(),
            waktu_selesai:   $('#fSelesai').val(),
            view_voted:      $('#swViewVoted').prop('checked')
        };
    }

    function berubah() {
        if (!saved) return false;
        if (logoBaru) return true;
        const n = nilaiForm();
        return n.nama_sekolah    !== (saved.nama_sekolah || '')
            || n.judul_pemilihan !== (saved.judul_pemilihan || '')
            || n.tahun_ajaran    !== (saved.tahun_ajaran || '')
            || n.waktu_mulai     !== (saved.waktu_mulai || '')
            || n.waktu_selesai   !== (saved.waktu_selesai || '')
            || n.view_voted      !== !!saved.view_voted;
    }

    function cekPerubahan() {
        const b = berubah();
        $('#btnSimpan, #btnBatal').prop('disabled', !b);
        $('#infoDirty')
            .text(b ? 'Ada perubahan yang belum disimpan.' : 'Belum ada perubahan.')
            .toggleClass('font-semibold text-amber-600', b)
            .toggleClass('text-slate-500', !b);
    }

    function bersihkanError() { $('[data-err]').addClass('hidden').text(''); }
    function tampilError(map) {
        Object.keys(map).forEach(function (k) {
            $('[data-err="' + k + '"]').text(map[k]).removeClass('hidden');
        });
    }

    function validasi(n) {
        const e = {};
        if (!n.nama_sekolah)    e.nama_sekolah = 'Nama sekolah wajib diisi.';
        if (!n.judul_pemilihan) e.judul_pemilihan = 'Judul pemilihan wajib diisi.';

        const m = /^(\d{4})-(\d{4})$/.exec(n.tahun_ajaran);
        if (!m) e.tahun_ajaran = 'Format harus 2026-2027.';
        else if (+m[2] !== +m[1] + 1) e.tahun_ajaran = 'Tahun harus berurutan, contoh 2026-2027.';

        if (!n.waktu_mulai)   e.waktu_mulai = 'Waktu mulai wajib diisi.';
        if (!n.waktu_selesai) e.waktu_selesai = 'Waktu selesai wajib diisi.';
        if (n.waktu_mulai && n.waktu_selesai && parseMs(n.waktu_selesai) <= parseMs(n.waktu_mulai)) {
            e.waktu_selesai = 'Waktu selesai harus setelah waktu mulai.';
        }
        return e;
    }

    function tampilLogo(url) {
        $('#logoPreview').html(url
            ? '<img src="' + esc(url) + '" alt="Logo sekolah" class="w-full h-full object-contain">'
            : '<i class="text-3xl fa-solid fa-school text-slate-300"></i>');
    }

    function sinkronPeringatan() {
        $('#warnViewVoted').toggleClass('hidden', !$('#swViewVoted').prop('checked'));
    }

    // simpan data server + sinkronkan jam + saklar + status (tanpa menyentuh isian form)
    function perbaruiSaved(d) {
        saved = d;
        waktuBasis = parseMs(d.sekarang);
        waktuT0 = Date.now();
        $('#swVoting').prop('checked', !!d.status_voting).prop('disabled', false);
        renderStatus();
    }

    // isi semua isian form dari data server
    function isiForm(d) {
        $('#fNama').val(d.nama_sekolah);
        $('#fJudul').val(d.judul_pemilihan);
        $('#fTahun').val(d.tahun_ajaran);
        $('#fMulai').val(d.waktu_mulai || '');
        $('#fSelesai').val(d.waktu_selesai || '');
        $('#swViewVoted').prop('checked', !!d.view_voted);

        logoBaru = null;
        $('#fLogo').val('');
        $('#namaLogo').text('JPG, PNG, atau WEBP, maksimal 2 MB.');
        tampilLogo(d.logo_url);

        sinkronPeringatan();
        bersihkanError();
        cekPerubahan();
    }

    function muat() {
        ajax(cfg.data, 'GET').then(function (d) {
            if (!aktif()) return;
            perbaruiSaved(d);
            isiForm(d);
        }).catch(function () {
            $('#ketStatus').text('Gagal memuat pengaturan. Muat ulang halaman.');
            toast('error', 'Gagal memuat pengaturan.');
        });
    }

    /* ---------- simpan ---------- */
    function simpan() {
        const n = nilaiForm();
        const galat = validasi(n);
        bersihkanError();
        if (Object.keys(galat).length) {
            tampilError(galat);
            toast('error', 'Periksa kembali isian yang ditandai.');
            return;
        }

        const fd = new FormData();
        Object.keys(n).forEach(function (k) {
            fd.append(k, typeof n[k] === 'boolean' ? (n[k] ? '1' : '0') : n[k]);
        });
        if (logoBaru) fd.append('logo', logoBaru);

        const $btn = $('#btnSimpan');
        const labelAsal = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...');

        ajax(cfg.save, 'POST', fd, true).then(function (res) {
            perbaruiSaved(res.data);
            isiForm(res.data);
            toast('success', res.message);
        }).catch(function (xhr) {
            const r = (xhr && xhr.responseJSON) || {};
            if (r.errors) {
                const peta = {};
                Object.keys(r.errors).forEach(function (k) { peta[k] = r.errors[k][0]; });
                tampilError(peta);
            }
            toast('error', pesanError(xhr));
        }).finally(function () {
            $btn.html(labelAsal);
            cekPerubahan();
        });
    }

    /* ---------- saklar voting (langsung, dengan konfirmasi) ---------- */
    function alarmBerakhir() {
        Modal.fire({
            html: header('fa-solid fa-triangle-exclamation', 'bg-amber-50 text-amber-600', 'Jadwal voting sudah berakhir',
                         'Saklar tidak bisa dinyalakan karena waktu selesai (' + esc(fmtWaktu(parseMs(saved.waktu_selesai))) + ') sudah lewat. '
                         + 'Perpanjang waktu selesai, simpan, lalu nyalakan saklar.'),
            confirmButtonText: 'Mengerti'
        }).then(function () { $('#fSelesai').trigger('focus'); });
    }

    $(document).on('change' + NS, '#swVoting', function () {
        const el = this;
        const mau = el.checked;
        el.checked = !mau;                 // kembalikan dulu; berubah setelah server menyetujui
        if (!saved) return;

        const selesai = parseMs(saved.waktu_selesai);
        if (mau && selesai !== null && sekarangMs() >= selesai) { alarmBerakhir(); return; }

        (mau ? Modal : ModalDanger).fire({
            html: header(mau ? 'fa-solid fa-play' : 'fa-solid fa-lock',
                         mau ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600',
                         mau ? 'Nyalakan voting?' : 'Matikan voting?',
                         mau ? 'Siswa bisa memilih selama waktu sekarang berada di dalam jadwal.'
                             : 'Siswa tidak bisa memilih sampai saklar dinyalakan lagi.'),
            showCancelButton: true,
            confirmButtonText: mau ? 'Nyalakan' : 'Matikan',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => ajax(cfg.voting, 'POST', { aktif: mau ? 1 : 0 })
                .catch((xhr) => { Swal.showValidationMessage(pesanError(xhr)); return false; })
        }).then(function (result) {
            if (!result.isConfirmed || !result.value) return;
            perbaruiSaved(result.value.data);
            toast('success', result.value.message);
        });
    });

    /* ---------- lihat pilihan siswa (view_voted) ---------- */
    $(document).on('change' + NS, '#swViewVoted', function () {
        const el = this;
        if (el.checked) {
            el.checked = false;            // tunggu konfirmasi
            ModalDanger.fire({
                html: header('fa-solid fa-eye', 'bg-amber-50 text-amber-600', 'Tampilkan pilihan siswa?',
                             'Admin akan bisa melihat siapa memilih siapa di halaman Siswa. Pemilihan bersifat rahasia, jadi aktifkan hanya bila diperlukan.'),
                showCancelButton: true,
                confirmButtonText: 'Ya, aktifkan',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) $('#swViewVoted').prop('checked', true);
                sinkronPeringatan();
                cekPerubahan();
            });
        } else {
            sinkronPeringatan();
            cekPerubahan();
        }
    });

    /* ---------- events ---------- */
    $(document).on('input' + NS, '#fNama, #fJudul, #fTahun, #fMulai, #fSelesai', cekPerubahan);
    $(document).on('change' + NS, '#fMulai, #fSelesai', cekPerubahan);

    $(document).on('change' + NS, '#fLogo', function () {
        const f = this.files && this.files[0];
        if (!f) return;
        if (['image/jpeg', 'image/png', 'image/webp'].indexOf(f.type) === -1) {
            toast('error', 'Logo harus berformat JPG, PNG, atau WEBP.');
            this.value = '';
            return;
        }
        if (f.size > 2 * 1024 * 1024) {
            toast('error', 'Ukuran logo maksimal 2 MB.');
            this.value = '';
            return;
        }
        logoBaru = f;
        if (logoObjUrl) URL.revokeObjectURL(logoObjUrl);
        logoObjUrl = URL.createObjectURL(f);
        $('#logoPreview').html('<img src="' + logoObjUrl + '" alt="Pratinjau logo" class="w-full h-full object-contain">');
        $('#namaLogo').text(f.name + ' (belum disimpan)');
        cekPerubahan();
    });

    $(document).on('click' + NS, '#btnSimpan', simpan);
    $(document).on('click' + NS, '#btnBatal', function () { if (saved) isiForm(saved); });

    /* ---------- init ---------- */
    muat();
    // status berubah sendiri seiring waktu (terjadwal -> berlangsung -> berakhir)
    window.__settingsTimer = setInterval(function () {
        if (aktif()) renderStatus(); else clearInterval(window.__settingsTimer);
    }, 15000);
})();
</script>
@endscript