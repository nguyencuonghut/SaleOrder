<?php

namespace App\Livewire;

use App\Models\Package;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PackagesCreate extends Component
{
    public $name;

    public function mount()
    {
        $this->name = null;
    }

    public function addPackage()
    {
        //Validate
        $rules = [
            'name'  => 'required|unique:packages',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bạn nhập đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $package = new Package();
        $package->name = $this->name;
        $package->save();

        $this->reset('name');
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/packages', navigate: true);

    }

    public function render()
    {
        return view('livewire.packages-create')->layout('layouts.base');
    }
}
