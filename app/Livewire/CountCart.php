<?php

namespace App\Livewire;

use Livewire\Component;

class CountCart extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.count-cart');
    }
}
