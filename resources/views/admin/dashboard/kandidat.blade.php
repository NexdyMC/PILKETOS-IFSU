<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div id="kandidat-app" class="p-4 sm:p-8" data-url-list="{{ route('kandidat.api.list') }}" data-url-store="{{ route('kandidat.api.store') }}" data-url-base="{{ url('admin/kandidat') }}" data-csrf="{{ csrf_token() }}">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="font-semibold font-display text-navy-900">Daftar Pasangan Calon</h2>
      <p wire:ignore id="kandidatCount" class="mt-1 text-sm text-slate-500">Memuat data...</p>
    </div>
  </div>

	<!-- Tombol Tambah Kandidat -->
  <button type="button" id="btnTambahKandidat" class="w-full border-2 border-dashed border-slate-300 hover:border-primary-500 bg-white hover:bg-primary-50/40 rounded-2xl min-h-[120px] flex flex-col items-center justify-center gap-2 p-6 text-center transition-all duration-300 transform hover:-translate-y-1 group mb-6">
    <div class="flex items-center justify-center transition-colors rounded-full w-14 h-14 bg-primary-50 group-hover:bg-primary-100">
      <i data-lucide="plus" class="w-7 h-7 text-primary-600"></i>
    </div>
    <p class="font-semibold font-display text-navy-900">Tambah Kandidat Baru</p>
    <p class="text-xs text-slate-500 max-w-[220px] leading-relaxed">Daftarkan pasangan calon ketua &amp; wakil ketua OSIS</p>
  </button>

	<!-- Grid kartu: diisi oleh JS -->
  <div wire:ignore id="kandidatGrid" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"></div>

	<!-- Modal Tambah/Edit -->
  <div wire:ignore id="modalWrapKandidat" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 modal-overlay-bg bg-navy-950/60 backdrop-blur-sm" data-close-modal></div>
    <div class="modal-panel relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
      <div class="kandidat-scroll max-h-[90vh] overflow-y-auto p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
          <h3 id="modalKandidatTitle" class="text-lg font-semibold font-display text-navy-900">Tambah Kandidat Baru</h3>
          <button type="button" data-close-modal class="flex items-center justify-center rounded-full w-9 h-9 hover:bg-slate-100 text-slate-400">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <form id="formKandidat" class="space-y-5" novalidate>
          <input type="hidden" name="id" id="inputKandidatId" value="">
          <div>
            <label class="text-sm font-medium text-navy-700 mb-1.5 block">Foto Paslon</label>
            <div class="grid items-center grid-cols-4 gap-4">
              <img id="previewFotoKandidat" src="https://placehold.co/120x120/EEF3FF/1E3A8A?text=Foto" alt="Preview" class="object-cover w-full border aspect-square rounded-xl border-slate-200">
              <div id="dropzoneFoto" class="flex flex-col items-center justify-center col-span-3 p-4 text-center transition-colors border-2 border-dashed cursor-pointer border-slate-300 bg-slate-50 hover:bg-primary-50 hover:border-primary-400 rounded-xl">
                <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400 mb-1.5"></i>
                <span class="text-sm font-semibold text-navy-900">Klik atau tarik foto ke sini</span>
                <span class="text-xs text-slate-500 mt-0.5">PNG, JPG, atau WEBP (maks 2MB)</span>
                <input type="file" name="foto" id="inputFotoKandidat" accept="image/png, image/jpeg, image/webp" class="hidden">
              </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5">Saat edit, kosongkan bagian ini jika tidak ingin mengganti foto.</p>
          </div>
          <div>
            <label class="text-sm font-medium text-navy-700 mb-1.5 block">Nama Pasangan Calon</label>
            <input type="text" name="nama" id="inputNamaKandidat" required placeholder="cth. Aditya Pratama & Salsa Nabila" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none text-sm">
          </div>
          <div>
            <label class="text-sm font-medium text-navy-700 mb-1.5 block">Visi</label>
            <textarea name="visi" id="inputVisiKandidat" rows="3" required placeholder="Tuliskan visi paslon..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none text-sm resize-none"></textarea>
          </div>
          <div>
            <label class="text-sm font-medium text-navy-700 mb-1.5 block">Misi</label>
            <textarea name="misi" id="inputMisiKandidat" rows="3" required placeholder="Tuliskan misi paslon..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none text-sm resize-none"></textarea>
          </div>
          <div class="flex items-center gap-3 pt-2">
            <button type="button" data-close-modal class="flex-1 px-5 py-3 text-sm font-semibold transition border rounded-xl border-slate-200 text-navy-700 hover:bg-slate-50">Batal</button>
            <button type="submit" id="btnSimpanKandidat" class="flex-1 px-5 py-3 text-sm font-semibold text-white transition btn-cta rounded-xl bg-primary-700 hover:bg-primary-600 disabled:opacity-60">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <style>
    /* scrollbar custom untuk modal kandidat */
    .kandidat-scroll {
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 transparent;
    }

    .kandidat-scroll::-webkit-scrollbar {
      width: 10px;
    }

    .kandidat-scroll::-webkit-scrollbar-track {
      background: transparent;
      margin: 20px 0;
    }

    .kandidat-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border: 3px solid transparent;
      background-clip: padding-box;
      border-radius: 999px;
    }

    .kandidat-scroll::-webkit-scrollbar-thumb:hover {
      background-color: #94a3b8;
    }
  </style>
</div>

@script
<script>
(function () {
  const $root = $('#kandidat-app');
  if (!$root.length) return;

  const NS = '.kandidat';
  const cfg = {
    list:  $root.attr('data-url-list'),
    store: $root.attr('data-url-store'),
    base:  $root.attr('data-url-base'),
    csrf:  $root.attr('data-csrf'),
  };
  const PLACEHOLDER = 'https://placehold.co/120x120/EEF3FF/1E3A8A?text=Foto';
  const DZ_AKTIF = 'border-primary-500 bg-primary-50';

  let kandidatList = [];

  // Lepas semua listener lama sebelum memasang yang baru (aman untuk wire:navigate)
  $(document).off(NS);

  /* ---------- helpers ---------- */
  const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
  ));
  const nl2br = (s) => esc(s).replace(/\r?\n/g, '<br>');
  const refreshIcons = () => { if (window.lucide) window.lucide.createIcons(); };
  const aktif = () => $('#kandidat-app').length > 0;

  function toast(icon, title) {
    Swal.fire({
      toast: true, position: 'top-end', icon, title,
      showConfirmButton: false, timer: 2500, timerProgressBar: true,
    });
  }

  function showError(xhr) {
    const res = xhr.responseJSON || {};
    const detail = res.errors
      ? Object.values(res.errors).flat().join('\n')
      : (res.message || 'Terjadi kesalahan pada server.');
    Swal.fire({ icon: 'error', title: 'Gagal', text: detail });
  }

  // $.ajax standar; FormData dikirim apa adanya (tanpa processData/contentType)
  function request(url, method, formData) {
    const opts = {
      url, method, dataType: 'json',
      headers: { 'X-CSRF-TOKEN': cfg.csrf, 'X-Requested-With': 'XMLHttpRequest' },
    };
    if (formData) {
      opts.data = formData;
      opts.processData = false;
      opts.contentType = false;
    }
    return $.ajax(opts);
  }

  /* ---------- render ---------- */
  function cardHtml(row, i) {
    const noUrut = String(i + 1).padStart(2, '0');
    return `
      <div class="bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(15,23,42,0.06)] border-2 border-slate-100 card-hover flex flex-col hover:border-blue-500">
        <div class="relative overflow-hidden bg-slate-800 aspect-[4/3]">
          <img src="${esc(row.image_url)}" alt="Kandidat ${esc(row.nama)}" class="object-cover w-full h-full">
        </div>

        <div class="flex-1 p-5">
          <p class="mb-2 text-xl font-bold text-center font-display line-clamp-1 text-navy-900">${esc(row.nama)}</p>

          <div class="flex justify-center mb-5">
            <span class="px-3 py-1 text-xs font-bold tracking-widest border rounded-full text-accent-500 bg-accent-400/15 border-accent-400/30">
              Calon Ketua OSIS ${noUrut}
            </span>
          </div>

          <div class="space-y-3">
            <div class="p-3 border bg-slate-50 rounded-xl border-slate-200">
              <h4 class="text-xs font-bold uppercase text-primary-600 mb-1 flex items-center gap-1.5">
                <i data-lucide="compass" class="w-3.5 h-3.5"></i> Visi
              </h4>
              <p class="text-sm leading-relaxed text-slate-600 line-clamp-2">${nl2br(row.visi)}</p>
            </div>
            <div class="p-3 border bg-slate-50 rounded-xl border-slate-200">
              <h4 class="text-xs font-bold uppercase text-primary-600 mb-1 flex items-center gap-1.5">
                <i data-lucide="target" class="w-3.5 h-3.5"></i> Misi
              </h4>
              <p class="text-sm leading-relaxed text-slate-600 line-clamp-2">${nl2br(row.misi)}</p>
            </div>
          </div>

          <div class="flex items-center gap-2 mt-5">
            <button type="button" data-action="edit" data-id="${esc(row.id)}"
                    class="btn-cta flex-1 flex items-center justify-center gap-1.5 text-sm font-medium px-3 py-2.5 rounded-xl bg-primary-50 text-primary-700 hover:bg-primary-100">
              <i data-lucide="pencil" class="w-4 h-4"></i> Edit
            </button>
            <button type="button" data-action="delete" data-id="${esc(row.id)}"
                    class="btn-cta flex-1 flex items-center justify-center gap-1.5 text-sm font-medium px-3 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100">
              <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
          </div>
        </div>
      </div>`;
  }

  function render() {
    if (!aktif()) return;
    $('#kandidatCount').text(`${kandidatList.length} kandidat terdaftar untuk periode ini`);
    $('#kandidatGrid').html(
      kandidatList.length
        ? kandidatList.map(cardHtml).join('')
        : '<p class="col-span-full text-center text-sm text-slate-500 py-10">Belum ada kandidat.</p>'
    );
    refreshIcons();
  }

  function loadKandidat() {
    return request(cfg.list, 'GET')
      .done((res) => { kandidatList = res.data; render(); })
      .fail((xhr) => {
        console.error('Gagal ambil data kandidat:', xhr);
        if (aktif()) $('#kandidatCount').text('Gagal memuat data.');
        showError(xhr);
      });
  }

  /* ---------- modal ---------- */
  function openModal(mode, data) {
    $('#formKandidat')[0].reset();
    $('#previewFotoKandidat').attr('src', PLACEHOLDER);

    if (mode === 'edit' && data) {
      $('#modalKandidatTitle').text('Edit Kandidat');
      $('#inputKandidatId').val(data.id);
      $('#inputNamaKandidat').val(data.nama);
      $('#inputVisiKandidat').val(data.visi);
      $('#inputMisiKandidat').val(data.misi);
      if (data.image) $('#previewFotoKandidat').attr('src', data.image_url);
    } else {
      $('#modalKandidatTitle').text('Tambah Kandidat Baru');
      $('#inputKandidatId').val('');
    }

    $('#modalWrapKandidat').removeClass('hidden');
    $('body').css('overflow', 'hidden');
  }

  function closeModal() {
    $('#modalWrapKandidat').addClass('hidden');
    $('body').css('overflow', '');
  }

  function tampilkanPreview(file) {
    if (!file) return;
    if (!file.type.startsWith('image/')) {
      Swal.fire({ icon: 'error', title: 'Format tidak sesuai', text: 'Tolong unggah file berupa gambar (PNG/JPG/WEBP).' });
      $('#inputFotoKandidat').val('');
      return;
    }
    if (file.size > 2 * 1024 * 1024) {
      Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Ukuran foto maksimal 2MB.' });
      $('#inputFotoKandidat').val('');
      return;
    }
    const reader = new FileReader();
    reader.onload = (e) => $('#previewFotoKandidat').attr('src', e.target.result);
    reader.readAsDataURL(file);
  }

  function konfirmasiHapus(row) {
    Swal.fire({
      title: 'Hapus Kandidat?',
      text: `Apakah Anda yakin ingin menghapus ${row.nama} dari daftar kandidat?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
    }).then((result) => {
      if (!result.isConfirmed) return;
      request(`${cfg.base}/${encodeURIComponent(row.id)}`, 'DELETE')
        .done((res) => { toast('success', res.message); loadKandidat(); })
        .fail(showError);
    });
  }

  /* ---------- events (delegasi di document, namespace .kandidat) ---------- */
  $(document).on('click' + NS, '[data-close-modal]', closeModal);

  $(document).on('click' + NS, '#btnTambahKandidat', () => openModal('add'));

  $(document).on('click' + NS, '#dropzoneFoto', function (e) {
    if (e.target.id === 'inputFotoKandidat') return; // cegah loop klik
    $('#inputFotoKandidat').trigger('click');
  });

  $(document).on('click' + NS, '#kandidatGrid [data-action]', function () {
    const id  = $(this).attr('data-id');
    const row = kandidatList.find((k) => String(k.id) === id);
    if (!row) return;
    if ($(this).attr('data-action') === 'edit') openModal('edit', row);
    else konfirmasiHapus(row);
  });

  $(document).on('keydown' + NS, (e) => {
    if (e.key === 'Escape' && aktif()) closeModal();
  });

  $(document).on('change' + NS, '#inputFotoKandidat', function () {
    if (this.files && this.files.length > 0) tampilkanPreview(this.files[0]);
  });

  $(document).on('dragover' + NS, '#dropzoneFoto', function (e) {
    e.preventDefault();
    $(this).addClass(DZ_AKTIF);
  });
  $(document).on('dragleave' + NS, '#dropzoneFoto', function (e) {
    e.preventDefault();
    $(this).removeClass(DZ_AKTIF);
  });
  $(document).on('drop' + NS, '#dropzoneFoto', function (e) {
    e.preventDefault();
    $(this).removeClass(DZ_AKTIF);
    const files = e.originalEvent.dataTransfer.files;
    if (files && files.length > 0) {
      $('#inputFotoKandidat')[0].files = files;
      tampilkanPreview(files[0]);
    }
  });

  $(document).on('submit' + NS, '#formKandidat', function (e) {
    e.preventDefault();

    const $btn =$('#btnSimpanKandidat');
    if ($btn.prop('disabled')) {
        return false; 
    }

    const id  = $('#inputKandidatId').val();
    const fd  = new FormData(this);
    fd.delete('id');

    let url = cfg.store;
    if (id) {
      url = `${cfg.base}/${encodeURIComponent(id)}`;
      fd.append('_method', 'PUT'); 
    }

    const originalText = $btn.text();$btn.prop('disabled', true).text('Menyimpan...');

    request(url, 'POST', fd)
      .done((res) => { closeModal(); toast('success', res.message); loadKandidat(); })
      .fail(showError)
      .always(() => {
          $btn.prop('disabled', false).text(originalText);
      });
  });

  /* ---------- init ---------- */
  $(function () {
    refreshIcons();
    loadKandidat();
  });
})();
</script>
@endscript