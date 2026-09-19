<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    {{-- @vite('resources/css/app.css') --}}
    @livewireStyles
</head>
<body class="flex">

    <aside class="w-64 h-screen bg-gray-900 text-white p-4">
        <h2 class="text-xl font-bold mb-6">Dashboard</h2>
        <nav class="flex flex-col gap-2">
            <a href="{{ route('admin.dashboard.home') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🏠 Home
            </a>
            <a href="{{ route('admin.dashboard.kandidat') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🖼️ kandidat
            </a>
            <a href="{{ route('admin.dashboard.siswa') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🧑 Siswa
            </a>
            <a href="{{ route('admin.dashboard.settings') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🧑 settings
            </a>
            <a href="{{ route('admin.dashboard.export') }}" wire:navigate class="hover:bg-gray-700 p-2 rounded">
                🧑 export
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>