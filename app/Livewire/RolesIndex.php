<?php

namespace App\Livewire;

use App\Models\Role;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RolesIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editRoleIndex;
    public $name;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editRoleIndex = null;
        $this->name = '';
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

    public function edit($editRoleId)
    {
        $this->editRoleIndex = $editRoleId;
        $this->name = Role::findOrFail($editRoleId)->name;

    }

    public function save()
    {
        //Validate
        $rules = [
            'name'  => 'required',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
        ];

        $this->validate($rules, $messages);
        $role = Role::findOrFail($this->editRoleIndex);
        $role->name = $this->name;
        $role->save();

        $this->reset('editRoleIndex', 'name');
        Session::flash('success_message', 'Cập nhật thành công!');
    }


    public function cancel()
    {
        $this->reset('editRoleIndex', 'name');
        $this->resetErrorBag();
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
