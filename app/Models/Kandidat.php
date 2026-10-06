<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    protected $table = 'tb_kandidat';
    // Tambahkan baris ini untuk mematikan timestamps otomatis
    public $timestamps = false;
    // PK default "id" (auto increment) dan timestamps (created_at/updated_at) sesuai tabelmu
    protected $fillable = ['nama', 'kelas', 'visi', 'misi', 'image'];

    protected $appends = ['image_url'];
    protected $guarded = [];
    
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('upload/photo/' . $this->image)
            : 'https://placehold.co/600x400/EEF3FF/1E3A8A?text=No-Image';
    }
}