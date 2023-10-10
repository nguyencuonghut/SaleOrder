<?php

namespace App\Livewire;

use Livewire\Component;

class GroupsIndex extends Component
{
    public function render()
    {
        return view('livewire.groups-index')->layout('layouts.base');
    }
}
