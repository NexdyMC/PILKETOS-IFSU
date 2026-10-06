<?php
namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin.app', ['title' => 'Dashboard'])]
class Home extends Component
{
    public function render()
    {
        return view('admin.dashboard.home');
    }
}