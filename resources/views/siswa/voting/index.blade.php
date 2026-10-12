@php
  $pengaturan = \App\Models\Settings::ambil();
  $votingBuka = $pengaturan->votingBuka();
@endphp

@extends('layouts.siswa.app')

@section('cdn')
  <!-- CDN : Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind-config.js') }}"></script>

  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @section('title', 'Voting - E-Vote OSIS')
@endsection

@section('content')
  <div class="space-y-4 text-center">
    <div class="mx-auto space-y-3 max-w-7xl text-center">
      <div class="py-6 space-y-3 md:space-y-4">

        <div class="flex justify-center">
          <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl md:w-20 md:h-20 transition-all shadow-md">
            <i class="text-[32px] md:text-[40px] fa-solid fa-users"></i>
          </div>
        </div>

        <h1 class="text-3xl font-extrabold text-center md:text-5xl text-slate-800">Voting <span class="text-[#FACC15]">Kandidat OSIS</span></h1>
        <p class="text-gray-600">Pilih calon ketua OSIS yang menurut Anda paling tepat</p>

        <div class="flex gap-4 items-start p-4 my-6 rounded-2xl border-l-4 transition-all shadow-lg bg-amber-300/20 border-amber-300/80 border-l-amber-300 sm:p-5 sm:items-center">
          <div class="flex justify-center items-center w-10 h-10 text-white bg-amber-400 rounded-xl shrink-0">
            <i class="text-[20px] fa-solid fa-info"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold leading-snug text-slate-900 sm:text-base">Penting Diperhatikan!</h4>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5 font-medium leading-relaxed text-left">
              Anda hanya dapat memilih satu kali dan tidak dapat mengubah pilihan setelahnya! Klik kartu kandidat untuk melihat visi &amp; misi selengkapnya.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="flex relative gap-4 justify-center items-center max-w-7xl">
    <div id="scroll-container" class="grid grid-cols-1 gap-4 h-full md:grid-cols-3">
      @foreach ($kandidat as $index => $row)
        <div class="flex overflow-hidden relative flex-col w-full h-full bg-white rounded-3xl border-2 transition-all duration-300 shadow-md cursor-pointer hover:-translate-y-2 md:hover:-translate-y-4 hover:shadow-xl hover:border-blue-500 border-slate-300/80 group kandidat-item"
          data-id="{{ $row->id }}"
          data-nama="{{ $row->nama }}"
          data-image="{{ $row->image_url }}"
          tabindex="0" role="button"
          aria-haspopup="dialog"
          aria-label="Lihat visi dan misi {{ $row->nama }}">

          <div class="relative border-b-2 overflow-hidden bg-slate-800 aspect-[4/3] shrink-0">
            <img src="{{ $row->image_url }}" alt="{{ $row->nama }}" width="400" height="300"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
          </div>

          <div class="grid gap-4 p-4">
            <h3 class="text-2xl font-bold text-center text-slate-800 shrink-0">{{ $row->nama }}</h3>

            <div class="flex justify-center shrink-0">
              <span class="p-2 py-1 text-sm font-bold tracking-widest text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-full border">
                Calon Ketua OSIS
              </span>
            </div>

            <div class="overflow-hidden z-10 space-y-4 bg-white shrink-0">
              {{-- Tombol Visi & Misi (tanpa data duplikat, data diambil dari kartu) --}}
              <button type="button"
                class="flex gap-2 justify-center items-center px-5 py-3 w-full text-sm font-semibold text-yellow-600 bg-white rounded-xl border-2 border-yellow-600 transition-colors btn-detail hover:bg-yellow-600 hover:text-white">
                <i class="text-[16px] fa-solid fa-book-open"></i>
                Lihat Visi &amp; Misi
              </button>

              @if ($votingBuka)
                <button type="button"
                  data-id="{{ $row->id }}"
                  data-nama="{{ $row->nama }}"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full text-sm font-semibold text-blue-600 bg-white rounded-xl border-2 border-blue-600 transition-colors btn-vote hover:bg-blue-600 hover:text-white">
                  <i class="text-[16px] fa-solid fa-check-to-slot"></i>
                  Pilih Kandidat Ini
                </button>
              @else
                <button type="button" disabled aria-disabled="true" title="{{ $pengaturan->pesanTutup() }}"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full text-sm font-semibold rounded-xl border-2 cursor-not-allowed text-slate-400 bg-slate-100 border-slate-300">
                  <i class="text-[16px] fa-solid fa-lock"></i>
                  Vote sudah ditutup
                </button>
              @endif
            </div>

            <div class="hidden kandidat-visi">{{ $row->visi }}</div>
            <div class="hidden kandidat-misi">{{ $row->misi }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection

@section('script')
<script>
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  $(function () {
    "use strict";

    const $popupModal   = $('#popupModal');
    const $modalVoteBtn = $('#modalVoteBtn');
    let voting = false; // cegah klik ganda
    const VOTING_BUKA = @json($votingBuka);
    const PESAN_TUTUP = @json($pengaturan->pesanTutup());

    // ================== POPUP VISI & MISI ==================
    function openKandidatModal($card) {
      $('#modalTitle').text($card.data('nama'));
      $('#modalImg').attr('src', $card.data('image')).attr('alt', 'Kandidat ' + $card.data('nama')).attr('width', '100').attr('height', '100');
      $('#modalVisi').text($.trim($card.find('.kandidat-visi').text()) || '-').css('white-space', 'pre-line');
      $('#modalMisi').text($.trim($card.find('.kandidat-misi').text()) || '-').css('white-space', 'pre-line');
      $modalVoteBtn.data('id', $card.data('id')).data('nama', $card.data('nama'));
      if (!VOTING_BUKA) {
        $modalVoteBtn.prop('disabled', true)
          .html('<i class="text-[16px] fa-solid fa-lock"></i> Vote sudah ditutup')
          .removeClass('text-blue-600 bg-white border-blue-600 hover:bg-blue-600 hover:text-white')
          .addClass('text-slate-400 bg-slate-100 border-slate-300 cursor-not-allowed');
      }
      $popupModal.removeClass('hidden').addClass('flex');
      $('body').addClass('overflow-hidden');
    }

    function closeKandidatModal() {
      $popupModal.addClass('hidden').removeClass('flex');
      $('body').removeClass('overflow-hidden');
    }

    // klik kartu -> buka popup (kecuali klik pada tombol)
    $('#scroll-container').on('click', '.kandidat-item', function (e) {
      if ($(e.target).closest('button').length) return;
      openKandidatModal($(this));
    });

    $('#scroll-container').on('keydown', '.kandidat-item', function (e) {
      if (e.target !== this) return;
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openKandidatModal($(this));
      }
    });

    // tombol "Lihat Visi & Misi" -> ambil data dari kartu induknya
    $('#scroll-container').on('click', '.btn-detail', function (e) {
      e.stopPropagation();
      openKandidatModal($(this).closest('.kandidat-item'));
    });

    $('#closeBtn').on('click', closeKandidatModal);
    $popupModal.on('click', function (e) {
      if (e.target === this) closeKandidatModal();
    });
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && $popupModal.hasClass('flex') && !EVoteDialog.isOpen()) closeKandidatModal();
    });

    // ================== VOTING ==================
    // tombol vote di kartu
    $('#scroll-container').on('click', '.btn-vote', function (e) {
      e.stopPropagation();
      selectKandidat($(this).data('id'), $(this).data('nama'));
    });

    // tombol vote di popup
    $modalVoteBtn.on('click', function () {
      const id = $(this).data('id'), nama = $(this).data('nama');
      closeKandidatModal();
      selectKandidat(id, nama);
    });

    function selectKandidat(idKandidat, nama) {
      if (voting) return;
      if (!VOTING_BUKA) {
        EVoteDialog.error({ title: 'Voting ditutup', text: PESAN_TUTUP });
        return;
      }
      if (!idKandidat || !nama) {
        EVoteDialog.error({ text: 'Data kandidat tidak ditemukan. Silakan muat ulang halaman.' });
        return;
      }

      EVoteDialog.confirm({
        title: 'Pilih ' + nama + '?',
        text: 'Pilihan tidak dapat diubah setelah dikonfirmasi.',
        confirmText: 'Ya, Pilih!',
        cancelText: 'Batal'
      }).then(function (ok) {
        if (!ok) return;
        voting = true;
        EVoteDialog.loading('Memproses...', 'Suara Anda sedang disimpan.');

        $.ajax({
          url: "/siswa/vote",
          method: "POST",
          data: { id_kandidat: idKandidat },
          dataType: "json"
        })
        .done(function () {
          EVoteDialog.success({
            text: 'Vote untuk ' + nama + ' berhasil disimpan.',
            seconds: 5
          }).then(function () {
            location.href = "/siswa/logout";
          });
        })
        .fail(function (jqXHR) {
          voting = false;
          let msg = (jqXHR.responseJSON && jqXHR.responseJSON.message) || "Terjadi kesalahan saat memproses voting.";
          if (jqXHR.status === 419) msg = "Sesi telah berakhir. Silakan muat ulang halaman.";
          EVoteDialog.error({ text: msg });
        });
      });
    }
  });
</script>
@endsection