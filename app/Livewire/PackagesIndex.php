<?php

namespace App\Livewire;

use App\Models\Package;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PackagesIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editPackageIndex;
    public $deletedPackageIndex;
    public $name;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editPackageIndex = null;
        $this->deletedPackageIndex = null;
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

    public function edit($editPackageId)
    {
        $this->editPackageIndex = $editPackageId;
        $this->name = Package::findOrFail($editPackageId)->name;

    }

    public function save()
    {
        //Validate
        $rules = [
            'name'  => [
                'required',
                Rule::unique('packages')->ignore($this->editPackageIndex),
                ]
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên đã tồn tại. Bạn vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $package = Package::findOrFail($this->editPackageIndex);
        $package->name = $this->name;
        $package->save();

        $this->reset('editPackageIndex', 'name');
        Session::flash('success_message', 'Cập nhật thành công!');
    }


    public function cancel()
    {
        $this->reset('editPackageIndex', 'name', 'deletedPackageIndex');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedPackageIndex = $id;
    }

    public function destroy()
    {
        // Destroy package
        $package = Package::findOrFail($this->deletedPackageIndex);
        $package->destroy($this->deletedPackageIndex);

        $this->reset('deletedPackageIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $packages = Package::where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('name', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
        return view('livewire.packages-index', ['packages' => $packages])->layout('layouts.base');
    }
}
