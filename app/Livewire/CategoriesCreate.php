<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CategoriesCreate extends Component
{
    public $name;
    public $description;

    public function mount()
    {
        $this->name = null;
        $this->description = null;
    }

    public function addCategory()
    {
        //Validate
        $rules = [
            'name'  => 'required|unique:categories',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bạn nhập đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $category = new Category();
        $category->name = $this->name;
        $category->description = $this->description;
        $category->save();

        $this->reset(['name', 'description']);
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/categories', navigate: true);

    }

    public function render()
    {
        return view('livewire.categories-create')->layout('layouts.base');
    }
}
