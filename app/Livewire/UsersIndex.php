<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public $search;

    public function mount()
    {
        $this->search = '';
    }

    public function render()
    {
        $users = User::query()
                    ->whereLike('name', $this->search)
                    ->whereLike('email', $this->search)
                    ->orWhereHas('role', function($q)
                    {
                        $q->where('name', 'like', '%'.$this->search.'%');

                    })
                    ->paginate(10);
        return view('livewire.users-index', ['users' => $users])->layout('layouts.base');
    }
}
