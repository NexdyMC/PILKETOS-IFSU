<?php
namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin.app', ['title' => 'Siswa - Dashboard'])]
class Siswa extends Component
{
    public function render()
    {
        return view('admin.dashboard.siswa');
    }
}