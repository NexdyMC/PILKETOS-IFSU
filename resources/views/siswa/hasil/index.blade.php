@extends('layouts.siswa.app')

@section('cdn')
  <!-- CDN : Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind-config.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- CDN : Chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- CDN : Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @section('title', 'Hasil - E-Vote OSIS')
@endsection

@section('content')
  <section class="grid items-center px-4 py-10">
    <div class="max-w-4xl mx-auto space-y-3 text-center">
      <div class="py-6 space-y-4">

        <!-- hero : icon -->
        <div class="flex justify-center">
          <div class="flex items-center text-white justify-center w-20 h-20 transition-all shadow-md bg-gradient-to-br from-amber-500 to-amber-300 rounded-xl">
           	<i class="fa-solid text-[40px] fa-chart-line"></i>
          </div>
        </div>

        <!-- hero : heading -->
        <h1 class="mb-4 text-5xl font-extrabold text-center text-slate-800">
          Hasil Voting <span class="text-[#FACC15]">OSIS</span>
        </h1>

        <!-- hero : description -->
        <p class="max-w-xl mx-auto text-sm text-slate-600 sm:text-base">
          Data diperbarui secara real-time setiap 5 detik
        </p>

        <!-- label : live -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-emerald-200 rounded-full shadow-sm">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          <span class="text-sm font-medium text-emerald-600">Live Update</span>
          <span class="text-sm text-slate-400">5s</span>
          <i id="refresh-icon" class="fa-solid fa-arrows-rotate text-emerald-500 text-xs transition-transform duration-500"></i>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

      <!-- card : total suara -->
      <div class="relative p-6 overflow-hidden text-white transition-all duration-300 shadow-lg bg-gradient-to-br from-cyan-400 to-blue-500 rounded-2xl shadow-cyan-500/30 hover:-translate-y-1 hover:shadow-cyan-500/50">
        <div class="absolute w-24 h-24 rounded-full -right-6 -top-6 bg-white/20 blur-2xl"></div>
        <div class="relative z-10 flex items-start justify-between">
          <div>
            <span class="text-sm font-semibold tracking-wider">Total Suara</span>
            <!-- Ditambahkan class animate-number dan data-value -->
            <div id="val-total-suara" class="text-4xl font-extrabold text-white animate-number" data-is-percent="false">0</div>
          </div>
          <div class="p-3 border bg-white/20 rounded-xl backdrop-blur-sm border-white/20">
            <i class="text-[40px] fa-solid fa-chart-column"></i>
          </div>
        </div>
      </div>

      <!-- card : partisipasi -->
      <div class="relative p-6 overflow-hidden text-white transition-all duration-300 shadow-lg bg-gradient-to-br from-emerald-400 to-teal-600 rounded-2xl shadow-emerald-500/30 hover:-translate-y-1 hover:shadow-emerald-500/50">
        <div class="absolute w-24 h-24 rounded-full -right-6 -top-6 bg-white/20 blur-2xl"></div>
        <div class="relative z-10 flex items-start justify-between">
          <div>
            <span class="text-sm font-semibold tracking-wider">Partisipasi</span>
            <div id="val-partisipasi" class="text-4xl font-extrabold text-white animate-number" data-is-percent="true">0%</div>
          </div>
          <div class="p-3 border bg-white/20 rounded-xl backdrop-blur-sm border-white/20">
            <i class="text-[40px] fa-solid fa-users"></i>
          </div>
        </div>
      </div>

      <!-- card : belum voting -->
      <div class="relative p-6 overflow-hidden text-white transition-all duration-300 shadow-lg bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-amber-500/30 hover:-translate-y-1 hover:shadow-amber-500/50">
        <div class="absolute w-24 h-24 rounded-full -right-6 -top-6 bg-white/20 blur-2xl"></div>
        <div class="relative z-10 flex items-start justify-between">
          <div>
            <span class="text-sm font-semibold tracking-wider">Belum Voting</span>
            <div id="val-belum-voting" class="text-4xl font-extrabold text-white animate-number" data-is-percent="true">0%</div>
          </div>
          <div class="p-3 border bg-white/20 rounded-xl backdrop-blur-sm border-white/20">
            <i class="text-[40px] fa-solid fa-circle-check"></i>
          </div>
        </div>
      </div>

      <!-- card : total kandidat -->
      <div class="relative p-6 overflow-hidden text-white transition-all duration-300 shadow-lg bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl shadow-green-500/30 hover:-translate-y-1 hover:shadow-green-500/50">
        <div class="absolute w-24 h-24 rounded-full -right-6 -top-6 bg-white/20 blur-2xl"></div>
        <div class="relative z-10 flex items-start justify-between">
          <div>
            <span class="text-sm font-semibold tracking-wider">Kandidat</span>
            <div id="val-jumlah-kandidat" class="text-4xl font-extrabold text-white animate-number" data-is-percent="false">0</div>
          </div>
          <div class="p-3 border bg-white/20 rounded-xl backdrop-blur-sm border-white/20">
            <i class="text-[40px] fa-solid fa-award"></i>
          </div>
        </div>
      </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">

      {{-- dougnut chart --}}
      {{-- <i class="fa-solid fa-chart-pie"></i> --}}
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-2xl font-bold text-slate-800">Persentase Suara</h3>
        <p class="text-sm text-slate-400 mb-4">Diagram Donat / Doughnut Chart</p>
        
        <div class="relative w-full h-[350px]">
          <canvas id="chartPersentase"></canvas>
        </div>
      </div>

      {{-- bar chart --}}
      {{-- <i class="fa-solid fa-chart-simple"></i> --}}
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-2xl font-bold text-slate-800">Perbandingan Perolehan Suara</h3>
        <p class="text-sm text-slate-400 mb-4">Diagram Batang / Bar Chart</p>
        
        <div class="relative w-full h-[350px]">
          <canvas id="chartPerbandingan"></canvas>
        </div>
      </div>
    </div>

    <div class="mt-6">
        <h2 class="text-2xl font-bold text-slate-800 mb-4">Progress Perolehan Kandidat</h2>
        <div id="progress-kandidat" class="space-y-4"></div>
    </div>

  </section>

  <div class="bg-gradient-to-r from-blue-50/90 via-blue-50/40 to-white border border-blue-100/80 border-l-[5px] border-l-blue-600 rounded-2xl p-4 sm:p-5 shadow-[0_4px_20px_rgb(30,58,138,0.05)] flex items-start sm:items-center gap-4 my-6 transition-all">
    <div
      class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 shadow-md rounded-xl shrink-0 shadow-blue-600/20">
      <i class="text-[20px] fa-solid fa-info"></i>
    </div>

    <!-- Content Text -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center gap-2">
        <h4 class="text-sm font-bold leading-snug font-display text-navy-900 sm:text-base">
          Informasi Penting
        </h4>
      </div>

      <p class="text-xs sm:text-sm text-slate-600 mt-0.5 font-medium leading-relaxed">
        Data voting diperbarui secara otomatis setiap <span class="font-bold text-navy-900">5 detik</span>. Anda juga
        dapat melakukan refresh manual dengan menekan tombol refresh.
      </p>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    let colors = ['#2563eb', '#facc15', '#06b6d4', '#22c55e', '#ef4444']; // warna sesuai jumlah kandidat

    let chartPersentase, chartPerbandingan;

// helper: hex -> rgba (colors harus format hex, mis. '#2563eb')
const withAlpha = (hex, alpha) => {
  const n = parseInt(hex.replace('#', ''), 16);
  return `rgba(${(n >> 16) & 255}, ${(n >> 8) & 255}, ${n & 255}, ${alpha})`;
};

// plugin: ring abu-abu + teks saat belum ada suara, total suara di tengah donut
const centerTextPlugin = {
  id: 'centerText',
  beforeDraw(chart) {
    const { ctx, chartArea: { left, right, top, bottom } } = chart;
    const values = chart.data.datasets[0].data;
    const total = values.reduce((a, b) => a + b, 0);
    const cx = (left + right) / 2, cy = (top + bottom) / 2;

    ctx.save();
    if (total === 0) {
      // ring kosong supaya donut tidak "hilang"
      const outer = Math.min(right - left, bottom - top) / 2;
      const inner = outer * 0.6;
      ctx.beginPath();
      ctx.arc(cx, cy, (outer + inner) / 2, 0, Math.PI * 2);
      ctx.lineWidth = outer - inner;
      ctx.strokeStyle = '#e2e8f0';
      ctx.stroke();
    }
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillStyle = '#1e293b';
    ctx.font = "800 32px 'Poppins', sans-serif";
    ctx.fillText(total, cx, cy - 8);
    ctx.fillStyle = '#94a3b8';
    ctx.font = "500 12px 'Inter', sans-serif";
    ctx.fillText('TOTAL SUARA', cx, cy + 18);
    ctx.restore();
  }
};

function initCharts() {
  const tooltipStyle = {
    padding: 12,
    backgroundColor: 'rgba(15, 23, 42, 0.92)',
    titleFont: { family: "'Poppins', sans-serif", weight: '600' },
    bodyFont: { family: "'Inter', sans-serif" },
    cornerRadius: 10,
    displayColors: true,
    boxPadding: 4
  };

  // ================= DONUT =================
  let ctxDonut = document.getElementById('chartPersentase').getContext('2d');
  chartPersentase = new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
      labels: [],
      datasets: [{
        data: [],
        backgroundColor: colors.map(c => withAlpha(c, 0.9)),
        borderColor: '#ffffff',
        borderWidth: 4,
        hoverOffset: 12,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '60%',
      layout: { padding: 20 },
      animation: { animateRotate: true, animateScale: true, duration: 900, easing: 'easeOutQuart' },
      plugins: {
        legend: {
          position: 'bottom',
          labels: { usePointStyle: true, padding: 20, color: '#475569', font: { family: "'Inter', sans-serif", size: 13, weight: 'bold' } }
        },
        tooltip: {
          ...tooltipStyle,
          callbacks: {
            label(ctx) {
              const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
              const pct = total ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
              return ` ${ctx.parsed} suara (${pct}%)`;
            }
          }
        }
      }
    },
    plugins: [centerTextPlugin]
  });

  // ================= BAR =================
  let ctxBar = document.getElementById('chartPerbandingan').getContext('2d');
  chartPerbandingan = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: [],
      datasets: [{
        data: [],
        backgroundColor: colors.map(c => withAlpha(c, 0.25)), // isi: transparan
        borderColor: colors.map(c => withAlpha(c, 1)),        // border: warna sama, pekat
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false,   // border melingkar penuh, termasuk sisi bawah
        minBarLength: 8,        // batang tetap terlihat walau 0 suara
        maxBarThickness: 60,
        hoverBackgroundColor: colors.map(c => withAlpha(c, 0.45))
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: 20 },
      animation: { duration: 800, easing: 'easeOutQuart' },
      plugins: {
        legend: { display: false },
        tooltip: {
          ...tooltipStyle,
          callbacks: { label: ctx => ` ${ctx.parsed.y} suara` }
        }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: '#475569', font: { family: "'Inter', sans-serif", weight: '600' } } },
        y: {
          beginAtZero: true,
          suggestedMax: 5,   // sumbu tidak mentok di 0-1 saat data kosong
          grid: { color: 'rgba(148, 163, 184, 0.2)', drawBorder: false },
          ticks: { stepSize: 1, precision: 0, color: '#94a3b8' }
        }
      }
    }
  });
}

    function persentase(labels, data) {
        chartPersentase.data.labels = labels;
        chartPersentase.data.datasets[0].data = data;
        chartPersentase.update();
    }

    function perbandingan(labels, data) {
        chartPerbandingan.data.labels = labels;
        chartPerbandingan.data.datasets[0].data = data;
        chartPerbandingan.update();
    }

    function loadStatistik() {

        $('#refresh-icon').addClass('animate-spin');


        $.ajax({
            url: '/siswa/hasil/statistik',
            method: 'GET',
            success: function(data) {
                animateNumber('#val-total-suara', data.total_suara);
                animateNumber('#val-partisipasi', data.partisipasi, '%');
                animateNumber('#val-belum-voting', data.belum_voting, '%');
                animateNumber('#val-jumlah-kandidat', data.kandidat);
            },
            error: function(xhr) {
                console.error('Gagal ambil statistik:', xhr);
            },
            complete: function() {
              setTimeout(function() {
                $('#refresh-icon').removeClass('animate-spin');
              }, 600);
            }

        });
    }

    function loadSuaraKandidat() {
        $.ajax({
            url: '/siswa/hasil/suara-kandidat',
            method: 'GET',
            success: function(res) {
                persentase(res.labels, res.suara);
                perbandingan(res.labels, res.suara);
                progressKandidat(res.kandidat)
            },
            error: function(xhr) {
                console.error('Gagal ambil data suara kandidat:', xhr);
            }
        });
    }

    function animateNumber(selector, targetValue, suffix = '') {
      let obj = { value: 0 };

      $(obj).animate({ value: targetValue }, {
          duration: 800,
          step: function() {
              $(selector).text(Math.floor(this.value) + suffix);
          },
          complete: function() {
              $(selector).text(targetValue + suffix); // pastikan angka final pas
          }
      });
    }

    function progressKandidat(kandidatList) {
        const $container = $('#progress-kandidat');

        // id card yang masih valid menurut data terbaru dari server
        const activeIds = kandidatList.map(k => 'kandidat-card-' + k.nomor_urut);

        // 1. HAPUS card kandidat yang sudah tidak ada di server
        $container.children('.kandidat-card').each(function () {
            if (!activeIds.includes(this.id)) {
                $(this).fadeOut(300, function () { $(this).remove(); });
            }
        });

        // 2. Tampilan kosong kalau tidak ada kandidat sama sekali
        $('#progress-empty').remove();
        if (kandidatList.length === 0) {
            $container.append(
                '<div id="progress-empty" class="p-6 text-center bg-white shadow-sm rounded-xl text-slate-400">Belum ada kandidat.</div>'
            );
            return;
        }

        // 3. BUAT / UPDATE card, lalu urutkan sesuai data server
        kandidatList.forEach(function (k, i) {
            const warna  = colors[i % colors.length];
            const cardId = 'kandidat-card-' + k.nomor_urut;
            const urut   = String(k.nomor_urut).padStart(2, '0');
            let $card    = $('#' + cardId);

            if ($card.length === 0) {
                $card = $(`
                    <div id="${cardId}" class="p-5 bg-white shadow-sm kandidat-card rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center text-sm font-bold text-white rounded-full k-badge w-9 h-9"></div>
                                <div>
                                    <h4 class="font-bold k-nama text-slate-800"></h4>
                                    <p class="text-xs k-urut text-slate-400"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="text-xl font-bold k-suara text-slate-800"></h4>
                                <p class="text-xs text-slate-400">Suara</p>
                            </div>
                        </div>
                        <div class="w-full h-4 overflow-hidden rounded-full bg-slate-200">
                            <div class="flex items-center justify-center h-4 text-xs font-bold text-white transition-all duration-700 ease-out rounded-full k-bar" style="width:0%"></div>
                        </div>
                    </div>
                `);
            }

            // isi/refresh semua data (.text() = aman dari XSS untuk nama kandidat)
            $card.find('.k-badge').text(urut).css('background-color', warna);
            $card.find('.k-nama').text(k.nama);
            $card.find('.k-urut').text('Paslon Nomor Urut ' + urut);
            $card.find('.k-suara').text(k.suara);
            $card.find('.k-bar').css({ width: k.persen + '%', 'background-color': warna }).text(k.persen + '%');

            // append pada elemen yang sudah ada = memindahkannya, jadi urutan selalu ikut server
            $container.append($card);
        });
    }
    
    const $syncElem = $('#syncTime');
    const $syncIcon = $('#syncIcon');

    $(document).ready(function() {
        initCharts();
        loadSuaraKandidat();
        loadStatistik();

        setInterval(function() {
            loadSuaraKandidat();
            loadStatistik();
        }, 5000);
    });
</script>
@endsection