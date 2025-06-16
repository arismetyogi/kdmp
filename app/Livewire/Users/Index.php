<?php

namespace App\Livewire\Users;

use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        return view('livewire.users.index');
    }
}
