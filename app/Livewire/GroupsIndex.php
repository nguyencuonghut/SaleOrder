<?php

namespace App\Livewire;

use App\Models\Group;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GroupsIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editGroupIndex;
    public $deletedGroupIndex;
    public $name;
    public $description;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editGroupIndex = null;
        $this->deletedGroupIndex = null;
        $this->name = '';
        $this->description = '';
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

    public function edit($editGroupId)
    {
        $group = Group::findOrFail($editGroupId);
        $this->editGroupIndex = $group->id;
        $this->name = $group->name;
        $this->description = $group->description;

    }

    public function save()
    {
        //Validate
        $rules = [
            'name'  => [
                'required',
                Rule::unique('groups')->ignore($this->editGroupIndex),
                ]
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên đã tồn tại. Bạn vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $group = Group::findOrFail($this->editGroupIndex);
        $group->name = $this->name;
        $group->description = $this->description;
        $group->save();

        $this->reset('editGroupIndex', 'name');
        Session::flash('success_message', 'Cập nhật thành công!');
    }


    public function cancel()
    {
        $this->reset('editGroupIndex', 'name', 'description', 'deletedGroupIndex');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedGroupIndex = $id;
    }

    public function destroy()
    {
        // Destroy group
        $group = Group::findOrFail($this->deletedGroupIndex);
        $group->destroy($this->deletedGroupIndex);

        $this->reset('deletedGroupIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $groups = Group::where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('name', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
        return view('livewire.groups-index', ['groups' => $groups])->layout('layouts.base');
    }
}
