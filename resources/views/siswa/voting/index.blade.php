@extends('layouts.siswa.app')

@section('cdn')
  <!-- CDN : Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind.config.js') }}"></script>

  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @section('title', 'Voting - E-Vote OSIS')
@endsection

@section('content')

<div class="space-y-12 text-center">
  
  <section id="salam" class="overflow-hidden relative rounded-3xl border-2 shadow-xl bg-navy-950 border-slate-800">
    <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full blur-3xl bg-amber-400/20"></div>
    <div class="absolute -left-10 -bottom-20 w-56 h-56 rounded-full blur-3xl bg-amber-300/10"></div>

    <div class="grid relative gap-6 items-center p-6 text-left md:grid-cols-3 md:p-10">
      <div class="space-y-4 md:col-span-2">
        @if ($votingBuka)
          <span class="inline-flex gap-2 items-center px-3 py-1 text-xs font-bold text-emerald-300 rounded-full border bg-emerald-500/15 border-emerald-400/40">
            <i class="fa-solid fa-circle text-[8px]"></i> Voting sedang dibuka
          </span>
        @else
          <span class="inline-flex gap-2 items-center px-3 py-1 text-xs font-bold text-red-300 rounded-full border bg-red-500/15 border-red-400/40">
            <i class="fa-solid fa-lock text-[10px]"></i> Voting ditutup
          </span>
        @endif

        <div>
          <p class="text-sm font-semibold md:text-base text-brand-yellow">{{ $sapaan }},</p>
          <h2 class="text-3xl font-extrabold uppercase text-white md:text-5xl">{{ $siswa['nama'] }}</h2>
          <p class="mt-1 text-sm font-semibold uppercase text-slate-400">Kelas {{ $siswa['kelas'] }}</p>
        </div>

        <p class="text-sm leading-relaxed md:text-base text-slate-300">
          Selamat datang di <span class="font-bold text-white">{{ $pengaturan->judul_pemilihan }}</span>
          {{ $pengaturan->nama_sekolah }}
          @if ($pengaturan->tahun_ajaran) tahun ajaran {{ $pengaturan->tahun_ajaran }} @endif.
          Suara Anda menentukan arah OSIS ke depan, gunakan hak pilih dengan bijak.
        </p>

        <a href="#voting"
          class="inline-flex gap-2 items-center px-5 py-3 text-sm font-semibold text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg">
          <i class="text-[16px] fa-solid fa-check-to-slot"></i> Mulai Voting
        </a>
      </div>

      <div class="p-5 space-y-3 rounded-2xl border bg-white/5 border-slate-700">
        <h3 class="flex gap-2 items-center text-sm font-bold uppercase text-brand-yellow">
          <i class="fa-solid fa-calendar-days"></i> Jadwal Voting
        </h3>
        @if ($pengaturan->waktu_mulai && $pengaturan->waktu_selesai)
          <div>
            <p class="text-xs text-slate-400">Mulai</p>
            <p class="text-sm font-semibold text-white">{{ \Carbon\Carbon::parse($pengaturan->waktu_mulai)->translatedFormat('d M Y, H:i') }} WIB</p>
          </div>
          <div>
            <p class="text-xs text-slate-400">Selesai</p>
            <p class="text-sm font-semibold text-white">{{ \Carbon\Carbon::parse($pengaturan->waktu_selesai)->translatedFormat('d M Y, H:i') }} WIB</p>
          </div>
        @else
          <p class="text-sm text-slate-400">Jadwal belum ditentukan.</p>
        @endif
      </div>
    </div>
  </section>


  <section id="cara-voting" class="space-y-6 text-center scroll-mt-24">
    <div class="space-y-3">
      <div class="flex justify-center">
        <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md md:w-20 md:h-20">
          <i class="text-[32px] md:text-[40px] fa-solid fa-list-check"></i>
        </div>
      </div>
      <h2 class="text-3xl font-extrabold md:text-5xl text-slate-800">Cara <span class="text-[#FACC15]">Voting</span></h2>
      <p class="text-gray-600">Ikuti langkah mudah berikut agar suara Anda sah</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      @foreach ($langkahVoting as $i => $langkah)
        <div class="relative p-5 space-y-3 bg-white rounded-3xl border-2 shadow-md transition-all duration-300 border-slate-300/80 hover:-translate-y-2 hover:shadow-xl hover:border-blue-500">
          <span class="flex absolute top-4 right-4 justify-center items-center w-8 h-8 text-sm font-extrabold rounded-full text-slate-800 bg-amber-300/40">{{ $i + 1 }}</span>
          <div class="flex justify-center items-center mx-auto w-14 h-14 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md">
            <i class="text-[24px] fa-solid {{ $langkah['ikon'] }}"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-800">{{ $langkah['judul'] }}</h3>
          <p class="text-sm leading-relaxed text-slate-600">{{ $langkah['deskripsi'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  <section id="visi-misi" class="space-y-6 text-center scroll-mt-24">
    <div class="space-y-3">
      <div class="flex justify-center">
        <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md md:w-20 md:h-20">
          <i class="text-[32px] md:text-[40px] fa-solid fa-compass"></i>
        </div>
      </div>
      <h2 class="text-3xl font-extrabold md:text-5xl text-slate-800">Visi &amp; Misi <span class="text-[#FACC15]">Kandidat</span></h2>
      <p class="text-gray-600">Kenali program kerja setiap calon sebelum menentukan pilihan</p>
    </div>

    <div class="grid grid-cols-1 gap-4 text-left md:grid-cols-3">
      @foreach ($kandidat as $row)
        <div class="flex flex-col p-5 space-y-4 bg-white rounded-3xl border-2 shadow-md border-slate-300/80">
          <div class="flex gap-3 items-center">
            <img src="{{ $row->image_url }}" alt="{{ $row->nama }}" width="64" height="64" loading="lazy"
              class="w-16 h-16 rounded-xl border-2 object-cover shrink-0 border-slate-200 bg-slate-800">
            <div class="min-w-0">
              <h3 class="text-lg font-bold leading-tight text-slate-800">{{ $row->nama }}</h3>
              <span class="inline-block px-2 py-0.5 mt-1 text-xs font-bold tracking-wider text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-full">Calon Ketua OSIS</span>
            </div>
          </div>

          <div class="p-4 bg-yellow-50 rounded-xl border-2 border-yellow-200">
            <h4 class="flex gap-1 items-center mb-1 text-base font-bold uppercase text-slate-900">
              <i class="text-[18px] text-yellow-600 fa-solid fa-compass"></i> Visi
            </h4>
            <div class="overflow-y-auto max-h-40 text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ trim($row->visi) ?: '-' }}</div>
          </div>

          <div class="p-4 bg-blue-50 rounded-xl border-2 border-blue-200">
            <h4 class="flex gap-1 items-center mb-1 text-base font-bold uppercase text-slate-900">
              <i class="text-[18px] text-blue-600 fa-solid fa-bullseye"></i> Misi
            </h4>
            <div class="overflow-y-auto max-h-40 text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ trim($row->misi) ?: '-' }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </section>
  
  <section id="voting" class="scroll-mt-24">
      <div class="py-6 space-y-3 md:space-y-4">

        <div class="flex justify-center">
          <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl md:w-20 md:h-20 transition-all shadow-md">
            <i class="text-[32px] md:text-[40px] fa-solid fa-users"></i>
          </div>
        </div>

        <h2 class="text-3xl font-extrabold text-center md:text-5xl text-slate-800">Voting <span class="text-[#FACC15]">Kandidat OSIS</span></h2>
        <p class="text-gray-600">Pilih calon ketua OSIS yang menurut Anda paling tepat</p>

        <div class="flex gap-4 items-start p-4 my-6 rounded-2xl border-l-4 transition-all shadow-lg bg-amber-300/20 border-amber-300/80 border-l-amber-300 sm:p-5 sm:items-center">
          <div class="flex justify-center items-center w-10 h-10 text-white bg-amber-400 rounded-xl shrink-0">
            <i class="text-[20px] fa-solid fa-info"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-left leading-snug text-slate-900 sm:text-base">Penting Diperhatikan!</h4>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5 font-medium leading-relaxed text-left">
              Anda hanya dapat memilih satu kali dan tidak dapat mengubah pilihan setelahnya! Klik kartu kandidat untuk melihat visi &amp; misi selengkapnya.
            </p>
          </div>
        </div>
      </div>

  <div class="flex relative gap-4 justify-center items-center max-w-7xl">
    <div id="scroll-container" class="flex flex-wrap gap-4 h-full justify-center">
      @forelse ($kandidat as $index => $row)
        <div class="flex overflow-hidden relative flex-col max-w-[320px] w-full h-full bg-white rounded-3xl border-2 transition-all duration-300 shadow-md cursor-pointer hover:-translate-y-2 md:hover:-translate-y-4 hover:shadow-xl hover:border-blue-500 border-slate-300/80 group kandidat-item"
          data-id="{{ $row->id }}"
          data-nama="{{ $row->nama }}"
          data-image="{{ $row->image_url }}"
          tabindex="0" role="button"
          aria-haspopup="dialog"
          aria-label="Lihat visi dan misi {{ $row->nama }}">

            {{-- Foto (kiri) --}}
            <img src="{{ $row->image_url }}" alt="{{ $row->nama }}" width="176" height="176" loading="lazy"
              class="self-start w-full rounded-2xl border-2 object-cover object-top shadow-sm aspect-square border-slate-200 bg-slate-800 sm:row-span-2">

            {{-- Nama + kelas (kanan atas) --}}
            <div class="flex flex-col justify-center min-w-0">
              <h3 class="text-xl font-bold leading-tight sm:text-3xl text-slate-800">{{ $row->nama }}</h3>

              @if ($row->kelas)
                <p class="flex gap-2 items-center mt-1 text-sm font-semibold uppercase sm:text-base text-slate-500">
                  <i class="text-[14px] text-yellow-600 fa-solid fa-graduation-cap"></i>{{ $row->kelas }}
                </p>
              @endif

              <button type="button"
                class="inline-flex gap-1.5 items-center mt-2 w-fit text-sm font-semibold text-yellow-600 transition-colors btn-detail hover:text-yellow-700 hover:underline">
                <i class="text-[14px] fa-solid fa-book-open"></i> Lihat Visi &amp; Misi
              </button>
            </div>

            {{-- Bar lebar (bawah): tombol pilih --}}
            <div class="col-span-2 sm:col-span-1 sm:col-start-2">
              @if ($votingBuka)
                <button type="button"
                  data-id="{{ $row->id }}"
                  data-nama="{{ $row->nama }}"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full text-sm font-semibold text-blue-600 bg-white rounded-xl border-2 border-blue-600 transition-colors btn-vote hover:bg-blue-600 hover:text-white sm:py-4 sm:text-base">
                  <i class="text-[16px] fa-solid fa-check-to-slot"></i>
                  Pilih Kandidat Ini
                </button>
              @else
                <button type="button" disabled aria-disabled="true" title="{{ $pengaturan->pesanTutup() }}"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full text-sm font-semibold rounded-xl border-2 cursor-not-allowed text-slate-400 bg-slate-100 border-slate-300 sm:py-4 sm:text-base">
                  <i class="text-[16px] fa-solid fa-lock"></i>
                  Vote sudah ditutup
                </button>
              @endif
            </div>

            <div class="hidden kandidat-visi">{{ $row->visi }}</div>
            <div class="hidden kandidat-misi">{{ $row->misi }}</div>
          </div>
        </div>
      @empty
        <div class="flex flex-col gap-4 justify-center items-center w-full p-6 text-center rounded-2xl bg-slate-100 shadow-lg rouderd-xl">
          <i class="text-[48px] text-slate-400 fa-solid fa-circle-exclamation"></i>
          <h3 class="text-lg font-bold text-slate-800">Belum ada kandidat</h3>
          <p class="text-sm text-slate-600">Saat ini belum ada kandidat yang tersedia untuk dipilih.</p>
        </div>
      @endforelse
    </div>
  </section>
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