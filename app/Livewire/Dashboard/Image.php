<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin.app')]
class Image extends Component
{
    public function render()
    {
        return view('components.dashboard.image');
    }
}