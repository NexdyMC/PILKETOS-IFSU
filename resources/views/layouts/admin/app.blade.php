<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- AOS --}}
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="text-gray-800 bg-gray-100">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-56 p-4 space-y-2 text-white bg-gray-900">
            <h1 class="mb-6 text-lg font-bold">My Dashboard</h1>

            <a href="/dashboard?tab=dashboard" class="block px-3 py-2 rounded tab-link hover:bg-gray-700" data-tab="dashboard">
                dashboard
            </a>
            <a href="/dashboard?tab=kandidat" class="block px-3 py-2 rounded tab-link hover:bg-gray-700" data-tab="kandidat">
                kandidat
            </a>
            <a href="/dashboard?tab=settings" class="block px-3 py-2 rounded tab-link hover:bg-gray-700" data-tab="settings">
                Settings
            </a>
        </aside>

        {{-- Main area --}}
        <div class="flex flex-col flex-1">
            {{-- Navbar --}}
            <nav class="flex items-center justify-between px-6 py-3 bg-white shadow">
                <span id="page-title" class="font-semibold text-gray-700">Dashboard</span>
                <span class="text-sm text-gray-400">Logged in as Admin</span>
            </nav>

            {{-- Loading bar tipis --}}
            <div id="loading-bar" class="h-0.5 bg-blue-500 w-0 transition-all duration-300"></div>

            {{-- Content --}}
            <main id="tab-content" class="flex-1 p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="py-4 text-xs text-center text-gray-400">
                &copy; {{ date('Y') }} My App
            </footer>
        </div>
    </div>

    {{-- <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script> --}}
    <script src="{{ asset('js/navigation.js') }}"></script>
</body>
</html>