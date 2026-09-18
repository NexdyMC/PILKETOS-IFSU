
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6 mt-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Tambah Kandidat</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kandidat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kandidat</label>
            <input type="text" name="nama" value="{{ old('nama') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Masukkan nama kandidat" required>
        </div>

        <!-- Kelas -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
            <select name="kelas" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="X RPL 1" {{ old('kelas') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                <option value="XI RPL 1" {{ old('kelas') == 'XI RPL 1' ? 'selected' : '' }}>XI RPL 1</option>
                <option value="XII RPL 1" {{ old('kelas') == 'XII RPL 1' ? 'selected' : '' }}>XII RPL 1</option>
                <!-- tambahkan sesuai kelas yang ada -->
            </select>
        </div>

        <!-- Visi -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Visi</label>
            <textarea name="visi" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Tuliskan visi kandidat" required>{{ old('visi') }}</textarea>
        </div>

        <!-- Misi -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Misi</label>
            <textarea name="misi" rows="4"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Tuliskan misi kandidat (pisahkan per baris)" required>{{ old('misi') }}</textarea>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Foto Kandidat</label>
            <input type="file" name="image" id="image-input" accept="image/*"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            
            <!-- preview gambar sebelum submit -->
            <img id="image-preview" src="#" alt="Preview"
                class="hidden mt-3 w-40 h-40 object-cover rounded-lg border">
        </div>

        <button type="submit"
            class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            Simpan Kandidat
        </button>
    </form>
</div>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $('#image-input').on('change', function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).removeClass('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>
