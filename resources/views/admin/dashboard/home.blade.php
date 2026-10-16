<div wire:poll.60s class="p-4 space-y-8 sm:p-8">

  {{-- ==================== 1. HEADER & STATUS PEMILIHAN ==================== --}}
  <div class="flex flex-col gap-4 justify-between items-start md:flex-row md:items-center">
    <div>
      <div class="flex items-center gap-3">
        <h2 class="text-2xl font-bold font-display text-navy-900">Statistik Hasil Pemilihan</h2>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
          @if($setting->statusJadwal() === 'berlangsung') bg-emerald-100 text-emerald-700 border border-emerald-300
          @elseif($setting->statusJadwal() === 'terjadwal') bg-blue-100 text-blue-700 border border-blue-300
          @elseif($setting->statusJadwal() === 'berakhir') bg-slate-200 text-slate-700 border border-slate-300
          @else bg-rose-100 text-rose-700 border border-rose-300 @endif">
          <span class="w-2 h-2 rounded-full
            @if($setting->statusJadwal() === 'berlangsung') bg-emerald-500 animate-pulse
            @elseif($setting->statusJadwal() === 'terjadwal') bg-blue-500
            @else bg-rose-500 @endif"></span>
          {{ strtoupper($setting->statusJadwal()) }}
        </span>
      </div>
      <p class="mt-1 text-sm text-slate-500">
        {{ $setting->judul_pemilihan }} &bull; {{ $setting->nama_sekolah }} (TP. {{ $setting->tahun_ajaran }})
      </p>
    </div>

    <div class="flex items-center gap-2 px-3.5 py-2 bg-white rounded-xl border border-slate-200 shadow-sm text-xs font-medium text-slate-600">
      <i class="fa-solid fa-arrows-rotate text-emerald-600 animate-spin" style="animation-duration: 8s;"></i>
      <span>Auto-refresh real-time (1 Menit)</span>
    </div>
  </div>

  {{-- ==================== 2. KARTU STATISTIK UTAMA (MAIN STAT CARDS) ==================== --}}
  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    
    {{-- Card 1: Total DPT --}}
    <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm transition hover:shadow-md">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-xs font-semibold tracking-wider uppercase text-slate-500">Total DPT (Pemilih)</p>
          <p class="mt-2 text-3xl font-extrabold font-display text-navy-900">{{ number_format($totalDpt) }}</p>
          <p class="mt-1 text-xs text-slate-500">Hak suara terdaftar di sistem</p>
        </div>
        <div class="flex justify-center items-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600">
          <i class="fa-solid fa-users fa-lg"></i>
        </div>
      </div>
    </div>

    {{-- Card 2: Suara Masuk --}}
    <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm transition hover:shadow-md">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-xs font-semibold tracking-wider uppercase text-slate-500">Suara Masuk</p>
          <p class="mt-2 text-3xl font-extrabold font-display text-emerald-600">{{ number_format($suaraMasuk) }}</p>
          <p class="mt-1 text-xs text-emerald-600 font-medium">
            <i class="fa-solid fa-arrow-trend-up mr-1"></i>{{ $persenPartisipasi }}% partisipasi
          </p>
        </div>
        <div class="flex justify-center items-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600">
          <i class="fa-solid fa-check-to-slot fa-lg"></i>
        </div>
      </div>
    </div>

    {{-- Card 3: Belum Memilih --}}
    <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm transition hover:shadow-md">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-xs font-semibold tracking-wider uppercase text-slate-500">Belum Memilih</p>
          <p class="mt-2 text-3xl font-extrabold font-display text-amber-600">{{ number_format($belumVoting) }}</p>
          <p class="mt-1 text-xs text-amber-600 font-medium">
            {{ $persenBelum }}% belum menyalurkan
          </p>
        </div>
        <div class="flex justify-center items-center w-12 h-12 rounded-xl bg-amber-50 text-amber-600">
          <i class="fa-solid fa-hourglass-half fa-lg"></i>
        </div>
      </div>
    </div>

    {{-- Card 4: Status Jadwal Voting --}}
    <div class="p-6 rounded-2xl bg-gradient-to-br from-navy-900 to-navy-950 text-white shadow-sm flex flex-col justify-between">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-xs font-semibold tracking-wider uppercase text-slate-400">Jadwal Pemilihan</p>
          <p class="mt-2 text-xl font-bold font-display text-accent-400">
            @if($setting->statusJadwal() === 'berlangsung')
              SEDANG BUKA
            @elseif($setting->statusJadwal() === 'terjadwal')
              TERJADWAL
            @elseif($setting->statusJadwal() === 'berakhir')
              BERAKHIR
            @else
              DITUTUP
            @endif
          </p>
        </div>
        <div class="flex justify-center items-center w-10 h-10 rounded-xl bg-white/10 text-yellow-400">
          <i class="fa-solid fa-calendar-check"></i>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-white/10 text-[11px] text-slate-300">
        @if($setting->waktu_selesai)
          Batas: {{ $setting->waktu_selesai->format('d M Y, H:i') }} WIB
        @else
          Kontrol manual oleh panitia
        @endif
      </div>
    </div>

  </div>

  {{-- ==================== 3. TABEL DATA KELOMPOK (XII, XI, X, GURU) ==================== --}}
  <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h3 class="text-lg font-bold font-display text-navy-900">Tabel Rekapitulasi Kelompok Pemilih</h3>
        <p class="text-xs text-slate-500 mt-0.5">Rincian perolehan suara berdasar kelompok: Kelas XII, Kelas XI, Kelas X, dan Guru</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">
          Total Kelompok: 4 Kategori
        </span>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
          <tr>
            <th class="py-3.5 px-4 w-12 text-center">No</th>
            <th class="py-3.5 px-4">Kelompok Pemilih</th>
            <th class="py-3.5 px-4">Keterangan</th>
            <th class="py-3.5 px-4 text-center">Total DPT</th>
            <th class="py-3.5 px-4 text-center">Sudah Voting</th>
            <th class="py-3.5 px-4 text-center">Belum Voting</th>
            <th class="py-3.5 px-4 min-w-[200px]">Partisipasi</th>
            <th class="py-3.5 px-4 text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          @php $no = 1; @endphp
          @foreach($kelompokData as $key => $row)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">{{ $no++ }}</td>
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $row['iconBg'] }} {{ $row['iconColor'] }} shrink-0">
                    <i class="fa-solid {{ $row['icon'] }}"></i>
                  </div>
                  <div>
                    <span class="font-bold text-navy-900 block">{{ $row['nama'] }}</span>
                    <span class="text-[11px] text-slate-400">Kode: {{ $key }}</span>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4 text-slate-600 text-xs">{{ $row['deskripsi'] }}</td>
              <td class="py-3.5 px-4 text-center font-bold font-mono text-navy-900">{{ number_format($row['total']) }}</td>
              <td class="py-3.5 px-4 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                  {{ number_format($row['voted']) }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                  {{ number_format($row['belum']) }}
                </span>
              </td>
              <td class="py-3.5 px-4">
                <div class="space-y-1">
                  <div class="flex justify-between text-xs">
                    <span class="font-semibold text-slate-700">{{ $row['persen'] }}%</span>
                    <span class="text-slate-400 text-[11px]">{{ $row['voted'] }}/{{ $row['total'] }}</span>
                  </div>
                  <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="{{ $row['barColor'] }} h-2 rounded-full" style="width: {{ $row['persen'] }}%"></div>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4 text-center">
                @if($row['persen'] >= 80)
                  <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Sangat Tinggi</span>
                @elseif($row['persen'] >= 50)
                  <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Tinggi</span>
                @elseif($row['persen'] > 0)
                  <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Sedang Berjalan</span>
                @else
                  <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Ada Suara</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-slate-50/90 font-bold text-navy-900 border-t-2 border-slate-200">
          <tr>
            <td colspan="3" class="py-3.5 px-4 text-right uppercase text-xs tracking-wider">Total Keseluruhan :</td>
            <td class="py-3.5 px-4 text-center font-mono">{{ number_format($totalDpt) }}</td>
            <td class="py-3.5 px-4 text-center font-mono text-emerald-700">{{ number_format($suaraMasuk) }}</td>
            <td class="py-3.5 px-4 text-center font-mono text-amber-700">{{ number_format($belumVoting) }}</td>
            <td class="py-3.5 px-4">
              <div class="flex justify-between items-center text-xs">
                <span>Rata-rata: {{ $persenPartisipasi }}%</span>
              </div>
            </td>
            <td class="py-3.5 px-4 text-center">
              <span class="px-3 py-1 bg-navy-900 text-white rounded-lg text-xs font-semibold">100% Data</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- ==================== 4. PEROLEHAN SUARA KANDIDAT (CARDS) ==================== --}}
  <div class="space-y-4">
    <div>
      <h3 class="text-lg font-bold font-display text-navy-900">Perolehan Suara Kandidat</h3>
      <p class="text-xs text-slate-500">Hasil suara riil yang diperoleh masing-masing pasangan calon</p>
    </div>

    {{-- Kartu Masing-masing Kandidat --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
      @forelse($kandidatList as $k)
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
          <div>
            {{-- Header Card --}}
            <div class="flex items-center justify-between mb-4">
              <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-navy-900 text-white font-bold font-display text-sm">
                #{{ $k['id'] }}
              </span>
              <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ $k['color'] }}20; color: {{ $k['color'] }};">
                {{ $k['persen'] }}% dari suara masuk
              </span>
            </div>

            {{-- Foto & Nama --}}
            <div class="flex gap-4 items-center">
              <img src="{{ $k['image_url'] }}" alt="{{ $k['nama'] }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0">
              <div class="min-w-0">
                <h4 class="font-bold text-navy-900 text-base leading-snug truncate font-display">{{ $k['nama'] }}</h4>
                <p class="text-xs text-slate-500 mt-1">Kelas: {{ $k['kelas'] ?: '-' }}</p>
              </div>
            </div>
          </div>

          {{-- Perolehan Suara & Bar --}}
          <div class="mt-6 pt-4 border-t border-slate-100">
            <div class="flex justify-between items-baseline mb-2">
              <span class="text-xs text-slate-500">Perolehan Suara</span>
              <span class="text-2xl font-black font-display text-navy-900">{{ number_format($k['suara']) }} <span class="text-xs font-normal text-slate-400">suara</span></span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
              <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $k['persen'] }}%; background-color: {{ $k['color'] }};"></div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-3 p-8 bg-white rounded-2xl border border-dashed text-center text-slate-400">
          Belum ada data kandidat yang terdaftar.
        </div>
      @endforelse
    </div>
  </div>

  {{-- ==================== 5. GRAFIK VISUALISASI (REALTIME 1 MENIT) ==================== --}}
  {{-- wire:ignore penting agar Livewire tidak merusak atau me-render ulang elemen canvas --}}
  <div wire:ignore class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    
    {{-- Bar Chart Suara Kandidat --}}
    <div class="p-6 text-white rounded-3xl lg:col-span-2 bg-navy-900 sm:p-8 flex flex-col justify-between shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="font-bold font-display text-lg text-white">Grafik Suara per Paslon</h3>
          <p class="text-xs text-slate-400">Perbandingan perolehan suara antar kandidat (Realtime 1 Menit)</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-emerald-400 font-mono">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>Live 60s</span>
        </div>
      </div>
      <div class="h-80 relative w-full">
        <canvas id="chartSuaraKandidat"></canvas>
      </div>
    </div>

    {{-- Doughnut Chart Partisipasi --}}
    <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm sm:p-8 flex flex-col justify-between">
      <div>
        <h3 class="font-bold font-display text-lg text-navy-900">Komposisi Partisipasi</h3>
        <p class="text-xs text-slate-500">Perbandingan pemilih sudah vs belum voting</p>
      </div>
      <div class="h-64 relative my-auto w-full">
        <canvas id="chartPartisipasi"></canvas>
      </div>
      <div class="grid grid-cols-2 gap-2 pt-4 border-t border-slate-100 text-center text-xs">
        <div id="admin-memilih-count" class="p-2 bg-emerald-50 rounded-xl text-emerald-700 font-semibold">
          {{ number_format($suaraMasuk) }} Memilih
        </div>
        <div id="admin-belum-count" class="p-2 bg-amber-50 rounded-xl text-amber-700 font-semibold">
          {{ number_format($belumVoting) }} Belum
        </div>
      </div>
    </div>

  </div>

</div>

{{-- ==================== SCRIPT CHART.JS DENGAN UPDATE REALTIME ==================== --}}
@script
<script>
  let chartSuaraInstance = null;
  let chartPartisipasiInstance = null;

  // Helper agar nama panjang kandidat ter-wrap ke 2 baris rapi secara horizontal tanpa miring
  function wrapLabel(str, maxLen = 14) {
    if (!str) return '';
    if (Array.isArray(str)) return str;
    const words = str.split(' ');
    const lines = [];
    let current = '';
    words.forEach(w => {
      if ((current ? current + ' ' + w : w).length <= maxLen) {
        current = current ? current + ' ' + w : w;
      } else {
        if (current) lines.push(current);
        current = w;
      }
    });
    if (current) lines.push(current);
    return lines.length > 1 ? lines : str;
  }

  // Plugin center text untuk donut chart (menampilkan Total Suara di tengah)
  const centerTextAdminPlugin = {
    id: 'centerTextAdmin',
    beforeDraw(chart) {
      if (chart.config.type !== 'doughnut') return;
      const { ctx, chartArea } = chart;
      if (!chartArea) return;
      const { left, right, top, bottom } = chartArea;
      const values = chart.data.datasets[0].data;
      const sudah = Number(values[0]) || 0;
      const cx = (left + right) / 2;
      const cy = (top + bottom) / 2;

      ctx.save();
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillStyle = '#0f172a';
      ctx.font = "800 28px 'Poppins', sans-serif";
      ctx.fillText(sudah, cx, cy - 6);

      ctx.fillStyle = '#64748b';
      ctx.font = "600 11px 'Inter', sans-serif";
      ctx.fillText('SUARA MASUK', cx, cy + 18);
      ctx.restore();
    }
  };

  function initAdminCharts() {
    if (typeof Chart === 'undefined') {
      setTimeout(initAdminCharts, 150);
      return;
    }

    const rawLabels = @json($chartKandidatLabels);
    const rawVotes = @json($chartKandidatVotes);
    const sudah = {{ $suaraMasuk }};
    const belum = {{ $belumVoting }};

    // ================= 1. BAR CHART SUARA KANDIDAT =================
    const ctxSuara = document.getElementById('chartSuaraKandidat');
    if (ctxSuara && !chartSuaraInstance) {
      chartSuaraInstance = new Chart(ctxSuara, {
        type: 'bar',
        data: {
          labels: rawLabels.map(l => wrapLabel(l, 14)),
datasets: [{
    label: 'Suara Diperoleh',
    data: rawVotes,
    backgroundColor: ['#38BDF8', '#34D399', '#FBBF24', '#A78BFA', '#F472B6'],
    borderRadius: 8,
            barThickness: 100,
            maxBarThickness: 100,
            minBarLength: 8,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: { padding: { top: 15, bottom: 5, left: 10, right: 10 } },
          animation: { duration: 600, easing: 'easeOutQuart' },
          plugins: {
            legend: { display: false },
            tooltip: {
              padding: 12,
              backgroundColor: 'rgba(15, 23, 42, 0.95)',
              titleFont: { family: "'Poppins', sans-serif", weight: '600' },
              bodyFont: { family: "'Inter', sans-serif" },
              cornerRadius: 10,
              callbacks: {
                title: function(items) {
                  if (!items || !items.length) return '';
                  const raw = items[0].chart.data.labels[items[0].dataIndex];
                  return Array.isArray(raw) ? raw.join(' ') : raw;
                },
                label: function(ctx) {
                  return ' ' + ctx.parsed.y + ' Suara';
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: {
                maxRotation: 0,
                minRotation: 0,
                autoSkip: false,
                color: '#94A3B8',
                font: { family: "'Inter', sans-serif", size: 11, weight: '600' }
              }
            },
            y: {
              beginAtZero: true,
              suggestedMax: 5,
              grid: { color: 'rgba(255, 255, 255, 0.08)' },
              ticks: {
                stepSize: 1,
                precision: 0,
                color: '#94A3B8',
                font: { family: "'Inter', sans-serif", size: 11 }
              }
            }
          }
        }
      });
    }

    // ================= 2. DOUGHNUT CHART PARTISIPASI =================
    const ctxPartisipasi = document.getElementById('chartPartisipasi');
    if (ctxPartisipasi && !chartPartisipasiInstance) {
      chartPartisipasiInstance = new Chart(ctxPartisipasi, {
        type: 'doughnut',
        data: {
          labels: ['Sudah Memilih', 'Belum Memilih'],
          datasets: [{
            data: [sudah, belum],
            backgroundColor: ['#10B981', '#F59E0B'],
            borderWidth: 0,
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          layout: { padding: 10 },
          animation: { duration: 600, easing: 'easeOutQuart' },
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                boxWidth: 10,
                font: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                color: '#475569'
              }
            },
            tooltip: {
              padding: 10,
              backgroundColor: 'rgba(15, 23, 42, 0.95)',
              callbacks: {
                label: function(ctx) {
                  const total = ctx.dataset.data.reduce((a, b) => Number(a) + Number(b), 0);
                  const val = Number(ctx.parsed) || 0;
                  const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                  return ` ${val} pemilih (${pct}%)`;
                }
              }
            }
          }
        },
        plugins: [centerTextAdminPlugin]
      });
    }
  }

  // Update data chart tanpa merusak atau membuat ulang objek canvas (seperti di siswa/hasil)
  function updateAdminCharts(labels, votes, sudah, belum) {
    if (chartSuaraInstance) {
      chartSuaraInstance.data.labels = labels.map(l => wrapLabel(l, 14));
      chartSuaraInstance.data.datasets[0].data = votes;
      chartSuaraInstance.update();
    }
    if (chartPartisipasiInstance) {
      chartPartisipasiInstance.data.datasets[0].data = [sudah, belum];
      chartPartisipasiInstance.update();
    }
    $('#admin-memilih-count').text(new Intl.NumberFormat('id-ID').format(sudah) + ' Memilih');
    $('#admin-belum-count').text(new Intl.NumberFormat('id-ID').format(belum) + ' Belum');
  }

  // Polling AJAX berkala setiap 1 Menit (60.000 ms)
  function fetchAdminChartStats() {
    $.ajax({
      url: '{{ route("admin.chart.stats") }}',
      method: 'GET',
      success: function(res) {
        if (res && res.labels && res.suara) {
          updateAdminCharts(res.labels, res.suara, res.suaraMasuk, res.belumVoting);
        }
      },
      error: function(err) {
        console.error('Gagal mengambil data chart stats:', err);
      }
    });
  }

  // Inisialisasi awal
  initAdminCharts();

  // Pasang interval 1 menit (60 detik) untuk update realtime
  const adminChartInterval = setInterval(fetchAdminChartStats, 60000);

  // Jalankan saat navigasi Livewire
  document.addEventListener('livewire:navigated', function () {
    initAdminCharts();
    fetchAdminChartStats();
  });
</script>
@endscript