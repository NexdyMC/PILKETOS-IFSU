<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Pengaturan pemilihan. Tabel tb_settings hanya berisi SATU baris.
 *
 * Aturan buka/tutup voting (gabungan manual + otomatis):
 *   voting terbuka  = saklar (status_voting) menyala  DAN  waktu sekarang di antara waktu_mulai dan waktu_selesai
 *   setelah waktu_selesai lewat, voting selalu tertutup dan saklar tidak bisa dinyalakan
 */
class Settings extends Model
{
    protected $table = 'tb_settings';
    public $timestamps = false;

    protected $fillable = [
        'nama_sekolah',
        'judul_pemilihan',
        'tahun_ajaran',
        'status_voting',
        'view_voted',
        'waktu_mulai',
        'waktu_selesai',
        'logo_sekolah',
    ];

    protected $casts = [
        'status_voting' => 'boolean',
        'view_voted'    => 'int',
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    private const CACHE_KEY = 'app.settings';

    /** Ambil pengaturan (di-cache). Kalau tabel masih kosong, kembalikan nilai bawaan (voting tertutup). */
    public static function ambil(): self
    {
        $tersimpan = Cache::get(self::CACHE_KEY);
        if ($tersimpan instanceof self) {
            return $tersimpan;
        }

        $baris = static::query()->first();
        if ($baris) {
            Cache::put(self::CACHE_KEY, $baris, 3600);
            return $baris;
        }

        return new static([
            'nama_sekolah'    => 'Nama Sekolah',
            'judul_pemilihan' => 'E-VOTE OSIS',
            'tahun_ajaran'    => date('Y') . '-' . (date('Y') + 1),
            'status_voting'   => false,
            'view_voted'      => '0',
        ]);
    }

    /** Wajib dipanggil setiap kali pengaturan diubah. */
    public static function hapusCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function jadwalBerakhir(): bool
    {
        return $this->waktu_selesai !== null && now()->greaterThanOrEqualTo($this->waktu_selesai);
    }

    /** ditutup | terjadwal | berlangsung | berakhir */
    public function statusJadwal(): string
    {
        if ($this->jadwalBerakhir()) {
            return 'berakhir';
        }
        if (!$this->status_voting) {
            return 'ditutup';
        }
        if ($this->waktu_mulai !== null && now()->lt($this->waktu_mulai)) {
            return 'terjadwal';
        }
        return 'berlangsung';
    }

    public function votingBuka(): bool
    {
        return $this->statusJadwal() === 'berlangsung';
    }

    /** Pesan untuk siswa saat voting tidak bisa dipakai. */
    public function pesanTutup(): string
    {
        return match ($this->statusJadwal()) {
            'terjadwal' => 'Voting belum dimulai. Voting dibuka pada ' . $this->waktu_mulai->format('d M Y, H:i') . '.',
            'berakhir'  => 'Waktu voting sudah berakhir. Terima kasih atas partisipasimu.',
            default     => 'Voting sedang ditutup oleh panitia.',
        };
    }

    /** URL logo: prioritas storage/logo (hasil upload), lalu public/images, lalu public. */
    public function logoUrl(): ?string
    {
        $nama = basename((string) $this->logo_sekolah);
        if ($nama === '') {
            return null;
        }

        $disk = Storage::disk('public');
        if ($disk->exists('logo/' . $nama)) {
            return asset('storage/app/public/logo/' . $nama) . '?v=' . $disk->lastModified('logo/' . $nama);
        }
        if (file_exists(public_path('images/' . $nama))) {
            return asset('images/' . $nama);
        }
        if (file_exists(public_path($nama))) {
            return asset($nama);
        }
        return null;
    }
}