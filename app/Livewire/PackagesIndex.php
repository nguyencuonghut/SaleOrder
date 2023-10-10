<?php

namespace App\Livewire;

use Livewire\Component;

class PackagesIndex extends Component
{
    public function render()
    {
        return view('livewire.packages-index')->layout('layouts.base');
    }
}
