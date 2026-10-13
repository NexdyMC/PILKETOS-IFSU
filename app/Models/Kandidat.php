<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kandidat extends Model
{
    protected $table = 'tb_kandidat';
    // Tambahkan baris ini untuk mematikan timestamps otomatis
    public $timestamps = false;

    protected $fillable = ['nama', 'kelas', 'visi', 'misi', 'image'];

    protected $appends = ['image_url'];
    protected $guarded = [];
    
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // aman untuk dua format lama di database: "abc.jpg" dan "kandidat/abc.jpg"
            $nama = basename($this->image);
            $disk = Storage::disk('public');

            if ($disk->exists('kandidat/' . $nama)) {
                return asset('storage/kandidat/' . $nama) . '?v=' . $disk->lastModified('kandidat/' . $nama);
            }
            // foto lama yang belum dipindah (hanya dibaca; hapus blok ini setelah semua dipindah)
            if (file_exists(public_path('upload/photo/' . $nama))) {
                return asset('upload/photo/' . $nama);
            }
        }

        return 'https://placehold.co/600x400/EEF3FF/1E3A8A?text=No-Image';
    }
}