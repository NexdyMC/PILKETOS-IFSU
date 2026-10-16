@php
  $ogSetting = class_exists(\App\Models\Settings::class) ? \App\Models\Settings::ambil() : null;
  $ogSekolah = $ogSetting->nama_sekolah ?? 'SMK Informatika Sumedang';
  $ogJudul = $ogSetting->judul_pemilihan ?? 'E-VOTE OSIS';
  $ogDeskripsi = 'Satu suara satu arah untuk OSIS yang lebih baik. Gunakan hak pilihmu dalam pemilihan OSIS ' . $ogSekolah . '.';
  $ogBanner = url('assets/images/banner-og.png');
@endphp

<!-- Open Graph / WhatsApp / Facebook / Telegram Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $ogJudul }} — {{ $ogSekolah }}">
<meta property="og:description" content="{{ $ogDeskripsi }}">
<meta property="og:image" content="{{ $ogBanner }}">
<meta property="og:image:secure_url" content="{{ $ogBanner }}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Banner {{ $ogJudul }}">
<meta property="og:site_name" content="{{ $ogJudul }}">

<!-- Twitter / X Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $ogJudul }} — {{ $ogSekolah }}">
<meta name="twitter:description" content="{{ $ogDeskripsi }}">
<meta name="twitter:image" content="{{ $ogBanner }}">

<!-- PWA Manifest & Meta Tags -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#0f172a">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Pilketos">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">

<!-- PWA Service Worker & Install Script -->
<script src="{{ asset('js/pwa.js') }}?v={{ filemtime(public_path('js/pwa.js')) }}" defer></script>
