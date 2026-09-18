<h2 class="text-xl font-bold mb-4">Data Kandidat</h2>

<table class="w-full bg-white rounded-xl shadow-sm">
    <thead>
        <tr class="border-b">
            <th class="p-3 text-left">Foto</th>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Kelas</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kandidat ?? [] as $k)
        <tr class="border-b">
            <td class="p-3"><img src="{{ asset('storage/kandidat/' . $k->image) }}" class="w-12 h-12 object-cover rounded"></td>
            <td class="p-3">{{ $k->nama }}</td>
            <td class="p-3">{{ $k->kelas }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="p-3 text-center text-slate-400">Belum ada data kandidat</td></tr>
        @endforelse
    </tbody>
</table>