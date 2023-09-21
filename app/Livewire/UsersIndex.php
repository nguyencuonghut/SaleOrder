<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UsersIndex extends Component
{
    public function render()
    {
        $users = User::all();
        return view('livewire.users-index', ['users' => $users])->layout('layouts.base');
    }
}
