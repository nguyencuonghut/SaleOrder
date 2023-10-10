<?php

namespace App\Livewire;

use Livewire\Component;

class CategoriesIndex extends Component
{
    public function render()
    {
        return view('livewire.categories-index')->layout('layouts.base');
    }
}
