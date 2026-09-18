<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite('resources/css/app.css') --}}
    @livewireStyles
</head>
<body class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 h-screen bg-gray-900 text-white p-4">
        <h2 class="text-xl font-bold mb-6">Dashboard</h2>
        <nav class="flex flex-col gap-2">
            <a href="{{ route('dashboard.home') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🏠 Home
            </a>
            <a href="{{ route('dashboard.image') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🖼️ Image
            </a>
            <a href="{{ route('dashboard.siswa') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🧑‍🎓 Siswa
            </a>
        </nav>
    </aside>

    {{-- KONTEN --}}
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>