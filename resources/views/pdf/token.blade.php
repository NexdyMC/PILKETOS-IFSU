<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Token Pemilih</title>
<style>
  @page { margin: 36px 36px 54px 36px; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #0f172a; }
  .pisah { page-break-after: always; }

  .footer { position: fixed; bottom: -34px; left: 0; right: 0; text-align: center; font-size: 9px; color: #64748b; }
  .pagenum:before { content: counter(page); }

  .kop { border-bottom: 2px solid #1d4ed8; padding-bottom: 8px; margin-bottom: 14px; }
  .kop .judul { font-size: 16px; font-weight: bold; color: #1e3a8a; }
  .kop .sub { font-size: 10px; color: #475569; margin-top: 2px; }
  h2 { font-size: 14px; margin: 0 0 3px 0; }
  .meta { font-size: 10px; color: #64748b; margin: 0 0 10px 0; }

  /* ===== Layout: daftar (untuk guru / wali kelas) ===== */
  table.tabel { width: 100%; border-collapse: collapse; }
  table.tabel thead { display: table-header-group; }
  table.tabel th { background: #e2e8f0; text-align: left; padding: 7px 8px; font-size: 10px; border: 1px solid #cbd5e1; }
  table.tabel td { padding: 6px 8px; border: 1px solid #e2e8f0; }
  table.tabel tr { page-break-inside: avoid; }
  table.tabel tbody tr:nth-child(even) td { background: #f8fafc; }
  .no { width: 28px; text-align: center; }
  .token { width: 110px; font-family: 'DejaVu Sans Mono', monospace; font-weight: bold; font-size: 13px; letter-spacing: 2px; }
  .catatan { margin-top: 12px; font-size: 9px; color: #64748b; }

  /* ===== Layout: kartu (dipotong lalu dibagikan ke murid) ===== */
  table.grid { width: 100%; border-collapse: separate; border-spacing: 8px; }
  table.grid tr { page-break-inside: avoid; }
  td.sel { width: 50%; vertical-align: top; padding: 0; }
  .kartu { border: 1px dashed #94a3b8; border-radius: 8px; padding: 10px 12px; height: 132px; overflow: hidden; }
  .kartu .atas { font-size: 8px; color: #1d4ed8; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
  .kartu .nama { font-size: 11px; font-weight: bold; margin-top: 5px; }
  .kartu .kls { font-size: 9px; color: #475569; }
  .kartu .kotak { margin-top: 7px; border: 1px solid #1d4ed8; border-radius: 6px; text-align: center; padding: 5px 0; background: #eff6ff; }
  .kartu .kotak .lbl { font-size: 7px; color: #475569; letter-spacing: 1px; }
  .kartu .kotak .tok { font-family: 'DejaVu Sans Mono', monospace; font-size: 22px; font-weight: bold; letter-spacing: 5px; color: #1e3a8a; }
  .kartu .bawah { margin-top: 5px; font-size: 7px; color: #64748b; }
</style>
</head>
<body>

<div class="footer">Rahasia &middot; {{ $pengaturan['judul'] }} &middot; Halaman <span class="pagenum"></span></div>

@foreach ($kelompok as $kelas => $rows)
<div class="{{ $loop->last ? '' : 'pisah' }}">

  <div class="kop">
    <div class="judul">{{ $pengaturan['judul'] }}</div>
    <div class="sub">{{ $pengaturan['sekolah'] }}@if ($pengaturan['tahun']) &middot; Tahun Ajaran {{ $pengaturan['tahun'] }}@endif</div>
  </div>

  @if ($layout === 'kartu')
    <h2>Kartu Token Pemilih &mdash; Kelas {{ $kelas }}</h2>
    <p class="meta">{{ $rows->count() }} siswa &middot; Dicetak {{ $dicetak }} &middot; Gunting sesuai garis putus-putus</p>

    <table class="grid">
      @foreach ($rows->chunk(2) as $pasang)
      <tr>
        @foreach ($pasang as $s)
        <td class="sel">
          <div class="kartu">
            <div class="atas">{{ $pengaturan['judul'] }}</div>
            <div class="nama">{{ $s->nama }}</div>
            <div class="kls">Kelas {{ $s->kelas }}</div>
            <div class="kotak">
              <div class="lbl">TOKEN LOGIN</div>
              <div class="tok">{{ $s->token }}</div>
            </div>
            <div class="bawah">Login di {{ $urlVoting }} &middot; Rahasia, jangan dibagikan.</div>
          </div>
        </td>
        @endforeach
        @if ($pasang->count() === 1)
        <td class="sel"></td>
        @endif
      </tr>
      @endforeach
    </table>
  @else
    <h2>Daftar Token Pemilih &mdash; Kelas {{ $kelas }}</h2>
    <p class="meta">{{ $rows->count() }} siswa &middot; Dicetak {{ $dicetak }}</p>

    <table class="tabel">
      <thead>
        <tr>
          <th class="no">No</th>
          <th>Nama</th>
          <th>kelas</th>
          <th>Token</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($rows as $s)
        <tr>
          <td class="no">{{ $loop->iteration }}</td>
          <td>{{ $s->nama }}</td>
          <td>{{ $s->kelas }}</td>
          <td class="token">{{ $s->token }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <p class="catatan">Token bersifat rahasia dan hanya untuk siswa yang bersangkutan. Login di {{ $urlVoting }}</p>
  @endif

</div>
@endforeach

</body>
</html>