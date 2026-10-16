<?php

namespace App\Livewire\Dashboard;

use App\Models\Kandidat;
use App\Models\Settings;
use App\Models\Siswa;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin.app', ['title' => 'Dashboard'])]
class Home extends Component
{
    public function render()
    {
        $setting = Settings::ambil();
        $totalDpt = Siswa::count();
        $suaraMasuk = Siswa::where('status', 1)->count();
        $belumVoting = Siswa::where('status', 0)->count();
        $persenPartisipasi = $totalDpt > 0 ? round(($suaraMasuk / $totalDpt) * 100, 1) : 0;
        $persenBelum = $totalDpt > 0 ? round(($belumVoting / $totalDpt) * 100, 1) : 0;

        // Ambil data siswa untuk pengelompokan
        $semuaSiswa = Siswa::select('kelas', 'status', 'voted')->get();

        $kelompokData = [
            'XII' => [
                'nama' => 'Kelas XII',
                'deskripsi' => 'Siswa Tingkat Akhir',
                'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'icon' => 'fa-graduation-cap',
                'iconColor' => 'text-indigo-600',
                'iconBg' => 'bg-indigo-50',
                'barColor' => 'bg-indigo-600',
                'total' => 0,
                'voted' => 0,
                'belum' => 0,
                'persen' => 0,
            ],
            'XI' => [
                'nama' => 'Kelas XI',
                'deskripsi' => 'Siswa Tingkat Menengah',
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                'icon' => 'fa-user-graduate',
                'iconColor' => 'text-blue-600',
                'iconBg' => 'bg-blue-50',
                'barColor' => 'bg-blue-600',
                'total' => 0,
                'voted' => 0,
                'belum' => 0,
                'persen' => 0,
            ],
            'X' => [
                'nama' => 'Kelas X',
                'deskripsi' => 'Siswa Tingkat Pertama',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'icon' => 'fa-book-open-reader',
                'iconColor' => 'text-emerald-600',
                'iconBg' => 'bg-emerald-50',
                'barColor' => 'bg-emerald-600',
                'total' => 0,
                'voted' => 0,
                'belum' => 0,
                'persen' => 0,
            ],
            'Guru' => [
                'nama' => 'Guru & Staf',
                'deskripsi' => 'Pendidik & Tenaga Kependidikan',
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                'icon' => 'fa-chalkboard-user',
                'iconColor' => 'text-amber-600',
                'iconBg' => 'bg-amber-50',
                'barColor' => 'bg-amber-500',
                'total' => 0,
                'voted' => 0,
                'belum' => 0,
                'persen' => 0,
            ],
        ];

        foreach ($semuaSiswa as $s) {
            $k = trim((string) $s->kelas);
            $key = null;
            if (stripos($k, 'Guru') !== false) {
                $key = 'Guru';
            } elseif (preg_match('/^(XII|12)\b/i', $k)) {
                $key = 'XII';
            } elseif (preg_match('/^(XI|11)\b/i', $k)) {
                $key = 'XI';
            } elseif (preg_match('/^(X|10)\b/i', $k)) {
                $key = 'X';
            }

            if ($key && isset($kelompokData[$key])) {
                $kelompokData[$key]['total']++;
                if ((int) $s->status === 1) {
                    $kelompokData[$key]['voted']++;
                }
            }
        }

        foreach ($kelompokData as &$item) {
            $item['belum'] = $item['total'] - $item['voted'];
            $item['persen'] = $item['total'] > 0 ? round(($item['voted'] / $item['total']) * 100, 1) : 0;
        }
        unset($item);

        // Data Kandidat
        $kandidats = Kandidat::orderBy('id')->get();
        $kandidatList = [];
        $chartKandidatLabels = [];
        $chartKandidatVotes = [];
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];

        foreach ($kandidats as $idx => $k) {
            $suara = Siswa::where('voted', $k->id)->count();
            $persen = $suaraMasuk > 0 ? round(($suara / $suaraMasuk) * 100, 1) : 0;

            $kandidatList[] = [
                'id' => $k->id,
                'nama' => $k->nama,
                'kelas' => $k->kelas,
                'image_url' => $k->image_url,
                'suara' => $suara,
                'persen' => $persen,
                'color' => $colors[$idx % count($colors)],
            ];

            $chartKandidatLabels[] = $k->nama;
            $chartKandidatVotes[] = $suara;
        }

        return view('admin.dashboard.home', [
            'setting' => $setting,
            'totalDpt' => $totalDpt,
            'suaraMasuk' => $suaraMasuk,
            'belumVoting' => $belumVoting,
            'persenPartisipasi' => $persenPartisipasi,
            'persenBelum' => $persenBelum,
            'kelompokData' => $kelompokData,
            'kandidatList' => $kandidatList,
            'chartKandidatLabels' => $chartKandidatLabels,
            'chartKandidatVotes' => $chartKandidatVotes,
        ]);
    }
}