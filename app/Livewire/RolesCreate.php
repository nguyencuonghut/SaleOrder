<?php

namespace App\Livewire;

use App\Models\Role;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RolesCreate extends Component
{
    public $name;

    public function mount()
    {
        $this->name = null;
    }

    public function addRole()
    {
        //Validate
        $rules = [
            'name'  => 'required|unique:roles',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bạn nhập đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $role = new Role();
        $role->name = $this->name;
        $role->save();

        $this->reset('name');
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/roles');

    }

    public function render()
    {
        return view('livewire.roles-create')->layout('layouts.base');
    }
}
