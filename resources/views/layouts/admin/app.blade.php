<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - PILKETOS IFSU</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS (opsional, kalau dipakai) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css">
</head>
<body class="bg-slate-100">

    <!-- LOADING BAR ala GitHub -->
    <div id="loading-bar" class="fixed top-0 left-0 h-1 bg-blue-500 z-50 transition-all duration-300 ease-out" style="width: 0%;"></div>

    <div class="flex">
        <!-- SIDEBAR -->
        <div class="w-64 bg-slate-900 min-h-screen p-4">
            <nav class="space-y-2">
                <a href="/admin/dashboard?tab=home" data-tab="home" class="tab-link block px-4 py-2 rounded-lg text-white hover:bg-slate-700">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="/admin/dashboard?tab=upload" data-tab="upload" class="tab-link block px-4 py-2 rounded-lg text-white hover:bg-slate-700">
                    <i class="fa-solid fa-upload"></i> Upload Kandidat
                </a>
                <a href="/admin/dashboard?tab=kandidat" data-tab="kandidat" class="tab-link block px-4 py-2 rounded-lg text-white hover:bg-slate-700">
                    <i class="fa-solid fa-list"></i> Data Kandidat
                </a>
                <a href="/admin/dashboard?tab=hasil" data-tab="hasil" class="tab-link block px-4 py-2 rounded-lg text-white hover:bg-slate-700">
                    <i class="fa-solid fa-chart-line"></i> Hasil Voting
                </a>
            </nav>
        </div>

        <!-- KONTEN -->
        <div class="flex-1 p-6">
            <h1 id="page-title" class="text-2xl font-bold mb-4">Dashboard</h1>
            <div id="tab-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script