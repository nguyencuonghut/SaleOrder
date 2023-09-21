<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;

class RolesIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $roles = Role::where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('name', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();

        return view('livewire.roles-index', ['roles' => $roles])->layout('layouts.base');
    }
}
