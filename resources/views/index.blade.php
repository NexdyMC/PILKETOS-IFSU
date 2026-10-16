<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>E-Vote OSIS</title>

  <!-- link : CDN  -->
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.tailwind-config')
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- link : style css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('partials.pwa-head')
</head>
<body class="bg-white antialiased text-navy-900">

  <!-- navbar -->
  <header class="fixed inset-x-0 top-0 z-50 border-b bg-white/80 backdrop-blur-md border-slate-100">
    <nav class="flex justify-between items-center px-6 mx-auto max-w-6xl h-16">

      <!-- navbar : title -->
      <div class="flex gap-2 items-center">
        <img src="{{ asset('assets/images/logo-smk.png') }}" alt="Logo IFSU" class="w-12 h-12" width="32" height="32">
        <p class="text-xl font-bold font-display text-navy-900">E-VOTE<span class="text-primary-600"> OSIS</span>
        </p>
      </div>

      <!-- navbar : menu -->
      <div class="hidden gap-8 items-center font-medium md:flex text-md text-navy-800">
        <a href="#beranda" class="transition hover:text-primary-600">Beranda</a>
        <a href="#tahapan" class="transition hover:text-primary-600">Tahapan</a>
        <a href="#kandidat" class="transition hover:text-primary-600">Kandidat</a>
        <a href="#countdown" class="transition hover:text-primary-600">Countdown</a>
        <a href="#tips" class="transition hover:text-primary-600">Tips</a>
        <a href="#video" class="transition hover:text-primary-600">Video</a>
      </div>

      <!-- navbar : login -->
      @if ($siswa)
      <div class="flex gap-3 items-center">
        <span class="hidden sm:block text-md text-navy-700">Hai, <span
            class="font-semibold">{{ $siswa->nama }}</span></span>
        <a href="{{ $logoutUrl }}"
          class="px-4 py-2 font-medium rounded-full border transition text-md border-slate-200 hover:bg-slate-50">Keluar</a>
      </div>
      @else
      <a href="{{ $loginUrl }}"
        class="flex items-center btn-cta text-md font-semibold px-5 py-2.5 rounded-full bg-primary-700 text-white hover:bg-primary-600">
        Login<i data-lucide="log-in" class="ml-2 w-4 h-4"></i>
      </a>
      @endif
    </nav>
  </header>

  <!-- section : hero -->
  <section id="beranda" class="relative overflow-hidden bg-[#F8FAFC] pt-20 pb-32 md:h-screen lg:h-[calc(100vh-24px)]">
    <div class="absolute -top-10 -left-10 w-72 h-72 rounded-full pointer-events-none blur-3xl opacity-35 bg-primary-500"></div>
    <div class="absolute right-0 top-24 w-80 h-80 rounded-full pointer-events-none blur-3xl opacity-35 bg-accent-400"></div>

    <div class="relative px-6 mx-auto max-w-6xl text-center lg:pt-16">
      <h1 class="mt-6 text-4xl font-extrabold leading-tight font-display sm:text-5xl lg:text-6xl text-navy-900">
        Satu Suara <span class="text-primary-700">Satu Arah</span> 
        <br>untuk
        <span class="relative inline-block text-[#FACC15]">
          OSIS
        </span> yang Lebih Baik.
      </h1>

      <p class="mx-auto mt-6 max-w-2xl font-semibold sm:text-lg text-navy-700">
        Gunakan hak pilihmu secara digital, aman, dan transparan. Kenali calon pemimpinmu, ikuti setiap tahapan, dan pantau hasil suara secara real-time.
      </p>

      <div class="flex flex-col gap-4 justify-center items-center mt-10 sm:flex-row">
        <a href="{{ $loginUrl }}" class="btn-cta btn-accent font-display font-semibold px-8 py-3.5 rounded-full bg-accent-400 text-navy-900 hover:bg-accent-500">
          Login untuk Voting
        </a>
        <a href="#kandidat" class="btn-cta font-display font-semibold px-8 py-3.5 rounded-full border-2 border-primary-700 text-primary-700 hover:bg-primary-700 hover:text-white">
          Lihat Kandidat
        </a>
      </div>
    </div>

    <!-- Wave divider: light to dark -->
    <div class="absolute bottom-0 left-0 -mb-px divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path d="M0,32 C240,90 480,0 720,24 C960,48 1200,90 1440,40 L1440,90 L0,90 Z" fill="#0F172A"/>
      </svg>
    </div>
  </section>

  <!-- section : time line -->
  <section id="tahapan" class="overflow-hidden relative pt-28 pb-32 bg-navy-900">
    <div class="absolute -right-20 top-1/3 w-96 h-96 rounded-full pointer-events-none blur-3xl opacity-35 bg-primary-600 "></div>

    <div class="relative px-6 mx-auto max-w-5xl">
      <div class="mb-20 text-center">
        <span class="text-xs font-semibold tracking-widest uppercase sm:text-sm text-accent-400">Alur Pemilihan</span>
        <h2 class="mt-3 text-3xl font-bold text-white font-display sm:text-4xl">Tahapan Pemilu OSIS</h2>
        <p class="mx-auto mt-3 max-w-xl text-slate-400">Empat tahap resmi menuju terpilihnya Ketua OSIS periode berikutnya.</p>
      </div>

      <div class="relative">
        <div class="timeline-line absolute left-[27px] md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-[3px] rounded-full"></div>

        @php
        $tahapan = [
          [
            'label' => 'Tahap 01',
            'judul' => 'Pendaftaran & Kampanye',
            'desc'  => 'Calon mendaftarkan diri dan menyampaikan visi-misi melalui poster digital serta media sosial sekolah.',
            'live'  => false,
          ],
          [
            'label' => 'Tahap 02',
            'judul' => 'Debat Terbuka',
            'desc'  => 'Seluruh kandidat memaparkan program kerja dan menjawab pertanyaan siswa secara langsung di aula sekolah.',
            'live'  => false,
          ],
          [
            'label' => 'Tahap 03',
            'judul' => 'Hari Pemungutan Suara',
            'desc'  => 'Siswa memilih melalui portal e-voting menggunakan akun siswa masing-masing, satu suara per akun.',
            'live'  => false,
          ],
          [
            'label' => 'Tahap 04',
            'judul' => 'Live Count Hasil (API)',
            'desc'  => 'Hasil suara ditayangkan secara real-time melalui API perhitungan suara langsung ke dashboard sekolah.',
            'live'  => true,
          ],
        ];
        @endphp
        @foreach ($tahapan as $i => $t)
        @php $kanan = $i % 2 === 1; @endphp
        <div class="relative flex items-start md:items-center mb-14 last:mb-0 {{ $kanan ? 'md:flex-row-reverse' : '' }}">
          <!-- dot -->
          <span class="timeline-dot {{ $t['live'] ? 'live' : '' }} absolute left-[21px] md:left-1/2 md:-translate-x-1/2 top-1.5 w-3.5 h-3.5 rounded-full bg-accent-400 z-10"></span>

          <div class="w-full md:w-1/2 {{ $kanan ? 'md:pl-12' : 'md:pr-12' }} pl-16 md:pl-0">
            <div class="p-6 rounded-2xl border bg-navy-800/70 border-white/5 backdrop-blur-sm">
              <div class="flex gap-3 items-center mb-2">
                <span class="text-xs font-semibold tracking-widest text-accent-400">{{ $t['label'] }}</span>
                @if ($t['live'])
                  <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-sky-300 bg-sky-400/10 px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span> LIVE
                  </span>
                @endif
              </div>
              <h3 class="font-display font-semibold text-lg text-white mb-1.5">{{ $t['judul'] }}</h3>
              <p class="text-sm leading-relaxed text-slate-400">{{ $t['desc'] }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Wave divider: dark to light -->
    <div class="absolute bottom-0 left-0 -mb-px divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path d="M0,40 C240,0 480,90 720,56 C960,24 1200,0 1440,48 L1440,90 L0,90 Z" fill="#F8FAFC"/>
      </svg>
    </div>
  </section>

  <!-- section : kandidat -->
  <section id="kandidat" class="relative bg-[#F8FAFC] pt-28 pb-28">
    <div class="px-6 mx-auto max-w-6xl">
      <div class="mb-16 text-center">
        <span class="text-xs font-semibold tracking-widest uppercase sm:text-sm text-primary-700">Kenali Mereka</span>
        <h2 class="mt-3 text-3xl font-bold font-display sm:text-4xl text-navy-900">Calon Ketua &amp; Wakil Ketua OSIS</h2>
        <p class="mx-auto mt-3 max-w-xl text-navy-600">Pelajari visi dan misi setiap paslon sebelum menentukan pilihanmu.</p>
      </div>

      {{-- status voting untuk siswa yang sedang login --}}
      @if ($siswa)
        <div class="max-w-2xl px-5 py-4 mx-auto mb-10 text-sm text-center border rounded-2xl {{ $sudahMemilih ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : ($voting['buka'] ? 'bg-primary-50 border-primary-100 text-primary-800' : 'bg-amber-50 border-amber-200 text-amber-800') }}">
          @if ($sudahMemilih)
            Terima kasih, <b>{{ $siswa->nama }}</b>! Suaramu sudah tercatat dan tidak bisa diubah.
          @elseif ($voting['buka'])
            Hai, <b>{{ $siswa->nama }}</b>. Pilih satu kandidat di bawah ini. Suara tidak bisa diubah setelah disimpan.
          @else
            {{ $voting['pesan'] }}
          @endif
        </div>
      @elseif (!$voting['buka'])
        <div class="px-5 py-4 mx-auto mb-10 max-w-2xl text-sm text-center text-amber-800 bg-amber-50 rounded-2xl border border-amber-200">
          {{ $voting['pesan'] }}
        </div>
      @endif

      <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($kandidat as $row)
          @php $dipilih = $sudahMemilih && (string) $pilihanId === (string) $row->id; @endphp

          <div class="card-kandidat shrink-0 flex flex-col justify-between relative group bg-white rounded-3xl shadow-[0_8px_30px_rgb(15,23,42,0.06)] overflow-hidden border-2 {{ $dipilih ? 'border-primary-600 ring-4 ring-primary-500/30' : 'border-slate-300/80' }}">
            <div class="text-left">

              <!-- kandidat : image -->
              <div class="relative rounded-2xl overflow-hidden bg-slate-800 aspect-[4/3] mb-5">
                <img src="{{ $row->image_url }}" alt="kandidat {{ $row->nama }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" width="320" height="256">
                @if ($dipilih)
                  <span class="absolute z-10 flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white rounded-full shadow-lg top-3 left-3 bg-primary-700">
                    <i data-lucide="badge-check" class="w-4 h-4"></i> Pilihanmu
                  </span>
                @endif
              </div>

              <!-- kandidat : nama siswa -->
              <p class="my-4 text-2xl font-bold text-center line-clamp-1">
                  {{ $row->nama }}
              </p>
              <div class="px-4 space-y-4 scrollbar-thin scrollbar-thumb-slate-700">

                <!-- kandidat : Visi -->
                <div class="p-3 rounded-xl border-2 bg-slate-100/60 border-slate-200/80">
                  <h4 class="flex gap-1 items-center mb-1 font-bold uppercase text-md text-brand-blue">
                      <i data-lucide="compass" class="w-5 h-5 text-brand-blue"></i> Visi
                  </h4>
                  <p class="text-sm leading-relaxed text-bland-blue ">
                      {!! nl2br(e($row->visi)) !!}
                  </p>
                </div>

                <!-- kandidat : Misi -->
                <div class="p-3 rounded-xl border-2 bg-slate-100/60 border-slate-200/80">
                  <h4 class="flex gap-1 items-center mb-1 font-bold uppercase text-md text-brand-blue">
                      <i data-lucide="compass" class="w-5 h-5 text-brand-blue"></i> Misi
                  </h4>
                  <p class="text-sm leading-relaxed text-bland-blue ">
                      {!! nl2br(e($row->misi)) !!}
                  </p>
                </div>
              </div>
            </div>

            <!-- kandidat : button -->
            <div class="px-4 py-4 space-y-4 text-center">
              @if (!$siswa)
                <a href="{{ $loginUrl }}"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full font-semibold rounded-xl border-2 btn-cta font-display text-md border-primary-700 text-primary-700 bg-slate-100 text-navy-500 hover:bg-primary-700 hover:text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="11" width="14" height="9" rx="2"/>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                  </svg>
                  Login untuk Voting
                </a>
              @elseif ($dipilih)
                <div class="flex gap-2 justify-center items-center px-5 py-3 w-full font-semibold text-white rounded-xl border-2 font-display text-md border-primary-700 bg-primary-700">
                  <i data-lucide="badge-check" class="w-5 h-5"></i> Pilihanmu
                </div>
              @elseif ($sudahMemilih)
                <button type="button" disabled class="px-5 py-3 w-full font-semibold rounded-xl border-2 cursor-not-allowed font-display text-md border-slate-200 bg-slate-100 text-slate-400">
                  Kamu sudah memilih
                </button>
              @elseif (!$voting['buka'])
                <button type="button" disabled class="px-5 py-3 w-full font-semibold rounded-xl border-2 cursor-not-allowed font-display text-md border-slate-200 bg-slate-100 text-slate-400">
                  {{ $voting['pesan'] }}
                </button>
              @else
                <button type="button"
                  class="flex gap-2 justify-center items-center px-5 py-3 w-full font-semibold rounded-xl border-2 btn-vote btn-cta font-display text-md border-primary-700 text-primary-700 bg-slate-100 hover:bg-primary-700 hover:text-white"
                  data-url="{{ route('home.vote', $row->getKey()) }}"
                  data-nama="{{ $row->nama }}">
                  <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                  Pilih Kandidat Ini
                </button>
              @endif
            </div>
          </div>
        @empty
          <p class="col-span-full py-10 text-center text-navy-600">Belum ada kandidat yang terdaftar.</p>
        @endforelse
      </div>
    </div>

    <!-- Wave divider: light to dark -->
    <div class="absolute bottom-0 left-0 -mb-px divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path d="M0,32 C240,90 480,0 720,24 C960,48 1200,90 1440,40 L1440,90 L0,90 Z" fill="#0F172A"/>
      </svg>
    </div>
  </section>

  <!-- section : countdown voting -->
  <section id="countdown" class="overflow-hidden relative pt-28 pb-32 bg-navy-900">
    <div class="absolute -top-16 -left-16 w-96 h-96 rounded-full pointer-events-none blur-3xl opacity-35 bg-primary-600"></div>
    <div class="absolute right-0 bottom-0 w-72 h-72 rounded-full pointer-events-none blur-3xl opacity-35 bg-accent-400"></div>

    <div class="relative px-6 mx-auto max-w-4xl text-center">
      <span class="text-xs font-semibold tracking-widest uppercase sm:text-sm text-accent-400">Jangan Sampai Terlewat</span>
      <h2 class="mt-3 text-3xl font-bold text-white font-display sm:text-4xl">Waktu Tersisa untuk Voting</h2>
      <p class="mx-auto mt-3 max-w-xl text-slate-400">Pastikan kamu memilih sebelum waktu pemungutan suara resmi ditutup.</p>

      <div class="grid relative grid-cols-4 gap-3 mx-auto mt-14 max-w-2xl sm:gap-6">
        <div class="countdown-line absolute left-6 right-6 top-1/2 -translate-y-1/2 h-[3px] rounded-full -z-0"></div>

        <div class="relative z-10 py-5 rounded-2xl border countdown-box bg-navy-800/80 border-white/5 sm:py-8">
          <div id="cd-hari" class="text-3xl font-extrabold countdown-number font-display sm:text-5xl text-accent-400">00</div>
          <div class="text-[11px] sm:text-xs font-semibold tracking-widest text-slate-400 uppercase mt-2">Hari</div>
        </div>
        <div class="relative z-10 py-5 rounded-2xl border countdown-box bg-navy-800/80 border-white/5 sm:py-8">
          <div id="cd-jam" class="text-3xl font-extrabold text-white countdown-number font-display sm:text-5xl">00</div>
          <div class="text-[11px] sm:text-xs font-semibold tracking-widest text-slate-400 uppercase mt-2">Jam</div>
        </div>
        <div class="relative z-10 py-5 rounded-2xl border countdown-box bg-navy-800/80 border-white/5 sm:py-8">
          <div id="cd-menit" class="text-3xl font-extrabold text-white countdown-number font-display sm:text-5xl">00</div>
          <div class="text-[11px] sm:text-xs font-semibold tracking-widest text-slate-400 uppercase mt-2">Menit</div>
        </div>
        <div class="relative z-10 py-5 rounded-2xl border countdown-box bg-navy-800/80 border-white/5 sm:py-8">
          <div id="cd-detik" class="text-3xl font-extrabold text-sky-300 countdown-number font-display sm:text-5xl">00</div>
          <div class="text-[11px] sm:text-xs font-semibold tracking-widest text-slate-400 uppercase mt-2">Detik</div>
        </div>
      </div>

      <p id="cd-selesai" class="hidden mt-8 text-sm font-semibold text-accent-400">Waktu pemungutan suara telah berakhir.</p>
    </div>

    <!-- Wave divider: dark to light -->
    <div class="absolute bottom-0 left-0 -mb-px divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path d="M0,40 C240,0 480,90 720,56 C960,24 1200,0 1440,48 L1440,90 L0,90 Z" fill="#F8FAFC"/>
      </svg>
    </div>
  </section>

  <!-- section : tips voting -->
  <section id="tips" class="relative bg-[#F8FAFC] pt-28 pb-32 overflow-hidden">
    <div class="absolute top-10 -right-10 w-72 h-72 rounded-full pointer-events-none blur-3xl opacity-35 bg-primary-500"></div>
    <div class="absolute bottom-10 -left-10 w-80 h-80 rounded-full pointer-events-none blur-3xl opacity-35 bg-accent-400"></div>

    <div class="relative px-6 mx-auto max-w-7xl">
      <div class="mb-16 text-center">
        <span class="text-xs font-semibold tracking-widest uppercase sm:text-sm text-primary-700">Panduan Singkat</span>
        <h2 class="mt-3 text-3xl font-bold font-display sm:text-4xl text-navy-900">Tips Voting Cepat &amp; Efisien</h2>
        <p class="mx-auto mt-3 max-w-xl text-navy-600">Ikuti 5 langkah ini agar suaramu tercatat dalam hitungan menit.</p>
      </div>

      @php
      $tips = [
        [
          'judul' => 'Login dengan Token',
          'desc'  => 'Masuk menggunakan Token yang sudah diberikan kepada panitia OSIS',
        ],
        [
          'judul' => 'Baca Visi-Misi',
          'desc'  => 'Luangkan 1-2 menit membaca ringkasan program di section Kandidat sebelum memilih.',
        ],
        [
          'judul' => 'Pilih Kandidat',
          'desc'  => 'Tekan tombol "Pilih Kandidat Ini" satu kali, sistem otomatis mengunci suara agar tidak bisa diubah/diganda.',
        ],
        [
          'judul' => 'Tunggu Konfirmasi',
          'desc'  => 'Pastikan muncul pesan "Vote berhasil disimpan." sebelum menutup atau me-refresh halaman.',
        ],
        [
          'judul' => 'Responsive',
          'desc'  => 'Website ini responsive cukup buka browser dari HP di sela jam istirahat, tanpa perlu ke lab komputer.',
        ],
      ];
      @endphp

      <div class="relative">
        <div class="hidden lg:block absolute top-[22px] left-8 right-8 h-[3px] bg-slate-200 rounded-full z-0"></div>

        <!-- Container Grid Horizontal -->
        <div class="grid relative z-10 grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-5">
          @foreach ($tips as $i => $tp)
            <div class="flex flex-col h-full">
              
              <!-- Horizontal Dot/Badge Langkah -->
              <div class="flex gap-3 items-center mb-4 lg:flex-col lg:items-start">
                <span class="flex justify-center items-center w-11 h-11 text-sm font-bold text-white rounded-xl shadow-lg bg-primary-600 font-display shadow-primary-500/20 shrink-0">
                  {{ $i + 1 }}
                </span>
                <span class="text-xs font-semibold tracking-wider uppercase text-primary-700">
                  Langkah {{ $i + 1 }}
                </span>
              </div>

              <!-- Card Konten -->
              <div class="bg-white border-2 border-slate-300 rounded-2xl p-6 shadow-[0_8px_30px_rgb(15,23,42,0.05)] flex-1 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
                <div>
                  <h3 class="mb-2 text-base font-semibold font-display text-navy-900">
                    {{ $tp['judul'] }}
                  </h3>
                  <p class="text-xs leading-relaxed sm:text-sm text-navy-600">
                    {{ $tp['desc'] }}
                  </p>
                </div>
              </div>

            </div>
          @endforeach
        </div>

      </div>
    </div>

    <!-- Wave divider: light -> dark -->
    <div class="absolute bottom-0 left-0 -mb-px w-full divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none" class="w-full">
        <path d="M0,32 C240,90 480,0 720,24 C960,48 1200,90 1440,40 L1440,90 L0,90 Z" fill="#0F172A"/>
      </svg>
    </div>
  </section>

  <!-- section : video tutorial -->
  <section id="video" class="overflow-hidden relative pt-28 pb-32 bg-navy-900">
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full pointer-events-none blur-3xl opacity-35 bg-primary-600"></div>

    <div class="relative px-6 mx-auto max-w-3xl">
      <div class="mb-14 text-center">
        <span class="text-xs font-semibold tracking-widest uppercase sm:text-sm text-accent-400">Tonton &amp; Praktikkan</span>
        <h2 class="mt-3 text-3xl font-bold text-white font-display sm:text-4xl">Cara Voting di Website Ini</h2>
        <p class="mx-auto mt-3 max-w-xl text-slate-400">Video singkat berikut menunjukkan alur voting langkah demi langkah.</p>
      </div>

      <div id="videoFacade"
          class="overflow-hidden relative rounded-2xl border shadow-2xl cursor-pointer video-facade aspect-video border-white/10 group bg-navy-800">
        <iframe
            class="absolute inset-0 w-full h-full"
            src="https://www.youtube.com/embed/1rv1PjprD18?list=RD1rv1PjprD18"
            title="Video tutorial voting"
            frameborder="0"
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>

    <!-- Wave divider: dark to light -->
    <div class="absolute bottom-0 left-0 -mb-px divider">
      <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path d="M0,40 C240,0 480,90 720,56 C960,24 1200,0 1440,48 L1440,90 L0,90 Z" fill="#F8FAFC"/>
      </svg>
    </div>
  </section>

  <!-- footer -->
  <footer class="py-8 bg-white border-t border-slate-100">
    <div class="px-6 mx-auto max-w-6xl text-sm text-center text-navy-500">
      &copy; 2026 Febri Pratama — All Right Reserved.
    </div>
  </footer>

  <!-- script : js -->
  <script>
    lucide.createIcons();
    // ===================== COUNTDOWN VOTING =====================
    const targetVoting = new Date({{ $berakhir }} * 1000).getTime();

    function updateCountdown() {
      const sekarang = new Date().getTime();
      const sisa = targetVoting - sekarang;

      if (sisa <= 0) {
        document.getElementById('cd-hari').textContent   = '00';
        document.getElementById('cd-jam').textContent    = '00';
        document.getElementById('cd-menit').textContent  = '00';
        document.getElementById('cd-detik').textContent  = '00';
        document.getElementById('cd-selesai').classList.remove('hidden');
        clearInterval(cdInterval);
        return;
      }

      const hari  = Math.floor(sisa / (1000 * 60 * 60 * 24));
      const jam   = Math.floor((sisa % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const menit = Math.floor((sisa % (1000 * 60 * 60)) / (1000 * 60));
      const detik = Math.floor((sisa % (1000 * 60)) / 1000);

      document.getElementById('cd-hari').textContent  = String(hari).padStart(2, '0');
      document.getElementById('cd-jam').textContent   = String(jam).padStart(2, '0');
      document.getElementById('cd-menit').textContent = String(menit).padStart(2, '0');
      document.getElementById('cd-detik').textContent = String(detik).padStart(2, '0');
    }
    updateCountdown();
    const cdInterval = setInterval(updateCountdown, 1000);
  </script>


  <script>
    // ===================== VOTING =====================
    const LOGIN_URL = @json($loginUrl);
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => (
      { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));

    const Modal = Swal.mixin({
      buttonsStyling: false,
      reverseButtons: true,
      customClass: {
        popup: 'rounded-3xl',
        confirmButton: 'px-6 py-3 mx-1 font-semibold text-white rounded-xl bg-primary-700 hover:bg-primary-600',
        cancelButton: 'px-6 py-3 mx-1 font-semibold border rounded-xl border-slate-200 text-navy-700 hover:bg-slate-50',
      },
    });

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-vote');
      if (!btn) return;

      let perluMuatUlang = false;

      Modal.fire({
        icon: 'question',
        title: 'Pilih kandidat ini?',
        html: 'Kamu akan memilih <b>' + esc(btn.dataset.nama) + '</b>.<br>Suara <b>tidak bisa diubah</b> setelah disimpan.',
        showCancelButton: true,
        confirmButtonText: 'Ya, pilih',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const res = await fetch(btn.dataset.url, {
              method: 'POST',
              headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
              credentials: 'same-origin',
            });
            const json = await res.json().catch(() => ({}));

            if (res.ok) return json;

            if (res.status === 419) {
              perluMuatUlang = true;
              Swal.showValidationMessage('Sesi sudah berakhir. Tutup pesan ini, halaman akan dimuat ulang lalu login kembali.');
            } else if (res.status === 401) {
              Swal.showValidationMessage('Kamu belum login atau sesi berakhir. Silakan login ulang.');
              setTimeout(() => { window.location.href = LOGIN_URL; }, 1800);
            } else {
              if (res.status === 409) perluMuatUlang = true;
              Swal.showValidationMessage(json.message || 'Terjadi kesalahan pada server.');
            }
            return false;
          } catch (err) {
            Swal.showValidationMessage('Koneksi bermasalah. Periksa internetmu lalu coba lagi.');
            return false;
          }
        },
      }).then(function (result) {
        if (result.isConfirmed && result.value) {
          Modal.fire({
            icon: 'success',
            title: 'Vote berhasil disimpan.',
            text: 'Terima kasih sudah menggunakan hak pilihmu.',
            confirmButtonText: 'Selesai',
          }).then(function () {
            window.location.hash = '#kandidat';
            window.location.reload();
          });
        } else if (perluMuatUlang) {
          window.location.reload();
        }
      });
    });

    // halaman dipulihkan dari cache tombol back (HP): muat ulang agar status & token CSRF segar
    window.addEventListener('pageshow', function (e) { if (e.persisted) window.location.reload(); });
  </script>

</body>
</html>