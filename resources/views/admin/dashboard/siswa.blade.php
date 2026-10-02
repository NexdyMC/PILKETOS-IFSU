<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <h1 class="text-2xl font-bold">Halaman Siswa</h1>
    <p>Ini halaman siswa dashboard.</p>
    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>
</div>