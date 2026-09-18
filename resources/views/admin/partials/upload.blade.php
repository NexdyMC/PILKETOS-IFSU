<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Tambah Kandidat</h2>

    <div id="upload-alert"></div>

    <form id="form-upload-kandidat" action="{{ route('kandidat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kandidat</label>
            <input type="text" name="nama" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
            <select name="kelas" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="X RPL 1">X RPL 1</option>
                <option value="XI RPL 1">XI RPL 1</option>
                <option value="XII RPL 1">XII RPL 1</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Visi</label>
            <textarea name="visi" rows="3" class="w-full px-4 py-2 border rounded-lg" required></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Misi</label>
            <textarea name="misi" rows="4" class="w-full px-4 py-2 border rounded-lg" required></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Foto Kandidat</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
            Simpan Kandidat
        </button>
    </form>
</div>