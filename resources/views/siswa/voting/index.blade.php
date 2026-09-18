@extends('layouts.siswa.app')

@section('cdn')
  <!-- CDN : Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind-config.js') }}"></script>

  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

@endsection

@section('content')
  <div class="space-y-4 text-center">
    <div class="mx-auto space-y-3 text-center max-w-7xl">
      <div class="py-6 space-y-3 md:space-y-4 ">

        <div class="flex justify-center">
          <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 text-white transition-all shadow-md bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl">
            <i class="text-[32px] md:text-[40px] fa-solid fa-users"></i>
          </div>
        </div>

        <h1 class="text-3xl md:text-5xl font-extrabold text-center text-slate-800">Voting <span class="text-[#FACC15]">OSIS</span></h1>
        <p class="text-gray-600">Pilih calon ketua OSIS yang menurut Anda paling tepat</p>

        <div class="flex items-start gap-4 p-4 my-6 transition-all border-l-4 shadow-lg bg-amber-300/20 border-amber-300/80 border-l-amber-300 rounded-2xl sm:p-5 sm:items-center">	
          <div class="flex items-center justify-center w-10 h-10 text-white rounded-xl bg-amber-400 shrink-0 shadow-blue-600/20">
            <i class="text-[20px] fa-solid fa-info"></i>
          </div>

          <!-- Content Text -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
              <h4 class="text-sm font-bold leading-snug font-display text-navy-900 sm:text-base">
                Penting Diperhatikan!
              </h4>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 mt-0.5 font-medium leading-relaxed text-left">
              Anda hanya dapat memilih satu kali dan tidak dapat mengubah pilihan setelahnya! Klik kartu kandidat untuk melihat visi &amp; misi selengkapnya.
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="relative flex items-center justify-center gap-4 max-w-7xl">
    <div id="scroll-container" class="grid h-full grid-cols-1 gap-4 transition-transform duration-500 ease-in-out md:grid-cols-3">
      @foreach ($kandidat as $index => $row )
      <div class="relative flex flex-col w-full h-full overflow-hidden transition-all duration-300 bg-white border-2 shadow-md cursor-pointer rounded-3xl hover:-translate-y-2 md:hover:-translate-y-4 hover:shadow-xl hover:border-blue-500 border-slate-300/80 group kandidat-item"
        data-id="{{ $row->id }}"
        data-nama="{{ $row->nama }}"
        data-image="{{ asset('/storage/kandidat/' .  $row->image) }}"
        tabindex="0" role="button"
        aria-haspopup="dialog"
        aria-label="Lihat visi dan misi {{ $row->nama }}">
          
          <!-- kandidat : image -->
          <div class="relative border-b-2 hover:border-blue-600 overflow-hidden bg-slate-800 aspect-[4/3] shrink-0">
            <img src="{{ asset('storage/kandidat/' . $row->image) }}" alt="{{ $row->nama }}"
              class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
          </div>
          <div class="grid gap-4 p-4">
            <!-- kandidat : nama siswa -->
            <h3 class="text-2xl font-bold text-center text-slate-800 shrink-0">
              {{ $row->nama }}
            </h3>
            
            <!-- kandidat : label -->
            <div class="flex justify-center shrink-0">
              <span class="p-2 py-1 text-sm font-bold tracking-widest text-white border rounded-full bg-gradient-to-br from-amber-500 to-amber-300">
                Calon Ketua OSIS
              </span>
            </div>
            
            
            <div class="z-10 space-y-4 overflow-hidden bg-white shrink-0">
              
              <!-- kandidat : button Visi & Misi -->
              <button type="button" class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-yellow-600 transition-colors bg-white border-2 border-yellow-600 btn-kandidat-item rounded-xl hover:bg-yellow-600 hover:text-white"
                data-id="{{ $row->id }}"
                data-nama="{{ $row->nama }}"
                data-image="{{ $row->image }}"
                tabindex="0" role="button"
                aria-haspopup="dialog"
                aria-label="Lihat visi dan misi {{ $row->nama }}">
                <i class="text-[16px] fa-solid fa-book-open"></i>
                Lihat Visi & Misi
              </button>
            
              <!-- kandidat : button voting -->
              <button type="button" class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-blue-600 transition-colors bg-white border-2 border-blue-600 btn-vote rounded-xl hover:bg-blue-600 hover:text-white">
                <i class="text-[16px] fa-solid fa-check-to-slot"></i>
                Pilih Kandidat Ini
              </button>
            </div>
            <div class="hidden kandidat-visi">{{ $row->visi }}</div>
            <div class="hidden kandidat-misi">{{ $row->misi }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection