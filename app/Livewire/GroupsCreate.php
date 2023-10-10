<?php

namespace App\Livewire;

use App\Models\Group;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class GroupsCreate extends Component
{
    public $name;
    public $description;

    public function mount()
    {
        $this->name = null;
        $this->description = null;
    }

    public function addGroup()
    {
        //Validate
        $rules = [
            'name'  => 'required|unique:groups',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bạn nhập đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $group = new Group();
        $group->name = $this->name;
        $group->description = $this->description;
        $group->save();

        $this->reset(['name', 'description']);
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/groups', navigate: true);

    }

    public function render()
    {
        return view('livewire.groups-create')->layout('layouts.base');
    }
}
