@extends('layouts.siswa.app')

@section('cdn')
  <!-- CDN : Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.tailwind-config')

  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @section('title', 'Voting - E-Vote OSIS')
@endsection

@section('content')
<div class="space-y-12 md:space-y-16">

  {{-- ============ 1. UCAPAN SALAM ============ --}}
  <section id="salam" class="relative overflow-hidden rounded-3xl border-2 shadow-xl bg-navy-950 border-slate-800 animate-fade-up motion-reduce:animate-none">
    <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full blur-3xl bg-amber-400/20 animate-float motion-reduce:animate-none"></div>
    <div class="absolute -bottom-20 -left-10 w-56 h-56 rounded-full blur-3xl bg-amber-300/10 animate-float motion-reduce:animate-none"></div>

    <div class="grid relative gap-6 items-center p-6 text-left md:grid-cols-3 md:p-10">
      <div class="space-y-4 md:col-span-2">
        @if ($votingBuka)
          <span class="inline-flex gap-2 items-center px-3 py-1 text-xs font-bold rounded-full border text-emerald-300 bg-emerald-500/15 border-emerald-400/40">
            <i class="fa-solid fa-circle text-[8px]"></i> Voting sedang dibuka
          </span>
        @else
          <span class="inline-flex gap-2 items-center px-3 py-1 text-xs font-bold rounded-full border text-red-300 bg-red-500/15 border-red-400/40">
            <i class="fa-solid fa-lock text-[10px]"></i> Voting ditutup
          </span>
        @endif

        <div>
          <p class="text-sm font-semibold md:text-base text-brand-yellow">{{ $sapaan }},</p>
          <h2 class="text-3xl font-extrabold text-white uppercase font-display md:text-5xl">{{ $siswa['nama'] }}</h2>
          <p class="mt-1 text-sm font-semibold uppercase text-slate-400">Kelas {{ $siswa['kelas'] }}</p>
        </div>

        <p class="text-sm leading-relaxed md:text-base text-slate-300">
          Selamat datang di <span class="font-bold text-white">{{ $pengaturan->judul_pemilihan }}</span>
          {{ $pengaturan->nama_sekolah }}
          @if ($pengaturan->tahun_ajaran) tahun ajaran {{ $pengaturan->tahun_ajaran }} @endif.
          Suara Anda menentukan arah OSIS ke depan, gunakan hak pilih dengan bijak.
        </p>

        <a href="#voting"
          class="inline-flex gap-2 items-center px-5 py-3 text-sm font-semibold text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl transition-all shadow-glow hover:-translate-y-0.5">
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

  {{-- ============ 2. CARA VOTING ============ --}}
  <section id="cara-voting" class="space-y-6 text-center scroll-mt-24">
    <div class="space-y-3">
      <div class="flex justify-center">
        <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md md:w-20 md:h-20">
          <i class="text-[32px] md:text-[40px] fa-solid fa-list-check"></i>
        </div>
      </div>
      <h2 class="text-3xl font-extrabold font-display md:text-4xl text-slate-800">Cara <span class="text-[#FACC15]">Voting</span></h2>
      <p class="text-gray-600">Ikuti langkah mudah berikut agar suara Anda sah</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      @foreach ($langkahVoting as $i => $langkah)
        <div class="relative p-5 space-y-3 bg-white rounded-3xl border-2 shadow-md transition-all duration-300 border-slate-300/80 hover:-translate-y-2 hover:shadow-xl hover:border-blue-500 animate-fade-up motion-reduce:animate-none"
          style="animation-delay: {{ $i * 120 }}ms">
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

  {{-- ============ 3. VISI & MISI KANDIDAT ============ --}}
  <section id="visi-misi" class="space-y-6 text-center scroll-mt-24">
    <div class="space-y-3">
      <div class="flex justify-center">
        <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl shadow-md md:w-20 md:h-20">
          <i class="text-[32px] md:text-[40px] fa-solid fa-compass"></i>
        </div>
      </div>
      <h2 class="text-3xl font-extrabold font-display md:text-4xl text-slate-800">Visi &amp; Misi <span class="text-[#FACC15]">Kandidat</span></h2>
      <p class="text-gray-600">Kenali program kerja setiap calon sebelum menentukan pilihan</p>
    </div>

    <div class="grid grid-cols-1 gap-4 text-left md:grid-cols-3">
      @foreach ($kandidat as $row)
        <div class="flex flex-col p-5 space-y-4 bg-white rounded-3xl border-2 shadow-md border-slate-300/80">
          <div class="flex gap-3 items-center">
            <img src="{{ $row->image_url }}" alt="{{ $row->nama }}" width="64" height="64" loading="lazy" class="object-cover object-center w-16 h-16 rounded-xl border-2 shrink-0 border-slate-200 bg-slate-800">
            <div class="min-w-0">
              <h3 class="text-lg font-bold leading-tight text-slate-800">{{ $row->nama }}</h3>
              <span class="inline-block px-2 py-0.5 mt-1 text-xs font-bold tracking-wider text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-full">Calon Ketua OSIS</span>
            </div>
          </div>

          <div class="p-4 bg-yellow-50 rounded-xl border-2 border-yellow-200">
            <h4 class="flex gap-1 items-center mb-1 text-base font-bold uppercase text-slate-900">
              <i class="text-[18px] text-yellow-600 fa-solid fa-compass"></i> Visi
            </h4>
            <div class="overflow-y-auto text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ trim((string) $row->visi) ?: '-' }}</div>
          </div>

          <div class="p-4 bg-blue-50 rounded-xl border-2 border-blue-200">
            <h4 class="flex gap-1 items-center mb-1 text-base font-bold uppercase text-slate-900">
              <i class="text-[18px] text-blue-600 fa-solid fa-bullseye"></i> Misi
            </h4>
            <div class="overflow-y-auto max-h-64 text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ trim((string) $row->misi) ?: '-' }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ============ 4. VOTING ============ --}}
  <section id="voting" class="scroll-mt-24">
    <div class="space-y-4 text-center">
      <div class="mx-auto space-y-3 max-w-7xl text-center">
        <div class="py-6 space-y-3 md:space-y-4">

          <div class="flex justify-center">
            <div class="flex justify-center items-center w-16 h-16 text-white bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl md:w-20 md:h-20 transition-all shadow-md">
              <i class="text-[32px] md:text-[40px] fa-solid fa-users"></i>
            </div>
          </div>

          <h1 class="text-3xl font-extrabold text-center font-display md:text-4xl text-slate-800">Voting <span class="text-[#FACC15]">Kandidat OSIS</span></h1>
          <p class="text-gray-600">Pilih calon ketua OSIS yang menurut Anda paling tepat</p>

          <div class="flex gap-4 items-start p-4 my-6 rounded-2xl border-l-4 transition-all shadow-lg bg-amber-300/20 border-amber-300/80 border-l-amber-300 sm:p-5 sm:items-center">
            <div class="flex justify-center items-center w-10 h-10 text-white bg-amber-400 rounded-xl shrink-0">
              <i class="text-[20px] fa-solid fa-info"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="text-sm font-bold leading-snug text-slate-900 sm:text-base">Penting Diperhatikan!</h4>
              <p class="text-xs sm:text-sm text-slate-600 mt-0.5 font-medium leading-relaxed text-left">
                Anda hanya dapat memilih satu kali dan tidak dapat mengubah pilihan setelahnya! Tentukan pilihan Anda dengan bijak.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex relative justify-center items-center mx-auto w-full max-w-3xl">
      <div id="scroll-container" class="grid grid-cols-1 gap-4 w-full">
        @foreach ($kandidat as $row)
          <div class="grid grid-cols-[7rem_1fr] sm:grid-cols-[11rem_1fr] gap-x-4 gap-y-3 p-4 text-left bg-white rounded-3xl border-2 transition-all duration-300 shadow-md hover:shadow-lg border-slate-300/80">

            {{-- Foto (kiri) --}}
            <img src="{{ $row->image_url }}" alt="{{ $row->nama }}" width="176" height="176" loading="lazy" class="object-cover object-center w-full rounded-2xl border-2 shadow-sm aspect-square self-start border-slate-200 bg-slate-800 sm:row-span-2">

            {{-- Nama + kelas (kanan atas) --}}
            <div class="flex flex-col justify-center min-w-0">
              <h3 class="text-xl font-bold leading-tight sm:text-3xl text-slate-800">{{ $row->nama }}</h3>

              <p class="flex gap-2 items-center mt-1 text-sm font-semibold sm:text-base text-slate-500">
                <i class="text-[14px] fa-solid fa-graduation-cap"></i>{{ $row->kelas }}
              </p>
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
          </div>
        @endforeach
      </div>
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

    let voting = false; // cegah klik ganda
    const VOTING_BUKA = @json($votingBuka);
    const PESAN_TUTUP = @json($pengaturan->pesanTutup());

    // ================== SMOOTH SCROLL (tombol "Mulai Voting") ==================
    $('a[href^="#"]').on('click', function (e) {
      const $target = $($(this).attr('href'));
      if (!$target.length) return;
      e.preventDefault();
      $('html, body').animate({ scrollTop: $target.offset().top - 90 }, 400);
    });

    // ================== VOTING ==================
    // tombol vote di kartu
    $('#scroll-container').on('click', '.btn-vote', function (e) {
      e.stopPropagation();
      selectKandidat($(this).data('id'), $(this).data('nama'));
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
            text: 'Vote untuk ' + nama + ' berhasil disimpan. Anda akan dialihkan ke halaman Hasil.',
            seconds: 5
          }).then(function () {
            location.href = "{{ route('siswa.hasil') }}";
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